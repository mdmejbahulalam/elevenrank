@extends('admin.layout')
@section('title', 'Tickets')
@section('content')
<h1>Support tickets</h1>
<p>Show:
	@foreach (['open' => 'Open', 'answered' => 'Answered', 'closed' => 'Closed', 'all' => 'All'] as $v => $label)
		<a href="/admin/tickets?status={{ $v }}" @if($status === $v) style="font-weight:700" @endif>{{ $label }}</a>@if(! $loop->last) · @endif
	@endforeach
</p>
<div class="card">
@forelse ($tickets as $t)
	<p style="margin:6px 0"><a href="/admin/tickets/{{ $t->id }}">{{ $t->subject }}</a> · {{ $t->user->email }} · {{ $t->status }} · {{ $t->updated_at->format('Y-m-d H:i') }}</p>
@empty <p class="muted">Nothing here.</p> @endforelse
</div>
@endsection
