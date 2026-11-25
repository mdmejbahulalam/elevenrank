@extends('layouts.site')
@section('title', $post['title'])
@section('description', $post['summary'])
@section('content')
<article class="s-sec" style="padding:56px 0">
	<div class="s-wrap s-narrow s-prose">
		<p class="s-meta"><a href="/blog" style="text-decoration:none">Blog</a> · {{ $post['category'] }} · {{ $post['minutes'] }} min read</p>
		<h1 style="font-size:clamp(32px,5vw,44px);line-height:1.1">{{ $post['title'] }}</h1>
		<p class="s-lead" style="margin-bottom:16px">{{ $post['summary'] }}</p>
		<p class="s-muted s-small">By {{ $post['author'] }} · {{ $post['date'] }}</p>
		@foreach ($post['body'] as $b)
			<h2>{{ $b['h'] }}</h2>
			<p>{{ $b['p'] }}</p>
		@endforeach
		<div class="s-card" style="margin-top:32px;display:flex;flex-wrap:wrap;gap:16px;align-items:center;justify-content:space-between"><div><h3 class="s-h" style="margin:0">Try ElevenRank free</h3><p>No tracking, no outside connections.</p></div><a class="s-btn primary" href="{{ config('site.plugin_url') }}">Get the free plugin</a></div>
	</div>
</article>
@if ($related->isNotEmpty())
<section class="s-sec alt" style="padding:48px 0">
	<div class="s-wrap">
		<h2 class="s-d-m">Related posts</h2>
		<div class="s-grid" style="margin-top:20px">
			@foreach ($related as $p)<a class="s-post" href="/blog/{{ $p['slug'] }}" style="text-decoration:none;color:inherit"><div class="img">Cover image</div><h3>{{ $p['title'] }}</h3></a>@endforeach
		</div>
	</div>
</section>
@endif
@endsection
