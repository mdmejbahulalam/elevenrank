@extends('layouts.account')
@section('page', 'Order')
@section('body')
<p class="a-eyebrow"><a href="/account/orders">Orders</a> / {{ $order->number }}</p>
<div class="a-between" style="margin-bottom:24px">
	<h1 style="margin:0">Order {{ $order->number }}</h1>
	@if ($order->status !== 'pending')<a class="a-btn secondary" href="/account/orders/{{ $order->number }}/invoice">View invoice</a>@endif
</div>
@if ($order->status === 'pending')
	<section class="a-card"><div class="a-empty">
		<div class="ic"><svg viewBox="0 0 24 24" aria-hidden="true"><circle cx="12" cy="12" r="9"/><path d="M12 7v5l3 2"/></svg></div>
		<h2 style="margin-bottom:8px">Waiting for your payment</h2>
		<p class="a-muted" style="margin:0 0 20px">We have your order. Payment is being finalised, and we will send payment instructions to {{ auth()->user()->email }}. Your licence key appears here as soon as the payment is confirmed.</p>
		<div class="a-row" style="justify-content:center"><a class="a-btn secondary" href="/account/orders">View orders</a><a class="a-btn ghost" href="/account/tickets">Paid but nothing here? Contact support</a></div>
	</div></section>
@else
<div class="a-stack">
	<section class="a-card">
		<div class="a-between"><p class="a-h3" style="margin:0">{{ $order->plan->name }} · {{ $order->plan->sitesLabel() }} · {{ $order->plan->months }} months</p><x-status :value="$order->status" /></div>
		<p class="a-small a-muted" style="margin:8px 0 0">Amount {{ $order->amount() }} · Placed {{ $order->created_at->format('j M Y') }}</p>
	</section>
	@if ($order->status === 'paid' && $order->licence)
		<section class="a-card">
			<h2>Your licence</h2>
			<x-copy-field :value="$order->licence->license_key" label="Copy key" />
			<p class="a-small a-muted" style="margin:12px 0 0">Valid until {{ $order->licence->expires?->format('j M Y') ?? 'no expiry' }}. <a href="/account/downloads">Download Pro</a>.</p>
		</section>
		<p class="a-small a-muted" style="margin:0">Refunds are available within 14 days. <a href="/account/tickets">Open a ticket</a> quoting this order number.</p>
	@elseif ($order->status === 'refunded')
		<div class="a-alert info"><div><strong>Refunded {{ $order->refunded_at?->format('j M Y') }}.</strong> The licence key has been switched off.</div></div>
	@endif
	@if ($order->status === 'paid' && $order->paid_at && $order->paid_at->gte(now()->subDays(14)))
		<form method="post" action="/account/orders/{{ $order->number }}/refund">@csrf<button class="a-btn secondary">Request a refund</button> <span class="a-small a-muted">(within 14 days)</span></form>
	@endif
	@error('refund')<div class="a-alert bad" role="alert"><div>{{ $message }}</div></div>@enderror
</div>
@endif
@endsection
