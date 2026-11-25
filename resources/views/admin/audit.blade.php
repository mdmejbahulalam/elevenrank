@extends('layouts.admin')
@section('title', 'Audit log')
@section('content')
<div class="a-between" style="margin-bottom:24px"><div><p class="a-eyebrow">System</p><h1 style="margin:0">Audit log</h1></div></div>
@if ($logs->isEmpty())
<x-card><x-empty icon="info" title="Nothing recorded yet.">Admin actions will appear here.</x-empty></x-card>
@else
<x-table :headings="['When', 'Who', 'Action', 'Subject', 'Reason', 'IP']">
@foreach ($logs as $l)<tr><td>{{ $l->created_at->format('j M, H:i') }}</td><td>{{ $l->user?->email ?? 'system' }}</td><td>{{ $l->action }}</td><td>{{ $l->subject }}</td><td>{{ $l->reason }}</td><td>{{ $l->ip }}</td></tr>@endforeach
</x-table>
<p class="a-small a-muted" style="margin:12px 0 0">Entries cannot be edited or deleted.</p>
@endif
@endsection
