@extends('layouts.site')
@section('content')
<section class="hero">
	<div class="wrap">
		<span class="tag">Free · GPL · for WordPress</span>
		<h1 style="margin-top:14px">SEO that stays on your site.</h1>
		<p class="lead">Titles, sitemaps, schema, redirects and a live score in the editor, from one calm dashboard. No tracking and no outside connections.</p>
		<div class="cta">
			<a class="btn" href="/features">See what is included</a>
			<a class="btn ghost" href="/pro">ElevenRank Pro</a>
		</div>
	</div>
</section>

<section class="block">
	<div class="wrap">
		<h2>Everything the basics need, in one plugin</h2>
		<p class="sub">So you can retire two or three others.</p>
		<div class="grid">
			<div class="card"><h3>Titles and meta</h3><p>Templates with variables for posts, pages, archives and the homepage, plus robots and canonical links.</p></div>
			<div class="card"><h3>XML sitemaps</h3><p>Posts, pages, terms and images, split into files and readable in the browser.</p></div>
			<div class="card"><h3>Structured data</h3><p>One JSON-LD graph per page. Empty or invalid fields are left out, and nothing is made up.</p></div>
			<div class="card"><h3>Redirects and 404s</h3><p>Five match types, CSV import, and a monitor that stores no IP addresses.</p></div>
			<div class="card"><h3>Live editor score</h3><p>19 checks in the block and Classic editors, computed in your browser.</p></div>
			<div class="card"><h3>Easy to switch</h3><p>Import from Yoast SEO, Rank Math, All in One SEO or SEOPress, with a check first that changes nothing.</p></div>
		</div>
	</div>
</section>

<section class="block">
	<div class="wrap">
		<h2>Private by design</h2>
		<p class="sub">The free plugin makes no requests to outside servers. Your text is analysed in your own browser.</p>
		<div class="card" style="display:flex;gap:20px;flex-wrap:wrap;align-items:center;justify-content:space-between">
			<div><h3>Need more? There is a Pro add-on.</h3><p>Site audit, auto-fix queue, rank tracker, Search Console figures and more. The free plugin stays fully usable without it.</p></div>
			<a class="btn" href="/pricing">See pricing</a>
		</div>
	</div>
</section>
@endsection
