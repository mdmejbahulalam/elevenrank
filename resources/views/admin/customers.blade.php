@extends('layouts.admin')
@section('title', 'Customers')
@section('content')
<h1>Customers</h1>
<form method="get" class="row" style="margin:0 0 16px"><input type="text" name="q" value="{{ $q }}" placeholder="Search name or email" style="max-width:320px"><button>Search</button></form>
<div class="card" style="overflow-x:auto">
@if ($users->isEmpty()) <p class="muted">No customers.</p> @else
<table>
	<thead><tr><th>Name</th><th>Email</th><th>Licences</th><th>Orders</th><th>Joined</th></tr></thead>
	<tbody>@foreach ($users as $u)
		<tr><td><a href="/admin/customers/{{ $u->id }}">{{ $u->name }}</a></td><td>{{ $u->email }}</td><td>{{ $u->licences_count }}</td><td>{{ $u->orders_count }}</td><td>{{ $u->created_at->format('Y-m-d') }}</td></tr>
	@endforeach</tbody>
</table>@endif
</div>
@endsection
