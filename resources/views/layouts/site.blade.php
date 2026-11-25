@php
$pageTitle = trim($__env->yieldContent('title')) ? trim($__env->yieldContent('title')).' · ElevenRank' : 'ElevenRank: free all-in-one SEO plugin for WordPress';
$pageDesc = trim($__env->yieldContent('description')) ?: 'ElevenRank is a free all-in-one SEO plugin for WordPress: titles, sitemaps, schema, redirects and a live editor score, with no tracking. ElevenRank Pro adds audits, automation and AI tools.';
$canonical = url()->current();
$org = ['@type' => 'Organization', '@id' => url('/').'#org', 'name' => 'ElevenRank', 'url' => url('/'), 'logo' => url('/assets/mark.svg')];
$site = ['@type' => 'WebSite', '@id' => url('/').'#site', 'url' => url('/'), 'name' => 'ElevenRank', 'publisher' => ['@id' => url('/').'#org']];
$graph = ['@context' => 'https://schema.org', '@graph' => [$org, $site]];
$nav = [['/features', 'Features', 'features'], ['/pro', 'Pro', 'pro'], ['/pricing', 'Pricing', 'pricing'], ['/docs', 'Docs', 'docs*'], ['/changelog', 'Changelog', 'changelog'], ['/support', 'Support', 'support']];
@endphp
<!doctype html>
<html lang="en">
<head>
	@include('layouts.head')
	<title>{{ $pageTitle }}</title>
	<meta name="description" content="{{ $pageDesc }}">
	<link rel="canonical" href="{{ $canonical }}">
	<meta property="og:type" content="website">
	<meta property="og:site_name" content="ElevenRank">
	<meta property="og:title" content="{{ $pageTitle }}">
	<meta property="og:description" content="{{ $pageDesc }}">
	<meta property="og:url" content="{{ $canonical }}">
	<meta name="twitter:card" content="summary">
	<meta name="twitter:title" content="{{ $pageTitle }}">
	<meta name="twitter:description" content="{{ $pageDesc }}">
	<script type="application/ld+json">{!! json_encode($graph, JSON_UNESCAPED_SLASHES | JSON_UNESCAPED_UNICODE) !!}</script>
	@stack('jsonld')
	<link rel="stylesheet" href="/assets/site.css?v={{ @filemtime(public_path('assets/site.css')) }}">
	@stack('head')
</head>
<body class="s-page">
<a class="skip" href="#main">Skip to content</a>
<header class="s-head">
	<div class="s-wrap">
		<a class="s-brand" href="/" aria-label="ElevenRank home"><img src="/assets/mark.svg" alt="" width="32" height="32"><span>Eleven<b>Rank</b></span></a>
		<nav class="s-nav" aria-label="Main">
			@foreach ($nav as [$href, $label, $match])
				<a class="l @if(request()->is($match)) on @endif" href="{{ $href }}" @if(request()->is($match)) aria-current="page" @endif>{{ $label }}</a>
			@endforeach
			@auth
				<a class="l" href="/account">My account</a>
			@else
				<a class="l" href="/account/login">Sign in</a>
			@endauth
			<a class="s-btn primary" href="{{ config('site.plugin_url') }}">Get the free plugin</a>
		</nav>
		<details class="s-menu">
			<summary aria-label="Menu"><svg width="20" height="20" viewBox="0 0 20 20" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" aria-hidden="true"><path d="M3 6h14M3 10h14M3 14h14"/></svg></summary>
			<div class="panel">
				@foreach ($nav as [$href, $label])<a class="l" href="{{ $href }}">{{ $label }}</a>@endforeach
				@auth<a class="l" href="/account">My account</a>@else<a class="l" href="/account/login">Sign in</a>@endauth
				<p style="margin:16px 0 0"><a class="s-btn primary block" href="{{ config('site.plugin_url') }}">Get the free plugin</a></p>
			</div>
		</details>
	</div>
</header>
<main id="main">@yield('content')</main>
<footer class="s-foot">
	<div class="s-wrap">
		<div class="cols">
			<div>
				<a class="s-brand" href="/"><img src="/assets/mark.svg" alt="" width="32" height="32"><span>Eleven<b>Rank</b></span></a>
				<p class="s-muted s-small" style="margin:12px 0 0;max-width:280px">Free all-in-one SEO for WordPress. No tracking, no outside connections.</p>
			</div>
			<div><h4>Product</h4><a href="/features">Features</a><a href="/pro">ElevenRank Pro</a><a href="/pricing">Pricing</a><a href="/changelog">Changelog</a><a href="/compare/yoast-seo">Switching from Yoast</a></div>
			<div><h4>Help</h4><a href="/docs">Documentation</a><a href="/docs/hooks">Developer hooks</a><a href="/support">Support</a></div>
			<div><h4>Company</h4><a href="/about">About</a><a href="/blog">Blog</a><a href="/account/login">Sign in</a></div>
			<div><h4>Legal</h4><a href="/terms">Terms</a><a href="/privacy">Privacy</a><a href="/refunds">Refunds</a><a href="/gpl">GPL</a></div>
		</div>
		<p class="legal">© {{ date('Y') }} ElevenRank. WordPress is a trademark of the WordPress Foundation; ElevenRank is not affiliated with it.</p>
	</div>
</footer>
<script>document.documentElement.classList.add("js");</script>
<script src="/assets/app.js" defer></script>
</body>
</html>
