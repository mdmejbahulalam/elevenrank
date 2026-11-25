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
<section class="s-sec" style="padding-bottom:24px">
	<div class="s-wrap s-narrow">
		<p class="s-eyebrow">Changelog</p>
		<h1 class="s-d-xl" style="font-size:clamp(34px,5vw,44px)">What changed, release by release</h1>
		<div class="s-row" style="margin-top:16px" aria-label="Filter by product">
			<a class="s-pill @if($product==='all') on @endif" href="/changelog" @if($product==='all') aria-current="true" @endif>All</a>
			<a class="s-pill ok" href="/changelog?product=free" @if($product==='free') aria-current="true" style="outline:2px solid var(--ok)" @endif>Free plugin</a>
			<a class="s-pill info" href="/changelog?product=pro" @if($product==='pro') aria-current="true" style="outline:2px solid var(--info)" @endif>Pro</a>
		</div>
	</div>
</section>
<section class="s-sec" style="padding-top:24px">
	<div class="s-wrap s-narrow">
		<div class="s-rel">
			@foreach ($releases as $r)
				@if ($product === 'all' || $product === $r['product'])
					<div id="{{ $r['product'] }}-{{ str_replace('.', '-', $r['version']) }}">
						<h2 class="s-h" style="margin:0">{{ $r['version'] }} <span class="s-pill {{ $r['product'] === 'pro' ? 'info' : 'ok' }}" style="margin-left:8px">{{ $r['product'] === 'pro' ? 'Pro' : 'Free plugin' }}</span></h2>
						<p class="s-meta" style="margin:4px 0 0">[Release date]</p>
						<ul style="margin-top:12px">@foreach ($r['items'] as $i)<li>{{ $i }}</li>@endforeach</ul>
					</div>
				@endif
			@endforeach
		</div>
	</div>
</section>
@endsection
