@extends('layouts.site')
@section('title', 'ElevenRank Pro')
@section('description', 'ElevenRank Pro adds a site audit, auto-fix queue, rank tracker, Search Console figures, an AI writer that uses your own account, and more to the free plugin.')
@php
$groups = [
	'On-page' => [['AI writer', 'Uses your own AI account. Nothing is sent through ElevenRank.'], ['Internal link suggestions', 'Suggestions based on your own content.'], ['More schema types', 'Structured data beyond the free set.'], ['Spreadsheet import and export', 'Bulk edit in a spreadsheet, with undo.']],
	'Crawl control' => [['Indexability debugger', 'See why a page is or is not indexable.'], ['Smarter redirects', 'Extra redirect tools on top of the free module.'], ['AI crawler logging and blocking', 'See and control AI crawlers.'], ['llms.txt', 'Generate an llms.txt for your site.']],
	'Analysis' => [['Site audit and auto-fix queue', 'Find issues and apply fixes you approve.'], ['Cannibalization map', 'Find pages competing for the same keyword.'], ['Search Console figures', 'Clicks and impressions next to your posts.'], ['Rank tracker', 'Track positions for your keywords.']],
	'Launch safety' => [['Launch and staging safety', 'Guards against indexing a staging site.'], ['SEO change history', 'A record of SEO changes you can review.'], ['AI agent access', 'Let your AI tools work with your SEO data.']],
];
@endphp
@section('content')
<section class="s-hero" style="padding-bottom:40px">
	<div class="s-wrap">
		<span class="s-pill">Add-on for the free plugin</span>
		<h1 class="s-d-xl" style="margin-top:16px;font-size:clamp(36px,5vw,52px)">ElevenRank Pro</h1>
		<p class="s-lead">Audits, automation and AI tools on top of the free plugin. Pro keeps working after your licence ends; only updates and support stop.</p>
		<div class="s-row"><a class="s-btn primary" href="/pricing">See pricing</a><a class="s-btn secondary" href="/docs">Read the Pro docs</a></div>
	</div>
</section>

@foreach ($groups as $name => $features)
<section class="s-sec @if($loop->index % 2 === 0) alt @endif" style="padding:56px 0">
	<div class="s-wrap">
		<h2 class="s-d-m">{{ $name }}</h2>
		<div class="s-grid" style="margin-top:20px">
			@foreach ($features as [$t, $d])
				<div class="s-card"><h3 class="s-h">{{ $t }}</h3><p>{{ $d }}</p></div>
			@endforeach
		</div>
	</div>
</section>
@endforeach

<section class="s-sec alt" style="padding:48px 0">
	<div class="s-wrap s-narrow">
		<div class="s-callout">
			<h3 class="s-h">Where Pro stands today</h3>
			<p>Version {{ config('site.pro_version') }}. {{ config('site.pro_status') }} See the <a href="/changelog?product=pro">Pro changelog</a>.</p>
		</div>
	</div>
</section>

<section class="s-sec">
	<div class="s-wrap">
		<div class="s-cta"><h2>Get Pro when you need it</h2><p>Annual licences for 1, 3 or 10 sites. 14-day refund.</p><a class="s-btn primary" href="/pricing">See pricing</a></div>
	</div>
</section>
@endsection
