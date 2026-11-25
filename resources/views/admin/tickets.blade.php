@extends('layouts.admin')
@section('title', 'Tickets')
@section('content')
<p class="a-eyebrow">Support</p><h1 style="margin:0 0 24px">Ticket queue</h1>
<form method="get" class="h-filters">
	<input type="text" name="q" value="{{ $q }}" placeholder="Search subject or email" aria-label="Search subject or email">
	<select name="status" aria-label="Status"><option value="">Any status</option>@foreach (['open' => 'Waiting for staff', 'answered' => 'Replied', 'closed' => 'Solved'] as $v => $label)<option value="{{ $v }}" @selected($status === $v)>{{ $label }}</option>@endforeach</select>
	<select name="assigned" aria-label="Assigned"><option value="">Assigned to anyone</option><option value="me" @selected($assigned === 'me')>Assigned to me</option><option value="none" @selected($assigned === 'none')>Unassigned</option></select>
	<button class="a-btn secondary">Filter</button>
</form>
@if ($tickets->isEmpty())
<x-card><x-empty icon="info" title="Nothing here.">No tickets match this filter.</x-empty></x-card>
@else
<x-table :headings="['Subject', 'Customer', 'Updated', 'Status']">
@foreach ($tickets as $t)
	<tr><td><a href="/admin/tickets/{{ $t->id }}">{{ $t->subject }}</a></td><td>{{ $t->user?->name ?? '—' }}</td><td>{{ $t->updated_at->isToday() ? 'Today' : $t->updated_at->diffForHumans() }}</td><td><x-status :value="$t->status" /></td></tr>
@endforeach
</x-table>
@endif
@endsection
