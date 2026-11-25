@extends('layouts.admin')
@section('title', 'Orders')
@section('content')
<div class="a-between" style="margin-bottom:24px"><div><p class="a-eyebrow">Sales</p><h1 style="margin:0">Orders</h1></div><a class="a-btn secondary" href="/admin/orders.csv">Export CSV</a></div>
<nav class="h-chips" aria-label="Filter by status">
	@foreach (['' => 'All', 'pending' => 'Pending', 'paid' => 'Paid', 'refunded' => 'Refunded', 'cancelled' => 'Cancelled'] as $v => $label)
		<a href="/admin/orders{{ $v ? '?status='.$v : '' }}" @if($status === $v) aria-current="true" @endif>{{ $label }}</a>
	@endforeach
</nav>
@if ($orders->isEmpty())
<x-card><x-empty icon="receipt" title="No orders.">Orders matching this filter will appear here.</x-empty></x-card>
@else
<x-table :headings="['Order', 'Customer', 'Plan', 'Amount', 'Status', 'Date', 'Actions']">
@foreach ($orders as $o)
	<tr>
		<td>{{ $o->number }}</td><td>{{ $o->user->email }}</td><td>{{ $o->plan->name }}</td><td>{{ $o->amount() }}</td><td><x-status :value="$o->status" /></td><td>{{ $o->created_at->format('j M Y') }}</td>
		<td>
			@if ($o->status === 'pending')
				<form method="post" action="/admin/orders/{{ $o->id }}" style="display:inline">@csrf<input type="hidden" name="do" value="paid"><button class="a-btn secondary sm">Mark paid</button></form>
				<form method="post" action="/admin/orders/{{ $o->id }}" style="display:inline">@csrf<input type="hidden" name="do" value="cancel"><button class="a-btn ghost sm">Cancel</button></form>
			@elseif ($o->status === 'paid')
				<form method="post" action="/admin/orders/{{ $o->id }}" style="display:inline-flex;gap:6px;align-items:center" onsubmit="return confirm('Refund this order and revoke its licence?')">@csrf<input type="hidden" name="do" value="refund"><input type="text" name="reason" placeholder="Reason" aria-label="Refund reason" required minlength="3" maxlength="255" style="width:130px;min-height:32px"><button class="a-btn secondary sm">Refund</button></form>
			@endif
		</td>
	</tr>
@endforeach
</x-table>
@endif
@endsection
