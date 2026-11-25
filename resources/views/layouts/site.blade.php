<!doctype html>
<html lang="en">
<head>
	@include('layouts.head')
	<title>@hasSection('title')@yield('title') · ElevenRank @else ElevenRank: free all-in-one SEO plugin for WordPress @endif</title>
	<meta name="description" content="@yield('description', 'ElevenRank is a free all-in-one SEO plugin for WordPress: titles, sitemaps, schema, redirects and a live editor score, with no tracking. ElevenRank Pro adds audits, automation and AI tools.')">
	<link rel="stylesheet" href="/assets/site.css?v={{ @filemtime(public_path('assets/site.css')) }}">
	@stack('head')
</head>
<body>
<a class="skip" href="#main">Skip to content</a>
<header class="site-head">
	<div class="wrap">
		<x-logo />
		<nav class="main" aria-label="Main">
			<a href="/features" @if(request()->is('features')) aria-current="page" @endif>Features</a>
			<a href="/pro" @if(request()->is('pro')) aria-current="page" @endif>Pro</a>
			<a href="/pricing" @if(request()->is('pricing')) aria-current="page" @endif>Pricing</a>
			<a href="/docs" @if(request()->is('docs*')) aria-current="page" @endif>Docs</a>
			<a href="/changelog" @if(request()->is('changelog')) aria-current="page" @endif>Changelog</a>
			<a href="/support" @if(request()->is('support')) aria-current="page" @endif>Support</a>
			@auth
				<a class="btn small" href="/account">My account</a>
			@else
				<a href="/account/login">Sign in</a>
			@endauth
		</nav>
	</div>
</header>
<main id="main">@yield('content')</main>
<footer class="site-foot">
	<div class="wrap">
		<div class="cols">
			<div><h4>ElevenRank</h4><span class="muted">Free all-in-one SEO for WordPress. No tracking, no outside connections.</span></div>
			<div><h4>Product</h4><a href="/features">Features</a><a href="/pro">ElevenRank Pro</a><a href="/pricing">Pricing</a><a href="/changelog">Changelog</a></div>
			<div><h4>Help</h4><a href="/docs">Documentation</a><a href="/docs/hooks">Developer hooks</a><a href="/support">Support</a></div>
			<div><h4>Legal</h4><a href="/terms">Terms</a><a href="/privacy">Privacy</a><a href="/refunds">Refunds</a><a href="/gpl">GPL</a></div>
		</div>
		<p class="muted" style="margin:24px 0 0">© {{ date('Y') }} ElevenRank. WordPress is a trademark of the WordPress Foundation; ElevenRank is not affiliated with it.</p>
	</div>
</footer>
<script src="/assets/app.js" defer></script>
</body>
</html>
