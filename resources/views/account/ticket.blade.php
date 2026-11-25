@extends('layouts.account')
@section('page', 'Ticket')
@section('body')
@php [$tone, $label] = ['open' => ['warn', 'Open'], 'answered' => ['info', 'Staff replied'], 'closed' => ['ok', 'Solved']][$ticket->status] ?? ['', ucfirst($ticket->status)]; @endphp
<div class="a-between" style="align-items:flex-start;margin-bottom:24px">
	<div><p class="a-eyebrow"><a href="/account/tickets">Support</a> / Ticket {{ $ticket->id }}</p><h1 style="margin:0">{{ $ticket->subject }}</h1></div>
	<span class="a-pill {{ $tone }}" style="margin-top:14px">{{ $label }}</span>
</div>
<div class="a-stack" style="gap:16px">
	@foreach ($ticket->replies as $r)
		<div class="a-msg @if($r->is_staff) staff @endif"><p class="who">{{ $r->is_staff ? 'ElevenRank support' : 'You' }} · {{ $r->created_at->isToday() ? 'Today, '.$r->created_at->format('H:i') : $r->created_at->format('j M Y, H:i') }}</p><div style="white-space:pre-wrap">{{ $r->body }}</div></div>
	@endforeach
</div>
@if ($ticket->status !== 'closed')
	<section class="a-card" style="margin-top:24px;max-width:720px">
		<form method="post" action="/account/tickets/{{ $ticket->id }}/reply" id="reply">
			@csrf
			<label class="a-label" for="r-body" style="margin-top:0">Reply</label>
			<textarea id="r-body" name="body" rows="5" maxlength="5000" required placeholder="Write your reply"></textarea>
			@error('body')<p class="a-small" style="color:var(--bad);margin:4px 0 0">{{ $message }}</p>@enderror
		</form>
		<form method="post" action="/account/tickets/{{ $ticket->id }}/solve" id="solve">@csrf</form>
		<p class="a-row" style="margin:16px 0 0"><button class="a-btn primary" form="reply">Send reply</button><button class="a-btn secondary" form="solve">Mark as solved</button></p>
	</section>
@else
	<p class="a-muted" style="margin-top:24px">This ticket is solved. Reply below to reopen it, or <a href="/account/tickets/new">open a new one</a>.</p>
	<section class="a-card" style="margin-top:16px;max-width:720px"><form method="post" action="/account/tickets/{{ $ticket->id }}/reply">@csrf<label class="a-label" for="r-body2" style="margin-top:0">Reply</label><textarea id="r-body2" name="body" rows="4" maxlength="5000" required></textarea><p style="margin:16px 0 0"><button class="a-btn primary">Send reply</button></p></form></section>
@endif
@endsection
