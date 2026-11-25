@extends('layouts.account')
@section('page', 'Checkout')
@section('body')
<p class="a-eyebrow">Checkout</p>
<h1>Complete your order</h1>
<p class="a-sub">You will pay on the payment provider's secure page. We never see your card.</p>
<form method="post" action="/checkout/{{ $plan->slug }}" class="a-checkout">
	@csrf
	<div class="a-stack">
		<section class="a-card">
			<h2>Plan</h2>
			@foreach ($plans->sortBy(fn ($x) => $x->id === $plan->id ? 0 : 1) as $p)
				<a class="a-radio @if($p->id === $plan->id) on @endif" href="/checkout/{{ $p->slug }}" @if($p->id === $plan->id) aria-current="true" @endif>
					<span class="dot" aria-hidden="true">@if($p->id === $plan->id)✓@endif</span>
					<span><span class="a-h3" style="display:block;margin:0">{{ $p->name }} · {{ $p->sitesLabel() }} · {{ $p->price() }}</span>@if($p->id === $plan->id)<span class="a-small a-muted">{{ $p->months }} months of updates and support.</span>@endif</span>
				</a>
			@endforeach
		</section>
		<section class="a-card">
			<h2>Billing details</h2>
			<label class="a-label" for="b-name">Name</label>
			<input id="b-name" type="text" value="{{ auth()->user()->name }}" readonly>
			<label class="a-label" for="b-email">Email for receipt</label>
			<input id="b-email" type="email" value="{{ auth()->user()->email }}" readonly>
			<label class="a-label" for="b-country">Country</label>
			<input id="b-country" name="billing_country" type="text" maxlength="80" value="{{ old('billing_country', auth()->user()->billing_country) }}" autocomplete="country-name">
		</section>
	</div>
	<aside class="a-card">
		<h2>Order summary</h2>
		<div class="a-between" style="margin-bottom:8px"><span>{{ $plan->name }}, {{ $plan->months }} months</span><span>{{ $plan->priceFull() }}</span></div>
		<div class="a-between a-muted" style="margin-bottom:16px"><span>Tax</span><span>Shown on the provider page</span></div>
		<div class="a-between" style="border-top:1px solid var(--line);padding-top:16px;margin-bottom:20px"><strong>Total</strong><strong>{{ $plan->priceFull() }}</strong></div>
		<button class="a-btn primary" style="width:100%">Continue to payment</button>
		<p class="a-small a-muted" style="margin:12px 0 0">14-day refund. After your term ends Pro keeps working, only updates and support stop. By ordering you accept the <a href="/terms">terms</a>.</p>
	</aside>
</form>
@endsection
