@extends('admin.layout')
@section('title', 'Orders')
@section('content')
<h1>Orders</h1>
<p>Filter:
	@foreach (['' => 'All', 'pending' => 'Pending', 'paid' => 'Paid', 'refunded' => 'Refunded', 'cancelled' => 'Cancelled'] as $v => $label)
		<a href="/admin/orders{{ $v ? '?status='.$v : '' }}" @if($status === $v) style="font-weight:700" @endif>{{ $label }}</a>@if(! $loop->last) · @endif
	@endforeach
</p>
<div class="card" style="overflow-x:auto">
@if ($orders->isEmpty()) <p class="muted">No orders.</p> @else
<table>
	<thead><tr><th>Order</th><th>Customer</th><th>Plan</th><th>Amount</th><th>Status</th><th>Date</th><th></th></tr></thead>
	<tbody>@foreach ($orders as $o)
		<tr>
			<td>{{ $o->number }}</td><td>{{ $o->user->email }}</td><td>{{ $o->plan->name }}</td><td>{{ $o->amount() }}</td><td>{{ $o->status }}</td><td>{{ $o->created_at->format('Y-m-d') }}</td>
			<td>
				@if ($o->status === 'pending')
					<form method="post" action="/admin/orders/{{ $o->id }}" style="display:inline">@csrf<input type="hidden" name="do" value="paid"><button class="link">Mark paid</button></form> ·
					<form method="post" action="/admin/orders/{{ $o->id }}" style="display:inline">@csrf<input type="hidden" name="do" value="cancel"><button class="link">Cancel</button></form>
				@elseif ($o->status === 'paid')
					<form method="post" action="/admin/orders/{{ $o->id }}" style="display:inline" onsubmit="return confirm('Refund this order and revoke its licence?')">@csrf<input type="hidden" name="do" value="refund"><button class="link">Refund</button></form>
				@endif
			</td>
		</tr>
	@endforeach</tbody>
</table>@endif
</div>
@endsection
