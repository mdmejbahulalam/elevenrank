@extends('layouts.account')
@section('page', 'Ticket')
@section('body')
<h1>{{ $ticket->subject }} <span class="tag">{{ $ticket->status }}</span></h1>
@foreach ($ticket->replies as $r)
	<div class="card" style="margin-bottom:12px;{{ $r->is_staff ? 'border-color:var(--accent)' : '' }}">
		<p class="muted" style="margin:0 0 6px">{{ $r->is_staff ? 'ElevenRank support' : 'You' }} · {{ $r->created_at->format('j M Y H:i') }}</p>
		<div style="white-space:pre-wrap">{{ $r->body }}</div>
	</div>
@endforeach
@if ($ticket->status !== 'closed')
	<form method="post" action="/account/tickets/{{ $ticket->id }}/reply">
		@csrf
		<label for="body">Reply</label>
		<textarea id="body" name="body" rows="4" maxlength="5000" required></textarea>
		@error('body') <p class="err" role="alert">{{ $message }}</p> @enderror
		<p><button class="btn">Send reply</button></p>
	</form>
@else
	<p class="muted">This ticket is closed. Open a new one if you need more help.</p>
@endif
@endsection
