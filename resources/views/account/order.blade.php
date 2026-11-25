@extends('layouts.account')
@section('page', 'Order')
@section('body')
@if ($order->status === 'pending')
<p class="a-eyebrow"><a href="/account/orders">Orders</a> / {{ $order->number }}</p>
<h1 style="margin-bottom:24px">Order {{ $order->number }}</h1>
	<section class="a-card"><div class="a-empty">
		<div class="ic"><svg viewBox="0 0 24 24" aria-hidden="true"><circle cx="12" cy="12" r="9"/><path d="M12 7v5l3 2"/></svg></div>
		<h2 style="margin-bottom:8px">Waiting for your payment</h2>
		<p class="a-muted" style="margin:0 0 20px">We have your order. Payment is being finalised, and we will send payment instructions to {{ auth()->user()->email }}. Your licence key appears here as soon as the payment is confirmed.</p>
		<div class="a-row" style="justify-content:center"><a class="a-btn secondary" href="/account/orders">View orders</a><a class="a-btn ghost" href="/account/tickets">Paid but nothing here? Contact support</a></div>
	</div></section>
@else
@php
	$canRefund = $order->status === 'paid' && $order->paid_at && $order->paid_at->gte(now()->subDays(14));
	$tone = ['paid' => 'ok', 'refunded' => 'bad'][$order->status] ?? '';
	$u = $order->user ?? auth()->user();
@endphp
<div class="a-between" style="align-items:flex-start;max-width:990px">
	<div><p class="a-eyebrow"><a href="/account/orders">Orders</a> / {{ $order->number }}</p><h1 style="margin:0">Invoice {{ $order->number }}</h1></div>
	<div class="a-row">
		<button type="button" class="a-btn secondary" onclick="window.print()">Download PDF</button>
		@if ($canRefund)<form method="post" action="/account/orders/{{ $order->number }}/refund" style="margin:0">@csrf<button class="a-btn ghost">Request a refund</button></form>@endif
	</div>
</div>
@error('refund')<div class="a-alert bad" role="alert" style="margin-top:16px"><div>{{ $message }}</div></div>@enderror
<div class="a-stack" style="margin-top:24px;max-width:720px">
	<section class="a-card" style="padding:32px">
		<div class="a-between"><p class="a-h2" style="margin:0;font-size:20px">Eleven<span style="color:var(--accent)">Rank</span></p><span class="a-pill {{ $tone }}">{{ ucfirst($order->status) }}</span></div>
		<div class="a-grid" style="margin-top:28px">
			<div><p class="a-eyebrow" style="margin:0 0 6px">Billed to</p><p style="margin:0;line-height:24px">{{ $u->name }}<br>{{ $u->email }}@if ($u->billing_address)<br>{{ $u->billing_address }}@if ($u->billing_country), {{ $u->billing_country }}@endif @endif</p></div>
			<div><p class="a-eyebrow" style="margin:0 0 6px">Details</p><p style="margin:0;line-height:24px">Invoice {{ $order->number }}<br>Issued {{ ($order->paid_at ?? $order->created_at)->format('j M Y') }}@if ($order->refunded_at)<br>Refunded {{ $order->refunded_at->format('j M Y') }}@endif</p></div>
		</div>
		<div class="a-table-wrap" style="margin-top:28px"><table class="a-table" style="min-width:0">
			<thead><tr><th>Item</th><th style="text-align:right">Amount</th></tr></thead>
			<tbody>
				<tr><td><strong>ElevenRank Pro, {{ $order->plan->name }} ({{ $order->plan->sitesLabel() }}), {{ $order->plan->months }} months</strong></td><td style="text-align:right">{{ $order->amount() }}</td></tr>
				<tr><td class="a-muted">Tax</td><td class="a-muted" style="text-align:right">[set by payment provider]</td></tr>
				<tr><td><strong>Total</strong></td><td style="text-align:right"><strong>{{ $order->amount() }}</strong></td></tr>
			</tbody>
		</table></div>
		<p class="a-small a-muted" style="margin:24px 0 0">[Seller legal name and address go here once confirmed.]</p>
	</section>
	@if ($order->status === 'paid' && $order->licence)
		<section class="a-card">
			<h2>Your licence</h2>
			<x-copy-field :value="$order->licence->license_key" label="Copy key" />
			<p class="a-small a-muted" style="margin:12px 0 0">Valid until {{ $order->licence->expires?->format('j M Y') ?? 'no expiry' }}. <a href="/account/downloads">Download Pro</a>.</p>
		</section>
	@elseif ($order->status === 'refunded')
		<div class="a-alert info"><div><strong>Refunded {{ $order->refunded_at?->format('j M Y') }}.</strong> The licence key has been switched off.</div></div>
	@endif
	<p class="a-small a-muted" style="margin:0">Refunds are available within 14 days of purchase. <a href="/account/tickets">Open a ticket</a> quoting this order number if you need help.</p>
</div>
@endif
@endsection
