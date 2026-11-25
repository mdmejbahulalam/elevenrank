@extends('layouts.site')
@section('title', 'Changelog')
@section('description', 'Release notes for ElevenRank and ElevenRank Pro.')
@section('content')
<div class="wrap page narrow">
	<h1>Changelog</h1>
	<h2>ElevenRank Pro</h2>
	<h3>0.1.0 <span class="tag">first release</span></h3>
	<ul><li>First release of the add-on, with licence activation and updates from this site.</li></ul>

	<h2>ElevenRank (free)</h2>
	<h3>1.1.0</h3>
	<ul>
		<li>New dashboard: every feature on a card with a short explanation and an on/off switch.</li>
		<li>Admin menu grouped under headings and kept in view while you scroll.</li>
		<li>One corner style for cards, buttons and fields; the Status screen shows the version with the debug report one click away.</li>
		<li>Developer hooks so add-ons can add screens and settings to the ElevenRank admin.</li>
	</ul>
	<h3>1.0.0</h3>
	<ul>
		<li>Importers for Yoast SEO, Rank Math, All in One SEO and SEOPress, with a check-first mode.</li>
		<li>Tools, a role editor, a status screen and WP-CLI commands.</li>
		<li>Structured data: one JSON-LD graph per page.</li>
		<li>Redirects with CSV import and export, automatic redirects for changed addresses, and a 404 monitor.</li>
		<li>XML sitemaps, a robots.txt editor, breadcrumbs, and image alt text patterns.</li>
		<li>SEO sidebar for the block editor and SEO box for the Classic Editor, with previews and a score.</li>
		<li>Titles and meta from templates, robots directives, canonical links, Open Graph and X card tags.</li>
	</ul>
</div>
@endsection
