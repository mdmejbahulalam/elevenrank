@extends('layouts.admin')
@section('title', 'Audit log')
@section('content')
<h1>Audit log</h1>
<div class="card" style="overflow-x:auto">
@if ($logs->isEmpty()) <p class="muted">Nothing recorded yet.</p> @else
<table>
	<thead><tr><th>When</th><th>Who</th><th>Action</th><th>Subject</th></tr></thead>
	<tbody>@foreach ($logs as $l)<tr><td>{{ $l->created_at->format('Y-m-d H:i') }}</td><td>{{ $l->user?->email ?? 'system' }}</td><td>{{ $l->action }}</td><td>{{ $l->subject }}</td></tr>@endforeach</tbody>
</table>@endif
</div>
@endsection
