@extends('layouts.site')
@section('title', 'Pricing')
@section('description', 'ElevenRank Pro annual licences for 1, 3 or 10 sites. The free plugin stays free.')
@section('content')
<section class="s-sec" style="padding-bottom:48px">
	<div class="s-wrap">
		<p class="s-eyebrow">Pricing</p>
		<h1 class="s-d-xl" style="font-size:clamp(36px,5vw,44px)">The plugin is free. Pro is an annual licence.</h1>
		<p class="s-lead">Pro keeps working if your licence ends. You only stop receiving updates and support until you renew. 14-day refund.</p>
		<div class="s-plans">
			@foreach ($plans as $plan)
				@php $feat = $loop->count > 2 ? $loop->index === 1 : $loop->last; @endphp
				<div class="s-plan @if($feat) feat @endif">
					<h2 class="s-h">{{ $plan->name }}</h2><span class="s-muted s-small">{{ $plan->sitesLabel() }}</span>
					<div class="amount">{{ $plan->price() }} <small>/ year</small></div>
					<ul class="s-ticks"><li>All Pro features</li><li>Updates and support for {{ $plan->months }} months</li><li>Move the licence to another site any time</li></ul>
					<a class="s-btn {{ $feat ? 'primary' : 'secondary' }} block" href="/checkout/{{ $plan->slug }}">Get {{ $plan->name }}</a>
				</div>
			@endforeach
		</div>
	</div>
</section>

<section class="s-sec alt" style="padding:56px 0">
	<div class="s-wrap">
		<div class="s-grid">
			<div><h2 class="s-h">GPL, no lock-in</h2><p class="s-muted" style="margin:0">Pro is GPL like WordPress. The key unlocks updates, downloads and support.</p></div>
			<div><h2 class="s-h">14-day refund</h2><p class="s-muted" style="margin:0">Ask within 14 days of any payment, including renewals, and the order is refunded. See the <a href="/refunds">refund policy</a>.</p></div>
			<div><h2 class="s-h">Keeps working</h2><p class="s-muted" style="margin:0">When a term ends, Pro keeps running on your sites. Only updates and support stop.</p></div>
		</div>
	</div>
</section>

<section class="s-sec">
	<div class="s-wrap">
		<h2 class="s-d-l">Free and Pro, side by side</h2>
		<div class="s-table-wrap" style="margin-top:24px">
			<table class="s-table">
				<thead><tr><th scope="col">Feature</th><th scope="col" class="c">Free</th><th scope="col" class="c">Pro</th></tr></thead>
				<tbody>
					<tr><td>Titles, meta, robots and canonical links</td><td class="c">Included</td><td class="c">Included</td></tr>
					<tr><td>XML sitemaps, schema, breadcrumbs</td><td class="c">Included</td><td class="c">Included</td></tr>
					<tr><td>Redirects and 404 monitor</td><td class="c">Included</td><td class="c">Included, plus smarter redirects</td></tr>
					<tr><td>Live editor score and importers</td><td class="c">Included</td><td class="c">Included</td></tr>
					<tr><td>Site audit and auto-fix queue</td><td class="c">Not included</td><td class="c">Included</td></tr>
					<tr><td>Rank tracker and Search Console figures</td><td class="c">Not included</td><td class="c">Included</td></tr>
					<tr><td>AI writer (your own AI account)</td><td class="c">Not included</td><td class="c">Included</td></tr>
					<tr><td>Launch and staging safety</td><td class="c">Not included</td><td class="c">Included</td></tr>
					<tr><td>Updates and support</td><td class="c">Community</td><td class="c">For the licence term</td></tr>
				</tbody>
			</table>
		</div>
	</div>
</section>

<section class="s-sec alt">
	<div class="s-wrap s-narrow s-faq">
		<h2 class="s-d-l">Questions</h2>
		<details><summary>Do I need the free plugin?</summary><p>Yes. Pro is an add-on and needs the free ElevenRank plugin installed and active.</p></details>
		<details><summary>What happens when my licence ends?</summary><p>Pro keeps working on your sites. You stop receiving updates and support until you renew.</p></details>
		<details><summary>Can I move a licence to another site?</summary><p>Yes. Deactivate the site in your account or from the plugin, then activate the new one.</p></details>
		<details><summary>How does the refund work?</summary><p>Ask within 14 days of purchase and the order is refunded in full. The key is then switched off. See the <a href="/refunds">refund policy</a>.</p></details>
	</div>
</section>
@endsection
