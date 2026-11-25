@extends('layouts.admin')
@section('title', $ticket->subject)
@section('content')
<h1>{{ $ticket->subject }}</h1>
<p class="muted">{{ $ticket->user->name }} · {{ $ticket->user->email }} · <x-status :value="$ticket->status" /></p>
@foreach ($ticket->replies as $r)
	<x-card style="margin-bottom:12px"><p class="muted" style="margin:0 0 6px">{{ $r->is_staff ? 'Staff' : $ticket->user->name }} · {{ $r->created_at->format('Y-m-d H:i') }}</p><div style="white-space:pre-wrap">{{ $r->body }}</div></x-card>
@endforeach
<x-card>
	<form method="post" action="/admin/tickets/{{ $ticket->id }}/reply">
		@csrf
		<x-field name="body" label="Reply" :rows="5" required />
		<label style="display:inline-flex;gap:8px;align-items:center"><input type="checkbox" name="close" value="1"> Close the ticket</label>
		<div class="row" style="margin-top:16px"><x-button>Send reply</x-button></div>
	</form>
</x-card>
@endsection
