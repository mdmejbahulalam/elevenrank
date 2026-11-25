@extends('layouts.admin')
@section('title', $order->number)
@section('content')
<div class="a-between" style="align-items:flex-start;margin-bottom:24px"><div><p class="a-eyebrow"><a href="/admin/orders">Orders</a> / {{ $order->number }}</p><p class="a-eyebrow" style="margin-top:0">Order</p><h1 style="margin:0">{{ $order->number }}</h1></div>
	<div class="a-row">
		@if ($order->status === 'paid')<form method="post" action="/admin/orders/{{ $order->number }}/receipt" style="margin:0">@csrf<button class="a-btn secondary">Resend receipt</button></form>@endif
		@if ($order->status === 'paid')@can('staff.orders.refund')<button type="button" class="a-btn danger" data-open="refund">Refund order</button>@endcan @endif
		@if ($order->status === 'pending')@can('staff.orders.settle')<form method="post" action="/admin/orders/{{ $order->id }}" style="margin:0">@csrf<input type="hidden" name="do" value="paid"><button class="a-btn primary">Mark paid</button></form><form method="post" action="/admin/orders/{{ $order->id }}" style="margin:0">@csrf<input type="hidden" name="do" value="cancel"><button class="a-btn secondary">Cancel order</button></form>@endcan @endif
	</div></div>
@if ($refundAsked && $order->status === 'paid')
	<div class="a-alert warn" role="status" style="margin-bottom:24px"><div><strong>Refund requested.</strong> Customer asked on {{ $refundAsked->created_at->format('j M Y') }}. @if ($order->paid_at && $order->paid_at->gte($refundAsked->created_at->copy()->subDays(14)))Inside the 14-day window. @endif Refunding revokes the licence.</div></div>
@endif
<div class="h-two">
	<section class="a-card"><h2>Items</h2>
		<x-table :headings="['Item', 'Amount']" :right="[1]">
			<tr><td>{{ $order->plan->name }}, {{ $order->plan->sitesLabel() }}, {{ $order->plan->months }} months</td><td style="text-align:right">{{ $order->amount() }}</td></tr>
			<tr><td class="a-muted">Tax</td><td class="a-muted" style="text-align:right">from provider</td></tr>
			<tr><td><strong>Total</strong></td><td style="text-align:right"><strong>{{ $order->amount() }}</strong></td></tr>
		</x-table>
	</section>
	<aside class="a-card"><h2>Details</h2>
		<dl class="h-kv"><dt>Customer</dt><dd><a href="/admin/customers/{{ $order->user_id }}">{{ $order->user->name }}</a></dd><dt>Status</dt><dd><x-status :value="$order->status" /></dd><dt>Provider ref</dt><dd class="a-mono">{{ $order->provider_ref ?: 'set by provider' }}</dd>@if ($order->licence)<dt>Licence</dt><dd><a class="a-mono" href="/admin/licences/{{ $order->licence->id }}">{{ substr($order->licence->license_key, 0, 7) }}…{{ substr($order->licence->license_key, -4) }}</a></dd>@endif</dl>
	</aside>
</div>
@if ($order->status === 'paid')
<x-modal id="refund" title="Refund {{ $order->number }}?">
	<p class="a-muted">The customer is refunded in full and the licence is revoked. This is recorded in the audit log.</p>
	<form method="post" action="/admin/orders/{{ $order->id }}">@csrf<input type="hidden" name="do" value="refund">
		<label class="a-label" for="rr">Reason (required)</label><textarea id="rr" name="reason" rows="3" minlength="3" maxlength="255" required placeholder="Why is this being refunded?"></textarea>
		<p class="a-row" style="margin:20px 0 0;justify-content:flex-end"><button type="button" class="a-btn secondary" data-close>Cancel</button><button class="a-btn danger">Refund order</button></p></form>
</x-modal>
@endif
@endsection
