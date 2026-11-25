@extends('layouts.site')
@section('title', 'ElevenRank Pro')
@section('description', 'ElevenRank Pro adds a site audit, auto-fix queue, rank tracker, Search Console figures, an AI writer that uses your own account, and more to the free plugin.')
@php
$groups = [
	'On-page' => [['More schema types', 'Structured data beyond the free set.'], ['AI writer', 'Uses your own AI account; nothing is sent through ElevenRank.'], ['Internal link suggestions', 'Suggestions based on your own content.'], ['Spreadsheet import and export', 'Bulk edit in a spreadsheet, with undo.']],
	'Crawl control' => [['Indexability debugger', 'See why a page is or is not indexable.'], ['Smarter redirects', 'Extra redirect tools on top of the free module.'], ['AI crawler logging and blocking', 'See and control AI crawlers.'], ['llms.txt', 'Generate an llms.txt for your site.']],
	'Analysis' => [['Site audit and auto-fix queue', 'Find issues and apply fixes you approve.'], ['Cannibalization map', 'Find pages competing for the same keyword.'], ['Search Console figures', 'Clicks and impressions next to your posts.'], ['Rank tracker', 'Track positions for your keywords.']],
	'Launch safety' => [['Launch and staging safety', 'Guards against indexing a staging site.'], ['SEO change history', 'A record of SEO changes with the ability to review them.'], ['AI agent access', 'Let your AI tools work with your SEO data.']],
];
@endphp
@section('content')
<div class="wrap page">
	<span class="tag">Add-on</span>
	<h1 style="margin-top:12px">ElevenRank Pro</h1>
	<p class="sub">A paid add-on for the free plugin, which must be installed and active. Pro keeps working after your licence ends; only updates and support stop.</p>
	<p style="margin-top:16px"><a class="btn" href="/pricing">See pricing</a></p>
	@foreach ($groups as $name => $features)
		<h2 style="margin-top:40px">{{ $name }}</h2>
		<div class="grid">
			@foreach ($features as [$t, $d])
				<x-card><h3>{{ $t }}</h3><p>{{ $d }}</p></x-card>
			@endforeach
		</div>
	@endforeach
	<x-alert tone="info" title="What Pro is not (yet)" style="margin-top:40px">Version {{ config('site.pro_version') }}. {{ config('site.pro_status') }} See the <a href="/changelog?product=pro">Pro changelog</a>.</x-alert>
	<h2>How the licence works</h2>
	<ul class="ticks">
		<li>One key covers the number of sites in your plan.</li>
		<li>Pro keeps working if the licence ends; you stop receiving updates and support.</li>
		<li>You can move a key to another site from your account.</li>
	</ul>
	<p><a class="btn" href="/pricing">See pricing</a></p>
</div>
@endsection
