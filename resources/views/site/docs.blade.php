@extends('layouts.site')
@php
$cfg = config('docs');
$flat = [];
foreach ($cfg['groups'] as $group => $pages) {
	foreach ($pages as $s => $p) { $flat[$s] = $p + ['group' => $group, 'slug' => $s, 'url' => $p['external'] ?? ($s === 'getting-started' ? '/docs' : '/docs/'.$s)]; }
}
$keys = array_keys($flat);
$i = array_search($slug, $keys, true);
$prev = $i > 0 ? $flat[$keys[$i - 1]] : null;
$next = $flat[$keys[$i + 1] ?? null] ?? null;
$index = [];
foreach ($flat as $p) {
	if (isset($p['external'])) { $index[] = ['t' => $p['title'], 'u' => $p['url'], 'x' => $p['title']]; continue; }
	$index[] = ['t' => $p['title'], 'u' => $p['url'], 'x' => strip_tags($p['intro'].' '.implode(' ', array_map(fn ($s) => $s[1].' '.$s[2], $p['sections'])))];
}
@endphp
@section('title', $page['title'].' · Documentation')
@section('description', $page['intro'])
@if (! empty($page['draft']))
@push('head')<meta name="robots" content="noindex,follow">@endpush
@endif
@section('content')
<div class="s-wrap" style="padding-top:32px">
	<form class="s-search" role="search" onsubmit="return false"><svg width="18" height="18" viewBox="0 0 20 20" fill="none" stroke="currentColor" stroke-width="1.75" stroke-linecap="round" aria-hidden="true"><circle cx="9" cy="9" r="5.5"/><path d="M13.5 13.5L17 17"/></svg><input id="doc-search" type="search" name="q" placeholder="Search the docs" autocomplete="off" aria-label="Search the docs" aria-controls="doc-results"></form>
	<ul id="doc-results" class="s-results" hidden></ul>
</div>
<div class="s-wrap s-docs">
	<nav class="tree" aria-label="Documentation">
		@foreach ($cfg['groups'] as $group => $pages)
			<h4>{{ $group }}</h4>
			@foreach ($pages as $s => $p)
				<a href="{{ $flat[$s]['url'] }}" @if($s === $slug) class="on" aria-current="page" @endif>{{ $p['title'] }}</a>
			@endforeach
		@endforeach
	</nav>
	<article class="s-prose rv in" id="doc-body">
		<p class="s-meta">{{ $page['title'] }} · Updated {{ $cfg['updated'] }}</p>
		<h1>{{ $page['title'] }}</h1>
		<p class="s-doc-intro">{{ $page['intro'] }}</p>
		@foreach ($page['sections'] as [$id, $h, $html])
			<section id="{{ $id }}"><h2>{{ $h }}</h2>{!! $html !!}</section>
		@endforeach
		<nav class="s-pn" aria-label="Previous and next">
			<span>@if ($prev)Previous: <a href="{{ $prev['url'] }}">{{ $prev['title'] }}</a>@else Previous: none @endif</span>
			<span>@if ($next)<a href="{{ $next['url'] }}">Next: {{ $next['title'] }}</a>@endif</span>
		</nav>
	</article>
	<aside class="toc" aria-label="On this page">
		<p class="s-meta" style="margin:0 0 8px">On this page</p>
		@foreach ($page['sections'] as [$id, $h])<a href="#{{ $id }}">{{ $h }}</a>@endforeach
	</aside>
</div>
<script>
(function () {
	var box = document.getElementById('doc-search'), out = document.getElementById('doc-results');
	if (!box) return;
	var idx = {!! json_encode($index, JSON_UNESCAPED_UNICODE | JSON_HEX_TAG) !!};
	box.addEventListener('input', function () {
		var q = box.value.trim().toLowerCase();
		out.innerHTML = '';
		if (!q) { out.hidden = true; return; }
		var hits = idx.filter(function (d) { return (d.t + ' ' + d.x).toLowerCase().indexOf(q) > -1; });
		hits.forEach(function (d) { var li = document.createElement('li'), a = document.createElement('a'); a.href = d.u; a.textContent = d.t; li.appendChild(a); out.appendChild(li); });
		if (!hits.length) { var li = document.createElement('li'); li.textContent = 'No matching page. Try another word.'; out.appendChild(li); }
		out.hidden = false;
	});
	var pre = new URLSearchParams(location.search).get('q');
	if (pre) { box.value = pre; box.dispatchEvent(new Event('input')); }
	document.addEventListener('click', function (e) { var b = e.target.closest('.s-copy'); if (b) { var t = b.getAttribute('data-copy'); if (navigator.clipboard) navigator.clipboard.writeText(t); b.textContent = 'Copied'; setTimeout(function () { b.textContent = 'Copy'; }, 1600); } }, true);
})();
</script>
@endsection
