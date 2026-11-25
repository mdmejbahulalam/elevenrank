@extends('account.layout')
@section('page', 'My account')
@section('body')
<h1>Hello, {{ auth()->user()->name }}</h1>
<div class="grid" style="margin:16px 0">
	<div class="card"><h3>Licences</h3><p>{{ $licences->count() }} on this account</p></div>
	<div class="card"><h3>Open tickets</h3><p>{{ $tickets }}</p></div>
</div>
@if ($licences->isEmpty())
	<div class="card"><h3>No licence yet</h3><p>Pro unlocks the audit, rank tracker and more. <a href="/pricing">See pricing</a>.</p></div>
@else
	<h2>Your licences</h2>
	@foreach ($licences as $l)
		<div class="card" style="margin-bottom:12px">
			<code>{{ $l->license_key }}</code>
			<p>{{ $l->activations_count }} / {{ $l->site_limit ?: 'unlimited' }} sites · {{ $l->status === 'active' ? ($l->isExpired() ? 'term ended '.$l->expires->format('j M Y') : ($l->expires ? 'renews or ends '.$l->expires->format('j M Y') : 'no expiry')) : $l->status }}</p>
		</div>
	@endforeach
@endif
@if ($orders->isNotEmpty())
	<h2>Recent orders</h2>
	@foreach ($orders as $o)<p><a href="/account/orders/{{ $o->number }}">{{ $o->number }}</a> · {{ $o->plan->name }} · {{ $o->amount() }} · {{ $o->status }}</p>@endforeach
@endif
@endsection
