@extends('account.layout')
@section('page', 'Licences')
@section('body')
<h1>Licences</h1>
@forelse ($licences as $l)
	<div class="card" style="margin-bottom:16px">
		<p style="margin:0 0 6px"><code id="key-{{ $l->id }}">{{ $l->license_key }}</code>
			<button class="link" type="button" onclick="navigator.clipboard&&navigator.clipboard.writeText(document.getElementById('key-{{ $l->id }}').textContent).then(()=>this.textContent='Copied')">Copy</button></p>
		<p class="muted" style="margin:0">
			{{ $l->site_limit ?: 'Unlimited' }} {{ $l->site_limit === 1 ? 'site' : 'sites' }} ·
			@if ($l->status !== 'active') {{ $l->status }}
			@elseif ($l->isExpired()) term ended {{ $l->expires->format('j M Y') }}; Pro keeps working, updates stopped
			@elseif ($l->expires) valid until {{ $l->expires->format('j M Y') }}
			@else no expiry @endif
		</p>
		<h3 style="margin:14px 0 6px">Sites</h3>
		@forelse ($l->activations as $a)
			<form method="post" action="/account/licences/{{ $l->id }}/deactivate" style="display:flex;gap:12px;align-items:center;margin:4px 0">
				@csrf <input type="hidden" name="site" value="{{ $a->site }}">
				<span>{{ $a->site }}</span><button class="link">Deactivate</button>
			</form>
		@empty
			<p class="muted" style="margin:0">Not activated on any site yet.</p>
		@endforelse
	</div>
@empty
	<p>No licences yet. <a href="/pricing">See pricing</a>.</p>
@endforelse
@endsection
