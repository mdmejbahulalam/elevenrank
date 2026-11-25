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
@endphp
@push('jsonld')
<script type="application/ld+json">{!! json_encode(['@context' => 'https://schema.org', '@type' => 'SoftwareApplication', 'name' => 'ElevenRank', 'applicationCategory' => 'BusinessApplication', 'operatingSystem' => 'WordPress', 'offers' => ['@type' => 'Offer', 'price' => '0', 'priceCurrency' => 'USD']], JSON_UNESCAPED_SLASHES) !!}</script>
@endpush
@section('content')
<section class="hero">
	<div class="wrap">
		<span class="tag">Free · GPL · for WordPress</span>
		<h1 style="margin-top:14px">SEO that stays on your site.</h1>
		<p class="lead">Titles, sitemaps, schema, redirects and a live score in the editor, from one calm dashboard. No tracking and no outside connections.</p>
		<div class="cta">
			<a class="btn" href="{{ config('site.plugin_url') }}">Get the free plugin</a>
			<a class="btn ghost" href="/pro">See Pro</a>
		</div>
	</div>
</section>

<section class="proof" aria-label="At a glance">
	<div class="wrap">
		<div class="grid">
			<div><strong>Private</strong><span>No requests to outside servers from the free plugin.</span></div>
			<div><strong>Light</strong><span>Plain JavaScript analysis in your browser; no bundled services.</span></div>
			<div><strong>Complete</strong><span>Titles, sitemaps, schema, redirects, 404 monitor and importers.</span></div>
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
		<p style="margin-top:20px"><a href="/features">See every feature</a></p>
	</div>
</section>

<section class="block">
	<div class="wrap">
		<h2>Switch in minutes</h2>
		<p class="sub">Bring your titles, descriptions, redirects and settings with you.</p>
		<ul class="chips" aria-label="Supported importers">
			<li>Yoast SEO</li><li>Rank Math</li><li>All in One SEO</li><li>SEOPress</li>
		</ul>
		<p class="muted" style="margin-top:12px">Names are used only to say what can be imported. We are not affiliated with these plugins.</p>
	</div>
</section>

<section class="block">
	<div class="wrap">
		<h2>How it works</h2>
		<ol class="steps">
			<li><strong>Install</strong><span>Add the free plugin from WordPress.org and run the short setup.</span></li>
			<li><strong>Import</strong><span>Run the check, then import from the plugin you use now.</span></li>
			<li><strong>Write</strong><span>Watch the live score in the editor and publish.</span></li>
		</ol>
	</div>
</section>

<section class="block">
	<div class="wrap">
		<h2>Free and Pro</h2>
		<x-table :headings="['', 'Free', 'Pro']">
			<tr><th scope="row">Titles, sitemaps, schema, redirects</th><td>Included</td><td>Included</td></tr>
			<tr><th scope="row">Live editor score</th><td>Included</td><td>Included</td></tr>
			<tr><th scope="row">Site audit and auto-fix queue</th><td>—</td><td>Included</td></tr>
			<tr><th scope="row">Rank tracker, Search Console figures</th><td>—</td><td>Included</td></tr>
			<tr><th scope="row">Updates and support after term</th><td>Free forever</td><td>While licensed</td></tr>
		</x-table>
		<p style="margin-top:16px"><a href="/pricing">See pricing</a></p>
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

<section class="block">
	<div class="wrap narrow">
		<h2>Questions</h2>
		@foreach ($faq as [$q, $a])
			<details><summary>{{ $q }}</summary><p>{{ $a }}</p></details>
		@endforeach
	</div>
</section>

<section class="block">
	<div class="wrap" style="text-align:center">
		<h2>Try it on one site</h2>
		<p class="sub">It is free, and it changes nothing until you say so.</p>
		<a class="btn" href="{{ config('site.plugin_url') }}">Get the free plugin</a>
	</div>
</section>
@endsection
