@extends('layouts.admin')
@section('title', 'Tickets')
@section('content')
<div class="a-between" style="margin-bottom:24px"><div><p class="a-eyebrow">Support</p><h1 style="margin:0">Ticket queue</h1></div></div>
<nav class="h-chips" aria-label="Filter by status">
	@foreach (['open' => 'Open', 'answered' => 'Answered', 'closed' => 'Closed', 'all' => 'All'] as $v => $label)
		<a href="/admin/tickets?status={{ $v }}" @if($status === $v) aria-current="true" @endif>{{ $label }}</a>
	@endforeach
</nav>
@if ($tickets->isEmpty())
<x-card><x-empty icon="info" title="Nothing here.">No tickets match this filter.</x-empty></x-card>
@else
<x-table :headings="['Subject', 'Customer', 'Updated', 'Status']">
@foreach ($tickets as $t)
	<tr><td><a href="/admin/tickets/{{ $t->id }}">{{ $t->subject }}</a></td><td>{{ $t->user->email }}</td><td>{{ $t->updated_at->format('j M Y, H:i') }}</td><td><x-status :value="$t->status" /></td></tr>
@endforeach
</x-table>
@endif
@endsection
