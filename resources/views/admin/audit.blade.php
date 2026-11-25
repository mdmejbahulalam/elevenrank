@extends('layouts.admin')
@section('title', 'Audit log')
@section('content')
<h1>Audit log</h1>
@if ($logs->isEmpty())
<x-card><x-empty icon="info" title="Nothing recorded yet.">Admin actions will appear here.</x-empty></x-card>
@else
<x-table :headings="['When', 'Who', 'Action', 'Subject']">
@foreach ($logs as $l)<tr><td>{{ $l->created_at->format('Y-m-d H:i') }}</td><td>{{ $l->user?->email ?? 'system' }}</td><td>{{ $l->action }}</td><td>{{ $l->subject }}</td></tr>@endforeach
</x-table>
@endif
@endsection
