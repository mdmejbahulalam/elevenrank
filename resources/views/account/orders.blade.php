@extends('layouts.account')
@section('page', 'Orders')
@section('body')
<p class="a-eyebrow">Orders</p>
<h1>Orders and invoices</h1>
<p class="a-sub">Every purchase and renewal. Refunds are available for 14 days.</p>
@if ($orders->isEmpty())
	<section class="a-card"><x-empty icon="receipt" title="No orders yet."><a href="/pricing">See pricing</a>.</x-empty></section>
@else
	<div class="a-table-wrap"><table class="a-table">
		<thead><tr><th>Order</th><th>Date</th><th>Item</th><th>Status</th><th style="text-align:right">Total</th><th style="text-align:right">Invoice</th></tr></thead>
		<tbody>
		@foreach ($orders as $o)
			@php $tone = ['paid' => 'ok', 'pending' => 'warn', 'refunded' => 'bad', 'failed' => 'bad'][$o->status] ?? ''; @endphp
			<tr>
				<td><a href="/account/orders/{{ $o->number }}">{{ $o->number }}</a></td>
				<td>{{ $o->created_at->format('j M Y') }}</td>
				<td>{{ $o->plan->name }}, {{ $o->plan->sites ?: 'unlimited' }} {{ $o->plan->sites === 1 ? 'site' : 'sites' }}@if ($o->plan->months), {{ $o->plan->months }} months @endif</td>
				<td><span class="a-pill {{ $tone }}">{{ ucfirst($o->status) }}</span></td>
				<td style="text-align:right">{{ $o->amount() }}</td>
				<td style="text-align:right">@if (in_array($o->status, ['paid', 'refunded'], true))<a href="/account/orders/{{ $o->number }}">View</a>@else<span class="a-muted">—</span>@endif</td>
			</tr>
		@endforeach
		</tbody>
	</table></div>
@endif
@endsection
