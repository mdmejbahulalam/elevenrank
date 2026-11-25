@extends('admin.layout')
@section('title', $user->name)
@section('content')
<h1>{{ $user->name }}</h1>
<p class="muted">{{ $user->email }} · joined {{ $user->created_at->format('Y-m-d') }}</p>
<div class="card" style="overflow-x:auto"><h2>Licences</h2>
@forelse ($licences as $l)
	<p><code>{{ $l->license_key }}</code> · {{ $l->activations_count }} / {{ $l->site_limit ?: '∞' }} sites · {{ $l->status }} · {{ $l->expires?->format('Y-m-d') ?? 'never' }}</p>
@empty <p class="muted">None.</p> @endforelse
</div>
<div class="card" style="overflow-x:auto"><h2>Orders</h2>
@forelse ($orders as $o)
	<p>{{ $o->number }} · {{ $o->plan->name }} · {{ $o->amount() }} · {{ $o->status }} · {{ $o->created_at->format('Y-m-d') }}</p>
@empty <p class="muted">None.</p> @endforelse
</div>
@endsection
