@extends('layouts.site')
@section('title', 'Page not found')
@section('content')
<section class="s-404">
	<div class="s-wrap s-narrow">
		<div class="big" aria-hidden="true">404</div>
		<h1 class="s-d-m" style="margin-top:16px">That page is missing</h1>
		<p class="s-muted">The address may have changed. Try the docs search, or go back home.</p>
		<form class="s-search" role="search" action="/docs" method="get"><svg width="18" height="18" viewBox="0 0 20 20" fill="none" stroke="currentColor" stroke-width="1.75" stroke-linecap="round" aria-hidden="true"><circle cx="9" cy="9" r="5.5"/><path d="M13.5 13.5L17 17"/></svg><input type="search" name="q" placeholder="Search the docs" aria-label="Search the docs"></form>
		<a class="s-btn primary" href="/">Back to the home page</a>
	</div>
</section>
@endsection
