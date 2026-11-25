@extends('account.layout')
@section('page', 'Orders')
@section('body')
<h1>Orders</h1>
@if ($orders->isEmpty())
	<p>No orders yet. <a href="/pricing">See pricing</a>.</p>
@else
	<table style="width:100%;border-collapse:collapse">
		<thead><tr><th align="left">Order</th><th align="left">Plan</th><th align="left">Amount</th><th align="left">Status</th><th align="left">Date</th></tr></thead>
		<tbody>
		@foreach ($orders as $o)
			<tr><td><a href="/account/orders/{{ $o->number }}">{{ $o->number }}</a></td><td>{{ $o->plan->name }}</td><td>{{ $o->amount() }}</td><td>{{ $o->status }}</td><td>{{ $o->created_at->format('j M Y') }}</td></tr>
		@endforeach
		</tbody>
	</table>
@endif
@endsection
