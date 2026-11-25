@extends('layouts.site')
@section('title', 'ElevenRank Pro')
@section('description', 'ElevenRank Pro adds a site audit, auto-fix queue, rank tracker, Search Console figures, an AI writer that uses your own account, and more to the free plugin.')
@section('content')
<div class="wrap page">
	<span class="tag">Add-on</span>
	<h1 style="margin-top:12px">ElevenRank Pro</h1>
	<p class="sub">A paid add-on for the free plugin, which must be installed and active. Pro keeps working after your licence ends; only updates and support stop.</p>
	<div class="grid" style="margin-top:24px">
		@foreach ([
			['Indexability debugger', 'See why a page is or is not indexable.'],
			['Cannibalization map', 'Find pages competing for the same keyword.'],
			['Site audit and auto-fix queue', 'Find issues and apply fixes you approve.'],
			['Smarter redirects', 'Extra redirect tools on top of the free module.'],
			['More schema types', 'Structured data beyond the free set.'],
			['AI writer', 'Uses your own AI account; nothing is sent through ElevenRank.'],
			['Internal link suggestions', 'Suggestions based on your own content.'],
			['Search Console figures', 'Clicks and impressions next to your posts.'],
			['AI crawler logging and blocking', 'See and control AI crawlers.'],
			['Launch and staging safety', 'Guards against indexing a staging site.'],
			['SEO change history', 'A record of SEO changes with the ability to review them.'],
			['Spreadsheet import and export', 'Bulk edit in a spreadsheet, with undo.'],
			['llms.txt', 'Generate an llms.txt for your site.'],
			['Rank tracker', 'Track positions for your keywords.'],
			['AI agent access', 'Let your AI tools work with your SEO data.'],
		] as [$t, $d])
			<div class="card"><h3>{{ $t }}</h3><p>{{ $d }}</p></div>
		@endforeach
	</div>
	<p class="muted" style="margin-top:20px">Pro is at version 0.1.0 (first release). Individual features are being completed and each release is listed in the <a href="/changelog">changelog</a>.</p>
	<p><a class="btn" href="/pricing">See pricing</a></p>
</div>
@endsection
