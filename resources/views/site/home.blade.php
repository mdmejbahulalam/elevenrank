@extends('layouts.site')
@php
$faq = [
	['Does ElevenRank send any data to another server?', 'No. The free plugin makes no requests to outside servers. Your text is analysed in your own browser.'],
	['Will it clash with my current SEO plugin?', 'No. ElevenRank adds no tags while another SEO plugin is active, so nothing is printed twice.'],
	['How do I move from Yoast SEO or Rank Math?', 'Open ElevenRank, then Import. Press Check to see what would change first. Nothing of the other plugin is changed or deleted.'],
	['Do I need Pro?', 'Only if you want the audit, automation and AI tools. Everything on the Features page works without it.'],
	['What happens when my Pro licence ends?', 'Pro keeps working on your sites. Updates and support stop until you renew.'],
	['Does it work on multisite?', 'Yes. It works on single sites and on multisite networks.'],
];
$features = [
	['Titles and meta', 'Templates with variables for posts, pages, archives and the homepage, plus robots and canonical links.', '<path d="M4 6h16M4 12h10M4 18h14"/>'],
	['XML sitemaps', 'Posts, pages, terms and images, split into files and readable in the browser.', '<path d="M12 3v6M5 21v-6h14v6M12 9l-7 6M12 9l7 6"/>'],
	['Structured data', 'One JSON-LD graph per page. Empty or invalid fields are left out, and nothing is made up.', '<path d="M8 4 3 12l5 8M16 4l5 8-5 8"/>'],
	['Redirects and 404s', 'Five match types, CSV import, and a monitor that stores no IP addresses.', '<path d="M5 12h14M13 6l6 6-6 6"/>'],
	['Live editor score', '19 checks in the block and Classic editors, computed in your browser.', '<path d="M4 20V10M10 20V4M16 20v-8M22 20H2"/>'],
	['Easy to switch', 'Import from Yoast SEO, Rank Math, All in One SEO or SEOPress, with a check first that changes nothing.', '<path d="M4 8h13l-3-3M20 16H7l3 3"/>'],
];
@endphp
@push('jsonld')
<script type="application/ld+json">{!! json_encode(['@context' => 'https://schema.org', '@type' => 'SoftwareApplication', 'name' => 'ElevenRank', 'applicationCategory' => 'BusinessApplication', 'operatingSystem' => 'WordPress', 'offers' => ['@type' => 'Offer', 'price' => '0', 'priceCurrency' => 'USD']], JSON_UNESCAPED_SLASHES) !!}</script>
@endpush
@section('content')
<section class="s-hero">
	<div class="s-wrap">
		<div class="cols">
			<div class="rv">
				<p class="s-eyebrow">Free · GPL · for WordPress</p>
				<h1 class="s-d-xl">SEO that stays on your site.</h1>
				<p class="s-lead">Titles, sitemaps, schema, redirects and a live score in the editor, from one calm dashboard. No tracking and no outside connections.</p>
				<div class="s-row">
					<a class="s-btn primary" href="{{ config('site.plugin_url') }}">Get the free plugin</a>
					<a class="s-btn secondary" href="/pro">See ElevenRank Pro</a>
				</div>
				<p class="s-muted s-small" style="margin:16px 0 0">Free on WordPress.org. Works on single sites and multisite.</p>
			</div>
			<div class="s-shot rv d2" aria-hidden="true">
				<div class="bar"><i></i><i></i><i></i><span>yoursite.com/wp-admin</span></div>
				<div class="body">
					<div class="side"><span class="on">Dashboard</span><span>Titles &amp; Meta</span><span>Sitemaps</span><span>Redirects</span><span>404 Monitor</span><span>Import</span></div>
					<div class="main">
						<div class="tiles"><div class="tile">Pages in sitemap<b>&mdash;</b></div><div class="tile">Active redirects<b>&mdash;</b></div><div class="tile">Missing pages, 7 days<b>&mdash;</b></div><div class="tile">Posts scoring good<b>&mdash;</b></div></div>
						<div class="row"><span><b>Site health</b></span><span class="s-pill ok">Tags are on</span></div>
						<div class="row"><span><b>Posts without a description</b></span><span>Fix</span></div>
						<div class="row"><span><b>Latest missing pages</b></span><span>Turn into redirects</span></div>
					</div>
				</div>
				<div class="s-ph">Placeholder wireframe. Replace with a real screenshot of the dashboard.</div>
			</div>
		</div>
	</div>
</section>

<section class="s-trust">
	<div class="s-wrap">
		<div class="s-trio">
			<div class="rv"><h3 class="s-h">Light</h3><p>The admin app loads only on ElevenRank screens, and every module can be switched off.</p></div>
			<div class="rv d1"><h3 class="s-h">Private</h3><p>No tracking, no ads in your dashboard and no connections to outside servers.</p></div>
			<div class="rv d2"><h3 class="s-h">Complete</h3><p>Titles, sitemaps, schema, redirects, breadcrumbs and a 404 monitor, so you need fewer plugins.</p></div>
		</div>
	</div>
</section>

<section class="s-sec">
	<div class="s-wrap">
		<div class="rv">
			<p class="s-eyebrow">What is included</p>
			<h2 class="s-d-l">Everything the basics need, in one plugin</h2>
			<p class="s-lead">So you can retire two or three others.</p>
		</div>
		<div class="s-grid s-grid3">
			@foreach ($features as $n => [$t, $d, $icon])
				<div class="s-card rv d{{ $n % 3 }}"><div class="s-ico"><svg viewBox="0 0 24 24" aria-hidden="true">{!! $icon !!}</svg></div><h3 class="s-h">{{ $t }}</h3><p>{{ $d }}</p></div>
			@endforeach
		</div>
		<p style="margin-top:24px"><a href="/features">See every feature</a></p>
	</div>
</section>

<section class="s-sec alt">
	<div class="s-wrap">
		<div class="s-switch rv">
			<div>
				<p class="s-eyebrow">Switching</p>
				<h2 class="s-d-m">Move over in a few minutes</h2>
				<p class="s-muted">Open ElevenRank, then Import. Each plugin whose data is found is listed with what it holds. Press Check what would change to see the result first. Nothing of the other plugin is changed or deleted.</p>
				<a class="s-btn secondary" href="/compare/yoast-seo">Read the switching guide</a>
			</div>
			<div class="s-chips"><span class="s-pill">Yoast SEO</span><span class="s-pill">Rank Math</span><span class="s-pill">All in One SEO</span><span class="s-pill">SEOPress</span></div>
		</div>
	</div>
</section>

<section class="s-sec">
	<div class="s-wrap">
		<div class="rv">
			<p class="s-eyebrow">Free and Pro</p>
			<h2 class="s-d-l">The free plugin is complete</h2>
			<p class="s-lead">Pro adds audits, automation and AI tools for people who want them. You never need it to run your SEO.</p>
		</div>
		<div class="s-split">
			<div class="s-card rv">
				<h3 class="s-h">ElevenRank</h3>
				<p><span class="s-pill ok">Free, GPL</span></p>
				<ul class="s-ticks"><li>Titles, meta, robots and canonical links</li><li>XML sitemaps, schema and breadcrumbs</li><li>Redirects and a 404 monitor</li><li>Live editor score and importers</li></ul>
				<a class="s-btn secondary" href="/features">See the free features</a>
			</div>
			<div class="s-card feat rv d1">
				<h3 class="s-h">ElevenRank Pro</h3>
				<p><span class="s-pill">Annual licence</span></p>
				<ul class="s-ticks"><li>Site audit and an auto-fix queue you approve</li><li>Rank tracker and Search Console figures</li><li>AI writer that uses your own AI account</li><li>Launch and staging safety</li></ul>
				<p class="s-small" style="margin:0 0 20px">Pro keeps working after your licence ends. Updates and support stop until you renew.</p>
				<a class="s-btn primary" href="/pricing">See pricing</a>
			</div>
		</div>
	</div>
</section>

<section class="s-sec alt">
	<div class="s-wrap">
		<h2 class="s-d-l rv">Up and running in three steps</h2>
		<div class="s-steps">
			<div class="s-step rv"><h3 class="s-h">Install</h3><p class="s-muted">Add ElevenRank from the Plugins screen or WordPress.org and activate it.</p></div>
			<div class="s-step rv d1"><h3 class="s-h">Run the setup wizard</h3><p class="s-muted">It checks your site title, tagline and search engine visibility.</p></div>
			<div class="s-step rv d2"><h3 class="s-h">Import or start fresh</h3><p class="s-muted">Bring your titles and keywords from another plugin, or review the sensible defaults.</p></div>
		</div>
	</div>
</section>

<section class="s-sec">
	<div class="s-wrap">
		<div class="s-card s-privacy rv">
			<p class="s-eyebrow">Private by design</p>
			<h2 class="s-d-m">Your text never leaves your browser</h2>
			<p>The free plugin makes no requests to outside servers. Content analysis runs in your own browser. The only request it makes goes to your own site, to check whether another post uses the same focus keyword.</p>
		</div>
	</div>
</section>

<section class="s-sec alt">
	<div class="s-wrap s-narrow">
		<h2 class="s-d-l rv">Questions</h2>
		<div class="s-faq">
			@foreach ($faq as [$q, $a])
				<details class="rv"><summary>{{ $q }}</summary><p>{{ $a }}</p></details>
			@endforeach
		</div>
	</div>
</section>

<section class="s-sec">
	<div class="s-wrap">
		<div class="s-cta rv">
			<h2>Keep your SEO on your own site</h2>
			<p>Install ElevenRank free, and look at Pro only if you need it.</p>
			<div class="s-row" style="justify-content:center"><a class="s-btn primary" href="{{ config('site.plugin_url') }}">Get the free plugin</a><a class="s-btn ghost" href="/pricing">See pricing</a></div>
		</div>
	</div>
</section>
@endsection
