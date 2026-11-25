@extends('layouts.account')
@section('page', 'Ticket')
@section('body')
<h1>{{ $ticket->subject }} <x-status :value="$ticket->status" /></h1>
@foreach ($ticket->replies as $r)
	<x-card style="margin-bottom:12px;{{ $r->is_staff ? 'border-color:var(--accent)' : '' }}">
		<p class="muted" style="margin:0 0 6px">{{ $r->is_staff ? 'ElevenRank support' : 'You' }} · {{ $r->created_at->format('j M Y H:i') }}</p>
		<div style="white-space:pre-wrap">{{ $r->body }}</div>
	</x-card>
@endforeach
@if ($ticket->status !== 'closed')
	<form method="post" action="/account/tickets/{{ $ticket->id }}/reply">
		@csrf
		<x-field name="body" label="Reply" :rows="4" maxlength="5000" required />
		<x-button style="margin-top:16px">Send reply</x-button>
	</form>
@else
	<p class="muted">This ticket is closed. Open a new one if you need more help.</p>
@endif
@endsection
