@extends('layouts.admin')
@section('title', 'Customers')
@section('content')
<p class="a-eyebrow">Customers</p><h1 style="margin:0 0 24px">Customers</h1>
<form method="get" class="h-filters">
	<input type="text" name="q" value="{{ $q }}" placeholder="Search name or email" aria-label="Search name or email">
	<select name="plan" aria-label="Plan"><option value="">All plans</option>@foreach ($plans as $p)<option value="{{ $p->id }}" @selected((string) $plan === (string) $p->id)>{{ $p->name }}</option>@endforeach</select>
	<button class="a-btn secondary">Filter</button>
</form>
@if ($users->isEmpty())
<x-card><x-empty icon="users" title="No customers.">Try a different search.</x-empty></x-card>
@else
<x-table :headings="['Customer', 'Plan', 'Licences', 'Joined', 'Status']">
@foreach ($users as $u)
	@php $l = $u->licences->first(); $st = ! $l ? null : ($l->status !== 'active' ? ($l->status === 'revoked' ? 'refunded' : $l->status) : ($l->isExpired() ? 'expired' : 'active')); @endphp
	<tr><td><a href="/admin/customers/{{ $u->id }}">{{ $u->name }}</a><br>{{ $u->email }}</td><td>{{ $l?->plan?->name ?? '—' }}</td><td>{{ $u->licences_count }}</td><td>{{ $u->created_at->format('j M Y') }}</td><td>@if ($st)<x-status :value="$st" />@else<span class="a-muted">—</span>@endif</td></tr>
@endforeach
</x-table>
@endif
@endsection
