@extends('layouts.account')
@section('page', 'Order')
@section('body')
<h1>Order {{ $order->number }}</h1>
<x-card>
	<p><strong>{{ $order->plan->name }}</strong> · {{ $order->plan->sitesLabel() }} · {{ $order->plan->months }} months</p>
	<p>Amount: {{ $order->amount() }} · Placed {{ $order->created_at->format('j M Y') }} · Status: <x-status :value="$order->status" /></p>
	@if ($order->status === 'pending')
		<p>We have your order. Payment is being finalised, and we will send payment instructions to {{ auth()->user()->email }}. Your licence key appears here as soon as the payment is confirmed.</p>
	@elseif ($order->status === 'paid' && $order->licence)
		<p>Licence key: <code>{{ $order->licence->license_key }}</code> · valid until {{ $order->licence->expires?->format('j M Y') ?? 'no expiry' }}. <a href="/account/downloads">Download Pro</a>.</p>
		<p class="muted">Refunds are available within 14 days. <a href="/account/tickets">Open a ticket</a> quoting this order number.</p>
	@elseif ($order->status === 'refunded')
		<p>Refunded {{ $order->refunded_at?->format('j M Y') }}. The licence key has been switched off.</p>
	@endif
	@if ($order->status !== 'pending')
		<p style="margin-top:12px"><x-button href="/account/orders/{{ $order->number }}/invoice" variant="secondary">View invoice</x-button></p>
	@endif
	@if ($order->status === 'paid' && $order->paid_at && $order->paid_at->gte(now()->subDays(14)))
		<form method="post" action="/account/orders/{{ $order->number }}/refund" style="margin-top:12px">@csrf<button class="link">Request a refund</button> <span class="muted">(within 14 days)</span></form>
	@endif
	@error('refund')<div class="field-error" role="alert">{{ $message }}</div>@enderror
</x-card>
@endsection
