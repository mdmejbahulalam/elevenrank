@extends('layouts.site')
@section('title', 'Changelog')
@section('description', 'Release notes for ElevenRank and ElevenRank Pro.')
@php
$product = in_array(request('product'), ['free', 'pro'], true) ? request('product') : 'all';
$releases = [
	['product' => 'pro', 'version' => '0.1.0', 'note' => 'first release', 'items' => ['First release of the add-on, with licence activation and updates from this site.']],
	['product' => 'free', 'version' => '1.1.0', 'note' => null, 'items' => ['New dashboard: every feature on a card with a short explanation and an on/off switch.', 'Admin menu grouped under headings and kept in view while you scroll.', 'One corner style for cards, buttons and fields; the Status screen shows the version with the debug report one click away.', 'Developer hooks so add-ons can add screens and settings to the ElevenRank admin.']],
	['product' => 'free', 'version' => '1.0.0', 'note' => null, 'items' => ['Importers for Yoast SEO, Rank Math, All in One SEO and SEOPress, with a check-first mode.', 'Tools, a role editor, a status screen and WP-CLI commands.', 'Structured data: one JSON-LD graph per page.', 'Redirects with CSV import and export, automatic redirects for changed addresses, and a 404 monitor.', 'XML sitemaps, a robots.txt editor, breadcrumbs, and image alt text patterns.', 'SEO sidebar for the block editor and SEO box for the Classic Editor, with previews and a score.', 'Titles and meta from templates, robots directives, canonical links, Open Graph and X card tags.']],
];
@endphp
@section('content')
<div class="wrap page narrow">
	<h1>Changelog</h1>
	<p class="filter" aria-label="Filter by product">Show:
		@foreach (['all' => 'All', 'free' => 'Free plugin', 'pro' => 'Pro'] as $k => $label)
			<a href="/changelog{{ $k === 'all' ? '' : '?product='.$k }}" @if($product === $k) aria-current="true" style="font-weight:700" @endif>{{ $label }}</a>@if(! $loop->last) · @endif
		@endforeach
	</p>
	@foreach ($releases as $r)
		@if ($product === 'all' || $product === $r['product'])
			<section id="{{ $r['product'] }}-{{ str_replace('.', '-', $r['version']) }}">
				<h2>{{ $r['product'] === 'pro' ? 'ElevenRank Pro' : 'ElevenRank (free)' }} {{ $r['version'] }} @if ($r['note'])<x-badge>{{ $r['note'] }}</x-badge>@endif</h2>
				<ul>@foreach ($r['items'] as $i)<li>{{ $i }}</li>@endforeach</ul>
			</section>
		@endif
	@endforeach
</div>
@endsection
