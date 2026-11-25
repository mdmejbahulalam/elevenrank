<!doctype html>
<html lang="en">
<head>
	<meta charset="utf-8">
	<meta name="viewport" content="width=device-width, initial-scale=1">
	<title>@hasSection('title')@yield('title') · ElevenRank @else ElevenRank: free all-in-one SEO plugin for WordPress @endif</title>
	<meta name="description" content="@yield('description', 'ElevenRank is a free all-in-one SEO plugin for WordPress: titles, sitemaps, schema, redirects and a live editor score, with no tracking. ElevenRank Pro adds audits, rank tracking and more.')">
	<style>
		:root { --bg:#f6f7fb; --card:#fff; --ink:#14142b; --muted:#5f6178; --line:#e3e5ee; --accent:#4338ca; --accent-ink:#fff; --bad:#b42318; --ok:#067647; --soft:#eef0ff; }
		@media (prefers-color-scheme: dark) { :root { --bg:#0f1020; --card:#181a30; --ink:#eceefb; --muted:#9a9cb8; --line:#2a2d4a; --accent:#8b83ff; --accent-ink:#0f1020; --bad:#ff8a80; --ok:#6ce9a6; --soft:#1f2142; } }
		* { box-sizing:border-box; }
		html { scroll-behavior:smooth; }
		body { margin:0; background:var(--bg); color:var(--ink); font:16px/1.6 system-ui,-apple-system,"Segoe UI",sans-serif; }
		a { color:var(--accent); } h1,h2,h3 { line-height:1.2; }
		.wrap { max-width:1040px; margin:0 auto; padding:0 20px; }
		.narrow { max-width:720px; }
		header.top { background:var(--card); border-bottom:1px solid var(--line); position:sticky; top:0; z-index:5; }
		header.top .wrap { display:flex; align-items:center; justify-content:space-between; gap:16px; padding-top:12px; padding-bottom:12px; flex-wrap:wrap; }
		.brand { font-weight:800; font-size:1.15rem; color:var(--ink); text-decoration:none; letter-spacing:-.01em; }
		.brand span { color:var(--accent); }
		nav.main { display:flex; gap:18px; align-items:center; flex-wrap:wrap; }
		nav.main a { color:var(--ink); text-decoration:none; font-weight:500; font-size:.95rem; }
		nav.main a:hover, nav.main a[aria-current] { color:var(--accent); }
		.btn, button.btn { display:inline-block; padding:11px 20px; border:0; border-radius:12px; background:var(--accent); color:var(--accent-ink); font:inherit; font-weight:600; text-decoration:none; cursor:pointer; }
		.btn.ghost { background:transparent; color:var(--accent); border:1px solid var(--line); }
		.btn.small { padding:7px 14px; font-size:.9rem; }
		button.link { background:none; border:0; padding:0; color:var(--muted); font:inherit; text-decoration:underline; cursor:pointer; }
		.hero { padding:72px 0 56px; text-align:center; }
		.hero h1 { font-size:clamp(2rem,5vw,3.2rem); margin:0 0 16px; letter-spacing:-.02em; }
		.hero p.lead { font-size:1.2rem; color:var(--muted); max-width:640px; margin:0 auto 28px; }
		.hero .cta { display:flex; gap:12px; justify-content:center; flex-wrap:wrap; }
		section.block { padding:48px 0; }
		section.block h2 { font-size:1.8rem; margin:0 0 8px; letter-spacing:-.01em; }
		section.block .sub { color:var(--muted); margin:0 0 28px; }
		.grid { display:grid; gap:16px; grid-template-columns:repeat(auto-fit,minmax(240px,1fr)); }
		.card { background:var(--card); border:1px solid var(--line); border-radius:12px; padding:20px; }
		.card h3 { margin:0 0 6px; font-size:1.05rem; } .card p { margin:0; color:var(--muted); }
		.price { font-size:2.2rem; font-weight:800; letter-spacing:-.02em; } .price small { font-size:.9rem; font-weight:500; color:var(--muted); }
		ul.ticks { list-style:none; padding:0; margin:14px 0 20px; } ul.ticks li { padding-left:26px; position:relative; margin:6px 0; }
		ul.ticks li::before { content:"✓"; position:absolute; left:0; color:var(--ok); font-weight:700; }
		.tag { display:inline-block; padding:2px 10px; border-radius:99px; background:var(--soft); color:var(--accent); font-size:.8rem; font-weight:700; }
		.page { padding:48px 0 64px; } .page h1 { font-size:2.1rem; margin:0 0 8px; } .page h2 { margin-top:32px; } .page .sub { color:var(--muted); }
		.page table { width:100%; border-collapse:collapse; font-size:.92rem; } .page th,.page td { text-align:left; padding:8px 8px; border-bottom:1px solid var(--line); vertical-align:top; }
		code { background:var(--soft); padding:2px 6px; border-radius:6px; font-size:.9em; }
		pre { background:var(--card); border:1px solid var(--line); border-radius:12px; padding:14px; overflow-x:auto; }
		details { background:var(--card); border:1px solid var(--line); border-radius:12px; padding:12px 16px; margin:10px 0; } summary { cursor:pointer; font-weight:600; }
		label { display:block; font-weight:600; margin:14px 0 6px; }
		input[type=text],input[type=email],input[type=password],input[type=number],textarea,select { width:100%; padding:10px 12px; border:1px solid var(--line); border-radius:12px; background:var(--bg); color:var(--ink); font:inherit; }
		.err { color:var(--bad); margin:6px 0 0; } .ok { color:var(--ok); }
		.flash { background:var(--soft); border:1px solid var(--line); border-radius:12px; padding:10px 14px; margin-bottom:16px; }
		.muted { color:var(--muted); }
		footer.foot { border-top:1px solid var(--line); background:var(--card); padding:32px 0; margin-top:32px; font-size:.92rem; }
		footer.foot .cols { display:grid; gap:20px; grid-template-columns:repeat(auto-fit,minmax(160px,1fr)); }
		footer.foot h4 { margin:0 0 8px; font-size:.85rem; text-transform:uppercase; letter-spacing:.05em; color:var(--muted); }
		footer.foot a { display:block; color:var(--ink); text-decoration:none; padding:2px 0; } footer.foot a:hover { color:var(--accent); }
		@media (max-width:640px) { .hero { padding:48px 0 32px; } }
	</style>
	@stack('head')
</head>
<body>
<header class="top">
	<div class="wrap">
		<a class="brand" href="/">Eleven<span>Rank</span></a>
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
<main>@yield('content')</main>
<footer class="foot">
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
</body>
</html>
