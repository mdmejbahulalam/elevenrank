@extends('layouts.account')
@section('page', 'Licences')
@section('body')
@php $first = $licences->first(); @endphp
<p class="a-eyebrow">Licences</p>
<h1>Your licence and sites</h1>
<p class="a-sub">@if ($first && $first->site_limit)Activate Pro on up to {{ $first->site_limit }} sites. @endif Free a slot by deactivating a site you no longer use.</p>
<div class="a-stack">
@forelse ($licences as $l)
	@php
		$plan = ($l->plan?->name ?? 'ElevenRank Pro').($l->site_limit ? ', '.$l->site_limit.' '.str('site')->plural($l->site_limit) : '');
		$free = $l->site_limit ? max(0, $l->site_limit - $l->activations->count()) : 0;
	@endphp
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
			{{ $plan }} ·
			@if ($l->status !== 'active') {{ $l->status }}
			@elseif ($l->isExpired()) term ended {{ $l->expires->format('j M Y') }}; Pro keeps working, updates stopped
			@elseif ($l->expires) updates and support until {{ $l->expires->format('j M Y') }}
			@else no expiry @endif
		</p>
	</section>
	<section>
		<div class="a-between" style="margin-bottom:12px"><h2 style="margin:0">Activated sites</h2><span class="a-small a-muted">{{ $l->activations->count() }} of {{ $l->site_limit ?: 'unlimited' }} used</span></div>
		<div class="a-table-wrap">
			<table class="a-table">
				<thead><tr><th>Site</th><th>Activated</th><th>Last check</th><th>Status</th><th style="text-align:right">Action</th></tr></thead>
				<tbody>
				@foreach ($l->activations as $a)
					@php $seen = $a->updated_at; $when = $seen->isToday() ? 'Today' : ($seen->isYesterday() ? 'Yesterday' : $seen->format('j M Y')); @endphp
					<tr>
						<td class="a-mono">{{ $a->site }}</td><td>{{ $a->created_at->format('j M Y') }}</td><td>{{ $when }}</td><td><span class="a-pill ok">Active</span></td>
						<td style="text-align:right"><button type="button" class="a-btn secondary sm" data-open="deact-{{ $a->id }}">Deactivate</button></td>
					</tr>
				@endforeach
				@for ($i = 0; $i < $free; $i++)
					<tr><td>Free slot</td><td class="a-muted">—</td><td class="a-muted">—</td><td><span class="a-pill">Available</span></td><td class="a-muted" style="text-align:right">—</td></tr>
				@endfor
				@if ($l->activations->isEmpty() && ! $l->site_limit)
					<tr><td colspan="5" class="a-muted" style="text-align:center">Not activated on any site yet.</td></tr>
				@endif
				</tbody>
			</table>
		</div>
		<p class="a-small a-muted" style="margin:12px 0 0">Staging copies on local or .test domains do not use a slot.</p>
	</section>
	@foreach ($l->activations as $a)
		<x-modal id="deact-{{ $a->id }}" title="Deactivate {{ $a->site }}?">
			<p class="a-muted">Pro stops running on this site and frees one slot. You can activate it again later with the same key. Your site content is not touched.</p>
			<form method="post" action="/account/licences/{{ $l->id }}/deactivate" class="a-row" style="margin-top:20px;justify-content:flex-end">@csrf<input type="hidden" name="site" value="{{ $a->site }}">
				<button type="button" class="a-btn secondary" data-close>Keep active</button><button class="a-btn danger">Deactivate site</button></form>
		</x-modal>
	@endforeach
@empty
	<section class="a-card"><x-empty icon="key" title="No licences yet">No licences yet. <a href="/pricing">See pricing</a>.</x-empty></section>
@endforelse
@if ($first && $first->plan && $first->plan->slug !== 'studio' && $first->status === 'active')
	<div class="a-alert info" role="note"><div><strong>Need more sites?</strong> Upgrade to Studio (10 sites) and pay only the difference. Prices are placeholders.</div></div>
@endif
</div>
@endsection
