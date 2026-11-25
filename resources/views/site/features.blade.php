@extends('site.layout')
@section('title', 'Features')
@section('description', 'Everything in the free ElevenRank SEO plugin: titles, sitemaps, schema, redirects, a 404 monitor, breadcrumbs, importers and a live editor score.')
@section('content')
<div class="wrap page">
	<h1>Features of the free plugin</h1>
	<p class="sub">Everything below is included in ElevenRank for WordPress at no cost.</p>
	@php
	$groups = [
		'Titles, meta and sharing' => ['Titles and meta descriptions from templates with variables, for posts, pages, custom post types, terms, archives, search and the homepage.', 'Robots directives with sensible defaults, and correct canonical links on paginated pages.', 'Open Graph and X (Twitter) card tags with a default sharing image.', 'Image alt text and title filled from your own patterns when an image has none.'],
		'Editor' => ['An SEO sidebar in the block editor and an SEO box in the Classic Editor.', 'Focus keywords, a live search and social preview, and a score from 19 checks (readability checks are English).', 'SEO title, description and score columns in the posts list, with filters, bulk noindex and Quick Edit fields.'],
		'Crawling and indexing' => ['XML sitemaps for posts, pages, custom post types, terms and optionally authors, with images and a readable browser view.', 'A robots.txt editor with a safe default and reset.', 'Breadcrumbs as a block, a shortcode and a template function.'],
		'Redirects and errors' => ['Exact, contains, starts-with, ends-with and regular-expression rules; codes 301, 302, 307, 410 and 451; loop checks; CSV import and export.', 'An automatic 301 when you change the address of a published post.', 'A 404 monitor that stores no IP addresses or browser details.'],
		'Structured data' => ['One JSON-LD graph per page: organization or person, website, page and breadcrumb.', 'Article, product, recipe, event, video and review types for posts. Nothing is printed for a type that is missing something Google requires.'],
		'Switching and running it' => ['Importers for Yoast SEO, Rank Math, All in One SEO and SEOPress, with a check-first mode.', 'Settings export and import, reset by group, a role editor and a status screen with a copyable debug text.', 'WP-CLI commands, multisite support, and no tags added while another SEO plugin is active.'],
	];
	@endphp
	@foreach ($groups as $title => $items)
		<h2>{{ $title }}</h2>
		<ul class="ticks">@foreach ($items as $i)<li>{{ $i }}</li>@endforeach</ul>
	@endforeach
	<div class="card" style="margin-top:32px"><h3>Looking for more?</h3><p>ElevenRank Pro adds an auditing and monitoring layer on top. <a href="/pro">See what it adds</a>.</p></div>
</div>
@endsection
