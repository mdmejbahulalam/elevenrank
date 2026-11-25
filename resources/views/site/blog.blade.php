@extends('layouts.site')
@section('title', 'Blog')
@section('description', 'Notes on WordPress SEO from the makers of ElevenRank: how-to guides, comparisons and release notes.')
@if (empty($posts))
	@push('head')<meta name="robots" content="noindex">@endpush
@endif
@section('content')
@php $cat = request('category'); $shown = collect($posts)->when($cat, fn ($c) => $c->where('category', $cat)); @endphp
<section class="s-sec" style="padding-bottom:24px">
	<div class="s-wrap">
		<p class="s-eyebrow">Blog</p>
		<h1 class="s-d-xl" style="font-size:clamp(34px,5vw,44px)">Notes on WordPress SEO</h1>
		<div class="s-row" style="margin-top:16px">
			<a class="s-pill @if(! $cat) ok @endif" href="/blog">All</a>
			@foreach (['How-to', 'Comparisons', 'Releases'] as $c)<a class="s-pill @if($cat === $c) ok @endif" href="/blog?category={{ urlencode($c) }}">{{ $c }}</a>@endforeach
		</div>
	</div>
</section>
<section class="s-sec" style="padding-top:32px">
	<div class="s-wrap">
		@if ($shown->isEmpty())
			<div class="s-card"><h2 class="s-h">No posts yet</h2><p>We publish when we have something useful to say. In the meantime, the <a href="/docs">docs</a> and the <a href="/changelog">changelog</a> are up to date.</p></div>
		@else
			<div class="s-grid">
				@foreach ($shown as $p)
					<a class="s-post" href="/blog/{{ $p['slug'] }}" style="text-decoration:none;color:inherit"><div class="img">Cover image</div><span class="s-meta">{{ $p['category'] }} · {{ $p['minutes'] }} min read</span><h3>{{ $p['title'] }}</h3><p class="s-muted" style="margin:0">{{ $p['summary'] }}</p></a>
				@endforeach
			</div>
		@endif
	</div>
</section>
@endsection
