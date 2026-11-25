@extends('layouts.admin')
@section('title', $user->name)
@section('content')
<h1>{{ $user->name }}</h1>
<p class="muted">{{ $user->email }} · joined {{ $user->created_at->format('Y-m-d') }}</p>
<x-card title="Licences">
@forelse ($licences as $l)
	<p><code>{{ $l->license_key }}</code> · {{ $l->activations_count }} / {{ $l->site_limit ?: '∞' }} sites · <x-status :value="$l->status" /> · {{ $l->expires?->format('Y-m-d') ?? 'never' }}</p>
@empty <p class="muted">None.</p> @endforelse
</x-card>
<x-card title="Orders" style="margin-top:16px">
@forelse ($orders as $o)
	<p>{{ $o->number }} · {{ $o->plan->name }} · {{ $o->amount() }} · <x-status :value="$o->status" /> · {{ $o->created_at->format('Y-m-d') }}</p>
@empty <p class="muted">None.</p> @endforelse
</x-card>
@endsection
