@extends('layouts.account')
@section('page', 'Orders')
@section('body')
<h1>Orders</h1>
@if ($orders->isEmpty())
	<x-card><x-empty icon="receipt" title="No orders yet."><a href="/pricing">See pricing</a>.</x-empty></x-card>
@else
	<x-table :headings="['Order', 'Plan', 'Amount', 'Status', 'Date']">
		@foreach ($orders as $o)
			<tr><td><a href="/account/orders/{{ $o->number }}">{{ $o->number }}</a></td><td>{{ $o->plan->name }}</td><td>{{ $o->amount() }}</td><td><x-status :value="$o->status" /></td><td>{{ $o->created_at->format('j M Y') }}</td></tr>
		@endforeach
	</x-table>
@endif
@endsection
