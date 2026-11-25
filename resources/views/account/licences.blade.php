@extends('layouts.account')
@section('page', 'Licences')
@section('body')
<h1>Licences</h1>
@forelse ($licences as $l)
	<x-card style="margin-bottom:16px">
		<x-copy-field :value="$l->license_key" label="Copy key" />
		<p class="muted" style="margin:12px 0 0">
			{{ $l->site_limit ?: 'Unlimited' }} {{ $l->site_limit === 1 ? 'site' : 'sites' }} ·
			@if ($l->status !== 'active') {{ $l->status }}
			@elseif ($l->isExpired()) term ended {{ $l->expires->format('j M Y') }}; Pro keeps working, updates stopped
			@elseif ($l->expires) valid until {{ $l->expires->format('j M Y') }}
			@else no expiry @endif
		</p>
		<h3 style="margin:14px 0 6px">Sites</h3>
		@forelse ($l->activations as $a)
			<form method="post" action="/account/licences/{{ $l->id }}/deactivate" class="row" style="margin:4px 0">
				@csrf <input type="hidden" name="site" value="{{ $a->site }}">
				<span>{{ $a->site }}</span><button class="link">Deactivate</button>
			</form>
		@empty
			<p class="muted" style="margin:0">Not activated on any site yet.</p>
		@endforelse
	</x-card>
@empty
	<x-card><x-empty icon="key" title="No licences yet">No licences yet. <a href="/pricing">See pricing</a>.</x-empty></x-card>
@endforelse
@endsection
