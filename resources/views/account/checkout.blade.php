@extends('layouts.account')
@section('page', 'Checkout')
@section('body')
<p class="a-eyebrow">Checkout</p>
<h1>Complete your order</h1>
<p class="a-sub">Review the plan, then place the order. You will receive payment instructions by e-mail.</p>
<div class="a-grid" style="grid-template-columns:repeat(auto-fit,minmax(280px,1fr));align-items:start">
	<section class="a-card">
		<h2>Plan</h2>
		<div class="a-check" style="border-top:0"><span class="a-dot done" aria-hidden="true">✓</span><div><p class="a-h3" style="margin:0">{{ $plan->name }} · {{ $plan->sitesLabel() }} · {{ $plan->price() }}</p><p class="a-small a-muted" style="margin:0">{{ $plan->months }} months of updates and support.</p></div></div>
	</section>
	<aside class="a-card">
		<h2>Order summary</h2>
		<div class="a-between" style="margin-bottom:8px"><span>{{ $plan->name }}, {{ $plan->months }} months</span><span>{{ $plan->price() }}</span></div>
		<div class="a-between a-muted" style="margin-bottom:16px"><span>Tax</span><span>Set by the payment provider</span></div>
		<div class="a-between" style="border-top:1px solid var(--line);padding-top:16px;margin-bottom:20px"><strong>Total</strong><strong>{{ $plan->price() }}</strong></div>
		<form method="post" action="/checkout/{{ $plan->slug }}">@csrf<button class="a-btn primary" style="width:100%">Place order</button></form>
		<p class="a-small a-muted" style="margin:12px 0 0">14-day refund. After your term ends Pro keeps working, only updates and support stop. By ordering you accept the <a href="/terms">terms</a>.</p>
	</aside>
</div>
@endsection
