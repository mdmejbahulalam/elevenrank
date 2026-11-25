@extends('layouts.site')
@section('title', 'Pricing')
@section('description', 'ElevenRank Pro annual licences for 1, 3 or 10 sites. The free plugin stays free.')
@section('content')
<div class="wrap page">
	<h1>Pricing</h1>
	<p class="sub">The free plugin costs nothing. ElevenRank Pro is an annual licence. If it lapses Pro keeps working; you only stop receiving updates and support. 14-day refund.</p>
	<div class="grid" style="margin-top:24px">
		@foreach ($plans as $plan)
			<div class="card">
				<h3>{{ $plan->name }}</h3>
				<div class="price">{{ $plan->price() }} <small>/ year</small></div>
				<ul class="ticks">
					<li>{{ $plan->sitesLabel() }}</li>
					<li>All Pro features</li>
					<li>Updates and support for {{ $plan->months }} months</li>
				</ul>
				<a class="btn" href="/checkout/{{ $plan->slug }}">Get {{ $plan->name }}</a>
			</div>
		@endforeach
	</div>
	<h2>Questions</h2>
	<details><summary>Do I need the free plugin?</summary><p>Yes. Pro is an add-on and requires the free ElevenRank plugin to be installed and active.</p></details>
	<details><summary>What happens when my licence ends?</summary><p>Pro keeps working on your sites. You stop receiving updates and support until you renew.</p></details>
	<details><summary>Can I move a licence to another site?</summary><p>Yes. Deactivate the site in your account (or from the plugin) and activate the new one.</p></details>
	<details><summary>What is the refund policy?</summary><p>You can ask for a refund within 14 days of purchase. See the <a href="/refunds">refund policy</a>.</p></details>
</div>
@endsection
