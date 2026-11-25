@extends('layouts.admin')
@section('title', 'Customers')
@section('content')
<div class="a-between" style="margin-bottom:24px"><div><p class="a-eyebrow">Sales</p><h1 style="margin:0">Customers</h1></div></div>
<form method="get" class="h-filters"><input type="text" name="q" value="{{ $q }}" placeholder="Search name or email" aria-label="Search name or email"><button class="a-btn secondary">Filter</button></form>
@if ($users->isEmpty())
<x-card><x-empty icon="users" title="No customers.">Try a different search.</x-empty></x-card>
@else
<x-table :headings="['Customer', 'Licences', 'Orders', 'Joined']">
@foreach ($users as $u)
	<tr><td><a href="/admin/customers/{{ $u->id }}">{{ $u->name }}</a><br><span class="a-small a-muted">{{ $u->email }}</span></td><td>{{ $u->licences_count }}</td><td>{{ $u->orders_count }}</td><td>{{ $u->created_at->format('j M Y') }}</td></tr>
@endforeach
</x-table>
@endif
@endsection
