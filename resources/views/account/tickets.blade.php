@extends('layouts.account')
@section('page', 'Support')
@section('body')
<div class="a-between" style="align-items:flex-start"><div><p class="a-eyebrow">Support</p><h1 style="margin:0">Your tickets</h1></div><a class="a-btn primary" href="/account/tickets/new">New ticket</a></div>
<div class="a-stack" style="margin-top:24px">
	@if ($tickets->isEmpty())
		<section class="a-card"><x-empty icon="chat" title="No tickets yet">Open one when you need help. <a href="/account/tickets/new">New ticket</a>.</x-empty></section>
	@else
		<div class="a-table-wrap"><table class="a-table">
			<thead><tr><th>Subject</th><th>Updated</th><th>Status</th></tr></thead>
			<tbody>@foreach ($tickets as $t)
				@php [$tone, $label] = ['open' => ['warn', 'Open'], 'answered' => ['info', 'Staff replied'], 'closed' => ['ok', 'Solved']][$t->status] ?? ['', ucfirst($t->status)]; @endphp
				<tr><td><a href="/account/tickets/{{ $t->id }}">{{ $t->subject }}</a></td><td>{{ $t->updated_at->isToday() ? 'Today' : $t->updated_at->format('j M Y') }}</td><td><span class="a-pill {{ $tone }}">{{ $label }}</span></td></tr>
			@endforeach</tbody>
		</table></div>
	@endif
	<p class="a-small a-muted" style="margin:0">Support is available during your term. Pro keeps working after it ends.</p>
</div>
@endsection
