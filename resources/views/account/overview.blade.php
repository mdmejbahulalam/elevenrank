@extends('layouts.account')
@section('page', 'My account')
@section('body')
<h1>Hello, {{ auth()->user()->name }}</h1>
<p class="muted" style="margin:0 0 24px">Your ElevenRank Pro licence, downloads and support in one place.</p>
<div class="grid" style="margin:16px 0">
	<x-stat label="Licences on this account" :value="$licences->count()" />
	<x-stat label="Open tickets" :value="$tickets" />
</div>
@if ($licences->isNotEmpty())
<x-card title="Install Pro in 3 steps" style="margin-bottom:16px">
	<ol class="checklist">
		<li><a href="/account/downloads">Download Pro</a> and upload it next to the free plugin.</li>
		<li>Open ElevenRank &gt; Pro licence and paste your key.</li>
		<li>@if ($hasSite) <x-badge tone="ok">Done</x-badge> @endif Activate it: your first site appears under <a href="/account/licences">Licences</a>.</li>
	</ol>
</x-card>
@endif
@if ($licences->isEmpty())
	<x-card><x-empty icon="key" title="No licence yet">Pro unlocks the audit, rank tracker and more. <a href="/pricing">See pricing</a>.</x-empty></x-card>
@else
	<h2>Your licences</h2>
	@foreach ($licences as $l)
		<x-card style="margin-bottom:12px">
			<x-copy-field :value="$l->license_key" label="Copy key" />
			<p style="margin-top:12px">
				{{ $l->activations_count }} / {{ $l->site_limit ?: 'unlimited' }} sites ·
				@if ($l->status !== 'active') <x-badge tone="bad">{{ $l->status }}</x-badge>
				@elseif ($l->isExpired()) <x-badge tone="info">term ended {{ $l->expires->format('j M Y') }}</x-badge>
				@elseif ($l->expires) <x-badge tone="ok">renews or ends {{ $l->expires->format('j M Y') }}</x-badge>
				@else <x-badge tone="ok">no expiry</x-badge> @endif
			</p>
		</x-card>
	@endforeach
@endif
@if ($orders->isNotEmpty())
	<h2>Recent orders</h2>
	@foreach ($orders as $o)<p><a href="/account/orders/{{ $o->number }}">{{ $o->number }}</a> · {{ $o->plan->name }} · {{ $o->amount() }} · {{ $o->status }}</p>@endforeach
@endif
@endsection
