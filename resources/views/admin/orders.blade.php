@extends('layouts.admin')
@section('title', 'Orders')
@section('content')
<div class="a-between" style="margin-bottom:24px;align-items:flex-start"><div><p class="a-eyebrow">Orders</p><h1 style="margin:0">Orders</h1></div><a class="a-btn secondary" href="/admin/orders.csv">Export CSV</a></div>
<form method="get" class="h-filters">
	<input type="text" name="q" value="{{ $q }}" placeholder="Search order or email" aria-label="Search order or email">
	<select name="status" aria-label="Status"><option value="">All statuses</option>@foreach (['pending' => 'Pending', 'paid' => 'Paid', 'refunded' => 'Refunded', 'cancelled' => 'Cancelled'] as $v => $label)<option value="{{ $v }}" @selected($status === $v)>{{ $label }}</option>@endforeach</select>
	<select name="range" aria-label="Date range">@foreach (['' => 'All time', '30' => 'Last 30 days', '90' => 'Last 90 days', '365' => 'Last year'] as $v => $label)<option value="{{ $v }}" @selected((string) $range === (string) $v)>{{ $label }}</option>@endforeach</select>
	<button class="a-btn secondary">Filter</button>
</form>
@if ($orders->isEmpty())
<x-card><x-empty icon="receipt" title="No orders.">Orders matching this filter will appear here.</x-empty></x-card>
@else
<x-table :headings="['Order', 'Customer', 'Date', 'Status', 'Total']" :right="[4]">
@foreach ($orders as $o)
	<tr><td><a href="/admin/orders/{{ $o->number }}">{{ $o->number }}</a></td><td>{{ $o->user->name }}</td><td>{{ $o->created_at->format('j M Y') }}</td><td><x-status :value="$o->status" /></td><td style="text-align:right">{{ $o->amount() }}</td></tr>
@endforeach
</x-table>
@endif
@endsection
