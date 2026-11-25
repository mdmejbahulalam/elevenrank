@extends('layouts.site')
@php
$faq = [
	['Is the free plugin really free?', 'Yes. It is released under the GPL and has no locked features, trial period or upsell screens that block your work.'],
	['Does it send my data anywhere?', 'No. The free plugin makes no requests to outside servers, and your text is analysed in your own browser.'],
	['Can I move over from another SEO plugin?', 'Yes. Importers for Yoast SEO, Rank Math, All in One SEO and SEOPress run a check first that changes nothing.'],
	['Do I need Pro?', 'Only if you want the audit, automation and AI tools. Everything on the Features page works without it.'],
	['What happens if my Pro licence ends?', 'Pro keeps working on your sites. You stop receiving updates and support until you renew.'],
	['Can I get a refund?', 'Yes, within 14 days of purchase. See the refund policy.'],
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
			<div>
				<p class="s-eyebrow">Free · GPL · for WordPress</p>
				<h1 class="s-d-xl">SEO that stays on your site.</h1>
				<p class="s-lead">Titles, sitemaps, schema, redirects and a live score in the editor, from one calm dashboard. No tracking and no outside connections.</p>
				<div class="s-row">
					<a class="s-btn primary" href="{{ config('site.plugin_url') }}">Get the free plugin</a>
					<a class="s-btn secondary" href="/pro">See Pro</a>
				</div>
			</div>
			<div class="s-shot" aria-hidden="true">
				<div class="bar"><i></i><i></i><i></i><span>yoursite.com/wp-admin</span></div>
				<div class="body">
					<div class="side"><span class="on">Dashboard</span><span>Titles</span><span>Sitemaps</span><span>Schema</span><span>Redirects</span></div>
					<div class="main">
						<div class="tiles"><div class="tile">Score<b>92</b></div><div class="tile">Pages<b>148</b></div><div class="tile">404s<b>3</b></div><div class="tile">Redirects<b>21</b></div></div>
						<div class="row"><span>Homepage title</span><span class="s-pill ok">Good</span></div>
						<div class="row"><span>XML sitemap</span><span class="s-pill ok">On</span></div>
						<div class="row"><span>Schema graph</span><span class="s-pill ok">On</span></div>
					</div>
				</div>
				<div class="s-ph">Placeholder wireframe. A real screenshot replaces this.</div>
			</div>
		</div>
	</div>
</section>

<section class="s-sec alt">
	<div class="s-wrap">
		<p class="s-eyebrow">What you get</p>
		<h2 class="s-d-l">Everything the basics need, in one plugin</h2>
		<p class="s-lead">So you can retire two or three others.</p>
		<div class="s-grid">
			@foreach ($features as [$t, $d, $icon])
				<div class="s-card"><div class="s-ico"><svg viewBox="0 0 24 24" aria-hidden="true">{!! $icon !!}</svg></div><h3 class="s-h">{{ $t }}</h3><p>{{ $d }}</p></div>
			@endforeach
		</div>
		<p style="margin-top:24px"><a class="s-btn ghost" href="/features">See every feature</a></p>
	</div>
</section>

<section class="s-sec">
	<div class="s-wrap">
		<p class="s-eyebrow">How it works</p>
		<h2 class="s-d-l">Switch in minutes</h2>
		<p class="s-lead">Bring your titles, descriptions, redirects and settings with you.</p>
		<div class="s-steps">
			<div class="s-step"><h3 class="s-h">Install</h3><p class="s-muted">Add the free plugin from WordPress.org and run the short setup.</p></div>
			<div class="s-step"><h3 class="s-h">Import</h3><p class="s-muted">Run the check, then import from Yoast SEO, Rank Math, All in One SEO or SEOPress.</p></div>
			<div class="s-step"><h3 class="s-h">Write</h3><p class="s-muted">Watch the live score in the editor and publish.</p></div>
		</div>
		<p class="s-muted s-small" style="margin-top:20px">Names are used only to say what can be imported. We are not affiliated with these plugins.</p>
	</div>
</section>

<section class="s-sec alt">
	<div class="s-wrap">
		<div class="s-split">
			<div class="s-card">
				<p class="s-eyebrow">Free</p>
				<h2 class="s-d-m">Private by design</h2>
				<p class="s-muted">The free plugin makes no requests to outside servers. Your text is analysed in your own browser.</p>
				<ul class="s-ticks"><li>Titles, sitemaps, schema, redirects</li><li>Live editor score</li><li>404 monitor and importers</li></ul>
				<a class="s-btn primary" href="{{ config('site.plugin_url') }}">Get the free plugin</a>
			</div>
			<div class="s-card">
				<p class="s-eyebrow">Pro</p>
				<h2 class="s-d-m">Need more? Add Pro.</h2>
				<p class="s-muted">The free plugin stays fully usable without it.</p>
				<ul class="s-ticks"><li>Site audit and auto-fix queue</li><li>Rank tracker, Search Console figures</li><li>Updates and support while licensed</li></ul>
				<a class="s-btn secondary" href="/pricing">See pricing</a>
			</div>
		</div>
	</div>
</section>

<section class="s-sec">
	<div class="s-wrap s-narrow">
		<p class="s-eyebrow">Questions</p>
		<h2 class="s-d-l">Good to know</h2>
		<div class="s-faq">
			@foreach ($faq as [$q, $a])
				<details><summary>{{ $q }}</summary><p>{{ $a }}</p></details>
			@endforeach
		</div>
	</div>
</section>

<section class="s-sec" style="padding-top:0">
	<div class="s-wrap">
		<div class="s-cta">
			<h2>Try it on one site</h2>
			<p>It is free, and it changes nothing until you say so.</p>
			<a class="s-btn primary" href="{{ config('site.plugin_url') }}">Get the free plugin</a>
		</div>
	</div>
</section>
@endsection
