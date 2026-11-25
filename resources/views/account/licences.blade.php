@extends('layouts.account')
@section('page', 'Licences')
@section('body')
<h1>Licences</h1>
@forelse ($licences as $l)
	<x-card style="margin-bottom:16px">
		@if ($l->label)<h3 style="margin:0 0 8px">{{ $l->label }}</h3>@endif
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
		<form method="post" action="/account/licences/{{ $l->id }}/label" class="row" style="margin-top:14px">
			@csrf
			<label for="label-{{ $l->id }}" style="margin:0">Label</label>
			<input id="label-{{ $l->id }}" type="text" name="label" value="{{ $l->label }}" maxlength="80" style="max-width:240px" placeholder="For example, Client site">
			<x-button variant="secondary" class="small">Save label</x-button>
		</form>
	</x-card>
@empty
	<x-card><x-empty icon="key" title="No licences yet">No licences yet. <a href="/pricing">See pricing</a>.</x-empty></x-card>
@endforelse
@endsection
