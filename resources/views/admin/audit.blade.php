@extends('layouts.admin')
@section('title', 'Audit log')
@section('content')
<div class="a-between" style="align-items:flex-start;margin-bottom:24px"><div><p class="a-eyebrow">System</p><h1 style="margin:0">Audit log</h1></div><a class="a-btn secondary" href="/admin/audit.csv?{{ http_build_query(['q' => $q, 'who' => $who, 'range' => $range]) }}">Export CSV</a></div>
<form method="get" class="h-filters">
	<input type="text" name="q" value="{{ $q }}" placeholder="Search action or person" aria-label="Search action or person">
	<select name="who" aria-label="Staff member"><option value="">All staff</option>@foreach ($staff as $s)<option value="{{ $s->id }}" @selected($who === (string) $s->id)>{{ $s->name }}</option>@endforeach</select>
	<select name="range" aria-label="Period">@foreach (['7' => 'Last 7 days', '30' => 'Last 30 days', '90' => 'Last 90 days', '0' => 'All time'] as $v => $label)<option value="{{ $v }}" @selected($range === (string) $v)>{{ $label }}</option>@endforeach</select>
	<button class="a-btn secondary">Filter</button>
</form>
@if ($logs->isEmpty())
<x-card><x-empty icon="info" title="Nothing recorded yet.">Staff actions will appear here.</x-empty></x-card>
@else
<x-table :headings="['When', 'Who', 'Action', 'Target']">
@foreach ($logs as $l)<tr><td>{{ $l->created_at->isToday() ? 'Today' : ($l->created_at->isYesterday() ? 'Yesterday' : $l->created_at->format('j M')) }} {{ $l->created_at->format('H:i') }}</td><td>{{ $l->user?->name ?? 'System' }}</td><td>{{ $l->action }}@if ($l->reason) <span class="a-muted">· {{ $l->reason }}</span>@endif</td><td>{{ $l->subject }}</td></tr>@endforeach
</x-table>
@endif
<p class="a-muted" style="margin-top:8px">Entries cannot be edited or deleted.</p>
@endsection
