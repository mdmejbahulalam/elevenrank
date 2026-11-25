@extends('layouts.account')
@section('page', 'Licences')
@section('body')
<p class="a-eyebrow">Licences</p>
<h1>Your licence and sites</h1>
<p class="a-sub">Free a slot by deactivating a site you no longer use. Staging copies on local or .test domains do not use a slot.</p>
<div class="a-stack">
@forelse ($licences as $l)
	<section class="a-card">
		<div class="a-between">
			<h2 style="margin:0">{{ $l->label ?: 'Licence key' }}</h2>
			@if ($l->status !== 'active')<span class="a-pill bad">{{ ucfirst($l->status) }}</span>
			@elseif ($l->isExpired())<span class="a-pill info">Term ended</span>
			@else<span class="a-pill ok">Active</span>@endif
		</div>
		<p class="a-small a-muted" style="margin:8px 0 12px">Paste this under ElevenRank, Licence in your WordPress admin. Keep it private.</p>
		<x-copy-field :value="$l->license_key" label="Copy key" />
		<p class="a-small a-muted" style="margin:12px 0 0">
			{{ $l->site_limit ?: 'Unlimited' }} {{ $l->site_limit === 1 ? 'site' : 'sites' }} ·
			@if ($l->status !== 'active') {{ $l->status }}
			@elseif ($l->isExpired()) term ended {{ $l->expires->format('j M Y') }}; Pro keeps working, updates stopped
			@elseif ($l->expires) updates and support until {{ $l->expires->format('j M Y') }}
			@else no expiry @endif
		</p>
		<form method="post" action="/account/licences/{{ $l->id }}/label" class="a-row" style="margin-top:14px">
			@csrf
			<label for="label-{{ $l->id }}" class="a-small" style="margin:0">Label</label>
			<input id="label-{{ $l->id }}" type="text" name="label" value="{{ $l->label }}" maxlength="80" style="max-width:240px" placeholder="For example, Client site">
			<button class="a-btn secondary sm">Save label</button>
		</form>
	</section>
	<section>
		<div class="a-between" style="margin-bottom:12px"><h2 style="margin:0">Activated sites</h2><span class="a-small a-muted">{{ $l->activations->count() }} of {{ $l->site_limit ?: 'unlimited' }} used</span></div>
		<div class="a-table-wrap">
			<table class="a-table">
				<thead><tr><th>Site</th><th>Status</th><th style="text-align:right">Action</th></tr></thead>
				<tbody>
				@forelse ($l->activations as $a)
					<tr><td>{{ $a->site }}</td><td><span class="a-pill ok">Active</span></td>
					<td style="text-align:right"><form method="post" action="/account/licences/{{ $l->id }}/deactivate" style="margin:0">@csrf<input type="hidden" name="site" value="{{ $a->site }}"><button class="a-btn secondary sm">Deactivate</button></form></td></tr>
				@empty
					<tr><td colspan="3" class="a-muted" style="text-align:center">Not activated on any site yet.</td></tr>
				@endforelse
				</tbody>
			</table>
		</div>
	</section>
@empty
	<section class="a-card"><x-empty icon="key" title="No licences yet">No licences yet. <a href="/pricing">See pricing</a>.</x-empty></section>
@endforelse
</div>
@endsection
