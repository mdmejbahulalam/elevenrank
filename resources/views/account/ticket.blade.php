@extends('layouts.account')
@section('page', 'Ticket')
@section('body')
<p class="a-eyebrow"><a href="/account/tickets">Support</a> / Ticket {{ $ticket->id }}</p>
<div class="a-between" style="margin-bottom:24px"><h1 style="margin:0">{{ $ticket->subject }}</h1><x-status :value="$ticket->status" /></div>
<div class="a-stack" style="gap:16px">
	@foreach ($ticket->replies as $r)
		<div class="a-msg @if($r->is_staff) staff @endif"><p class="who">{{ $r->is_staff ? 'ElevenRank support' : 'You' }} · {{ $r->created_at->format('j M Y, H:i') }}</p><div style="white-space:pre-wrap">{{ $r->body }}</div></div>
	@endforeach
</div>
@if ($ticket->status !== 'closed')
	<section class="a-card" style="margin-top:24px;max-width:720px">
		<form method="post" action="/account/tickets/{{ $ticket->id }}/reply">
			@csrf
			<x-field name="body" label="Reply" :rows="4" maxlength="5000" required />
			<p style="margin-top:16px"><button class="a-btn primary">Send reply</button></p>
		</form>
	</section>
@else
	<p class="a-muted" style="margin-top:24px">This ticket is closed. Open a new one if you need more help.</p>
@endif
@endsection
