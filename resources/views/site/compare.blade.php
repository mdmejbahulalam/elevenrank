@extends('layouts.site')
@section('title', 'ElevenRank and Yoast SEO')
@section('description', 'How ElevenRank compares with Yoast SEO, and how to switch step by step.')
@push('head')<meta name="robots" content="noindex">@endpush
@section('content')
@php $todo = 'Not yet checked'; @endphp
<section class="s-sec" style="padding-bottom:32px">
	<div class="s-wrap s-narrow">
		<p class="s-eyebrow">Compare</p>
		<h1 class="s-d-xl" style="font-size:clamp(34px,5vw,44px)">ElevenRank and Yoast SEO</h1>
		<p class="s-lead">A fair look at what each does, and how to switch if you decide to. The Yoast SEO column is filled in only after each cell is checked against its own documentation, and dated.</p>
	</div>
</section>
<section class="s-sec alt" style="padding:48px 0">
	<div class="s-wrap">
		<div class="s-table-wrap">
			<table class="s-table">
				<thead><tr><th scope="col">Area</th><th scope="col" class="c">ElevenRank</th><th scope="col" class="c">Yoast SEO</th></tr></thead>
				<tbody>
					<tr><td>Titles, meta and canonical links</td><td class="c">Included</td><td class="c">{{ $todo }}</td></tr>
					<tr><td>XML sitemaps</td><td class="c">Included</td><td class="c">{{ $todo }}</td></tr>
					<tr><td>Schema (JSON-LD)</td><td class="c">Included</td><td class="c">{{ $todo }}</td></tr>
					<tr><td>Redirects and 404 monitor</td><td class="c">Included, free</td><td class="c">{{ $todo }}</td></tr>
					<tr><td>Outside connections from the free plugin</td><td class="c">None</td><td class="c">{{ $todo }}</td></tr>
					<tr><td>Import from the other plugin</td><td class="c">Yes</td><td class="c">Not applicable</td></tr>
				</tbody>
			</table>
		</div>
		<p class="s-muted s-small" style="margin:12px 0 0">We are not affiliated with Yoast SEO. The name is used only to say what can be imported and compared.</p>
	</div>
</section>
<section class="s-sec" style="padding:56px 0">
	<div class="s-wrap s-narrow">
		<h2 class="s-d-m">Switching, step by step</h2>
		<div class="s-steps" style="margin-top:20px">
			<div class="s-step"><h3 class="s-h">Install ElevenRank</h3><p class="s-muted" style="margin:0">Keep Yoast SEO active for now. While it is active, ElevenRank adds no tags.</p></div>
			<div class="s-step"><h3 class="s-h">Check, then import</h3><p class="s-muted" style="margin:0">Open Import, press Check what would change, then Import now.</p></div>
			<div class="s-step"><h3 class="s-h">Deactivate the old plugin</h3><p class="s-muted" style="margin:0">Its data is left untouched, so you can go back.</p></div>
		</div>
		<div class="s-row" style="margin-top:32px"><a class="s-btn primary" href="{{ config('site.plugin_url') }}">Get the free plugin</a><a class="s-btn secondary" href="/docs#getting-started">Read the docs</a></div>
	</div>
</section>
@endsection
