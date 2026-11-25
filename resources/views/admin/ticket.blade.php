@extends('layouts.admin')
@section('title', $ticket->subject)
@section('content')
<div class="a-between" style="align-items:flex-start;margin-bottom:24px"><div><p class="a-eyebrow"><a href="/admin/tickets">Tickets</a> / {{ $ticket->id }}</p><p class="a-eyebrow" style="margin-top:0">Ticket {{ $ticket->id }}</p><h1 style="margin:0">{{ $ticket->subject }}</h1></div><x-status :value="$ticket->status" /></div>
<div class="h-two">
	<div class="a-stack" style="gap:16px">
		@foreach ($ticket->replies as $r)
			@if ($r->is_note)
				<div class="a-alert warn"><div><strong>Internal note.</strong> Customers never see this. {{ $r->body }} <span class="a-muted">· {{ $r->user?->name }}, {{ $r->created_at->format('j M H:i') }}</span></div></div>
			@else
				<div class="h-msg {{ $r->is_staff ? 'staff' : '' }}"><p class="a-eyebrow" style="margin:0 0 4px">{{ $r->is_staff ? ($r->user_id === auth()->id() ? 'You' : ($r->user?->name ?? 'Staff')) : $ticket->user?->name }} · {{ $r->created_at->isToday() ? 'Today' : $r->created_at->format('j M Y') }}</p><div style="white-space:pre-wrap">{{ $r->body }}</div></div>
			@endif
		@endforeach
		<form method="post" action="/admin/tickets/{{ $ticket->id }}/reply" class="a-card">@csrf
			<label class="a-label" for="body" style="margin-top:0">Reply</label><textarea id="body" name="body" rows="5" placeholder="Write a reply" @error('body') aria-invalid="true" @enderror>{{ old('body') }}</textarea>
			@error('body')<div class="field-error" role="alert">{{ $message }}</div>@enderror
			<p class="a-row" style="margin:16px 0 0"><button class="a-btn primary" name="do" value="reply">Send reply</button><button class="a-btn secondary" name="do" value="note">Add internal note</button>@if ($ticket->status !== 'closed')<button class="a-btn" name="do" value="solve" style="background:none;border:0;color:var(--accent);text-decoration:none">Mark solved</button>@endif</p>
		</form>
	</div>
	<aside class="a-card" style="align-self:start"><h2>Customer</h2>
		<dl class="h-kv"><dt>Name</dt><dd>@if ($ticket->user)<a href="/admin/customers/{{ $ticket->user_id }}">{{ $ticket->user->name }}</a>@else—@endif</dd>
			<dt>Licence</dt><dd>@if ($licence)<a href="/admin/licences/{{ $licence->id }}">{{ $licence->plan?->name ?? 'Pro' }}, {{ strtolower(($licence->status === 'active' && $licence->isExpired()) ? 'term ended' : $licence->status) }}</a>@else—@endif</dd>
			<dt>Assigned</dt><dd>{{ $ticket->assignee?->name ?? 'Nobody yet' }}</dd></dl>
	</aside>
</div>
@endsection
