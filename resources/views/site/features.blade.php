@extends('layouts.site')
@section('title', 'Features')
@section('description', 'Everything in the free ElevenRank SEO plugin: titles, sitemaps, schema, redirects, a 404 monitor, breadcrumbs, importers and a live editor score.')
@section('content')
@php
$cards = [
	'Crawling and indexing' => ['XML sitemaps for posts, pages, post types, terms and optionally authors, with images and a readable browser view.', 'A robots.txt editor with a safe default and reset.', 'Breadcrumbs as a block, a shortcode and a template function.'],
	'Redirects and errors' => ['Exact, contains, starts with, ends with and regular-expression rules; codes 301, 302, 307, 410 and 451.', 'An automatic 301 when you change the address of a published post.', 'A 404 monitor that stores no IP addresses or browser details.'],
	'Structured data' => ['One JSON-LD graph per page: organization or person, website, page and breadcrumb.', 'Article, product, recipe, event, video and review types. Nothing is printed for a type that is missing something Google requires.'],
	'Switching and running it' => ['Importers for Yoast SEO, Rank Math, All in One SEO and SEOPress, with a check-first mode.', 'Settings export and import, a role editor and a status screen with a copyable debug text.', 'WP-CLI commands, multisite support, and no tags added while another SEO plugin is active.'],
];
@endphp
<section class="s-sec" style="padding-bottom:24px">
	<div class="s-wrap">
		<p class="s-eyebrow">Features</p>
		<h1 class="s-d-xl" style="font-size:clamp(36px,5vw,44px)">Everything in the free plugin</h1>
		<p class="s-lead">Eleven things SEO plugins usually split across several add-ons, in one place and at no cost.</p>
		<nav class="s-row" aria-label="On this page">
			<a class="s-pill" href="#titles">Titles and sharing</a>
			<a class="s-pill" href="#editor">Editor</a>
			<a class="s-pill" href="#more">Crawling</a>
			<a class="s-pill" href="#more">Redirects</a>
			<a class="s-pill" href="#more">Structured data</a>
			<a class="s-pill" href="#more">Switching</a>
		</nav>
	</div>
</section>

<section class="s-sec" id="titles" style="padding:48px 0">
	<div class="s-wrap">
		<div class="s-split" style="align-items:center">
			<div>
				<h2 class="s-d-m">Titles, meta and sharing</h2>
				<ul class="s-ticks"><li>Titles and descriptions from templates with variables, for posts, pages, custom post types, terms, archives, search and the homepage.</li><li>Robots directives with sensible defaults, and correct canonical links on paginated pages.</li><li>Open Graph and X card tags with a default sharing image.</li><li>Image alt text and title filled from your own patterns when an image has none.</li></ul>
			</div>
			<div class="s-shot" aria-hidden="true"><div class="bar"><i></i><i></i><i></i><span>Titles &amp; Meta</span></div><div class="main"><div class="row">Post title %sep% Site name</div><div class="row">Description template</div></div><div class="s-ph">Placeholder. Replace with a real screenshot.</div></div>
		</div>
	</div>
</section>

<section class="s-sec alt" id="editor" style="padding:48px 0">
	<div class="s-wrap">
		<div class="s-split" style="align-items:center">
			<div class="s-shot" aria-hidden="true"><div class="bar"><i></i><i></i><i></i><span>Block editor</span></div><div class="main"><div class="row">Focus keyword</div><div class="row">Search preview</div><div class="row">19 checks</div></div><div class="s-ph">Placeholder. Replace with a real screenshot.</div></div>
			<div>
				<h2 class="s-d-m">The editor</h2>
				<ul class="s-ticks"><li>An SEO sidebar in the block editor and an SEO box in the Classic Editor.</li><li>Focus keywords, a live search and social preview, and a score from 19 checks. Readability checks are English.</li><li>Score, title and description columns in the posts list, with filters, bulk noindex and Quick Edit fields.</li></ul>
			</div>
		</div>
	</div>
</section>

<section class="s-sec" id="more" style="padding:48px 0">
	<div class="s-wrap">
		<div class="s-grid">
			@foreach ($cards as $title => $items)
				<div class="s-card"><h3 class="s-h">{{ $title }}</h3><ul class="s-ticks">@foreach ($items as $i)<li>{{ $i }}</li>@endforeach</ul></div>
			@endforeach
		</div>
	</div>
</section>

<section class="s-sec">
	<div class="s-wrap">
		<div class="s-card" style="display:flex;flex-wrap:wrap;gap:20px;align-items:center;justify-content:space-between;padding:32px">
			<div><h3 class="s-h">Looking for more?</h3><p>ElevenRank Pro adds an auditing and monitoring layer on top of the free plugin.</p></div>
			<a class="s-btn primary" href="/pro">See what Pro adds</a>
		</div>
	</div>
</section>
@endsection
