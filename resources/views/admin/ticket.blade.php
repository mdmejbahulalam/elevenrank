@extends('layouts.admin')
@section('title', $ticket->subject)
@section('content')
<h1>{{ $ticket->subject }}</h1>
<p class="muted">{{ $ticket->user->name }} · {{ $ticket->user->email }} · {{ $ticket->status }}</p>
@foreach ($ticket->replies as $r)
	<div class="card"><p class="muted" style="margin:0 0 6px">{{ $r->is_staff ? 'Staff' : $ticket->user->name }} · {{ $r->created_at->format('Y-m-d H:i') }}</p><div style="white-space:pre-wrap">{{ $r->body }}</div></div>
@endforeach
<div class="card">
	<form method="post" action="/admin/tickets/{{ $ticket->id }}/reply">
		@csrf
		<label for="body">Reply</label>
		<textarea id="body" name="body" rows="5" required></textarea>
		@error('body') <p class="err" role="alert">{{ $message }}</p> @enderror
		<label style="display:inline-flex;gap:8px;align-items:center"><input type="checkbox" name="close" value="1"> Close the ticket</label>
		<div class="row"><button>Send reply</button></div>
	</form>
</div>
@endsection
