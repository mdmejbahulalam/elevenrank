@extends('layouts.admin')
@section('title', 'Tickets')
@section('content')
<h1>Support tickets</h1>
<p>Show:
	@foreach (['open' => 'Open', 'answered' => 'Answered', 'closed' => 'Closed', 'all' => 'All'] as $v => $label)
		<a href="/admin/tickets?status={{ $v }}" @if($status === $v) style="font-weight:700" aria-current="true" @endif>{{ $label }}</a>@if(! $loop->last) · @endif
	@endforeach
</p>
@if ($tickets->isEmpty())
<x-card><x-empty icon="info" title="Nothing here.">No tickets match this filter.</x-empty></x-card>
@else
<x-table :headings="['Subject', 'Customer', 'Status', 'Updated']">
@foreach ($tickets as $t)
	<tr><td><a href="/admin/tickets/{{ $t->id }}">{{ $t->subject }}</a></td><td>{{ $t->user->email }}</td><td><x-status :value="$t->status" /></td><td>{{ $t->updated_at->format('Y-m-d H:i') }}</td></tr>
@endforeach
</x-table>
@endif
@endsection
