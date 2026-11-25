@extends('layouts.admin')
@section('title', 'Customers')
@section('content')
<h1>Customers</h1>
<form method="get" class="row" style="margin:0 0 16px"><input type="text" name="q" value="{{ $q }}" placeholder="Search name or email" aria-label="Search name or email" style="max-width:320px"><x-button>Search</x-button></form>
@if ($users->isEmpty())
<x-card><x-empty icon="users" title="No customers.">Try a different search.</x-empty></x-card>
@else
<x-table :headings="['Name', 'Email', 'Licences', 'Orders', 'Joined']">
@foreach ($users as $u)
	<tr><td><a href="/admin/customers/{{ $u->id }}">{{ $u->name }}</a></td><td>{{ $u->email }}</td><td>{{ $u->licences_count }}</td><td>{{ $u->orders_count }}</td><td>{{ $u->created_at->format('Y-m-d') }}</td></tr>
@endforeach
</x-table>
@endif
@endsection
