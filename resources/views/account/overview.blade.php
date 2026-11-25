@extends('layouts.account')
@section('page', 'My account')
@section('body')
@php
$first = $licences->first();
$ended = $first && $first->status === 'active' && $first->isExpired();
$revoked = $first && $first->status !== 'active';
$pct = $first && $first->site_limit ? min(100, round($first->activations_count / $first->site_limit * 100)) : 0;
$soon = $first && $first->status === "active" && $first->expires && ! $first->isExpired() && $first->expires->lte(now()->addDays(30));
@endphp
<p class="a-eyebrow">Overview</p>
<h1>Welcome back, {{ strtok(auth()->user()->name, " ") }}</h1>
<p class="a-sub">Your ElevenRank Pro licence, downloads and support in one place.</p>
<div class="a-stack">
	@if ($first && ! $hasSite && ! $revoked && ! $ended && ! $soon)
		<div class="a-alert info" role="status"><div><strong>Your licence is ready.</strong> Download Pro, install it, then paste your key to activate.</div></div>
	@elseif ($soon)
		<div class="a-alert warn" role="status"><div><strong>Your term ends soon.</strong> Pro keeps working either way. Renew to keep updates and support.</div></div>
	@elseif ($ended)
		<div class="a-alert info" role="status"><div><strong>Pro keeps working on your sites.</strong> Updates and support have stopped. Renew to get them back.</div></div>
	@elseif ($revoked)
		<div class="a-alert bad" role="status"><div><strong>This licence was revoked after a refund.</strong> Pro is no longer active on your sites. <a href="/account/tickets">Contact support</a> if this is a mistake.</div></div>
	@endif

	@if ($licences->isEmpty())
		<section class="a-card"><x-empty icon="key" title="No licence yet">Pro unlocks the audit, rank tracker and more. <a href="/pricing">See pricing</a>.</x-empty></section>
	@else
		@foreach ($licences as $l)
		<section class="a-card" aria-label="Licence">
			<div class="a-between">
				<div><p class="a-eyebrow">Licence</p><h2 style="margin:0">{{ $l->label ?: (($l->plan?->name ?? "ElevenRank Pro").($l->site_limit ? ", ".$l->site_limit." ".str('site')->plural($l->site_limit) : "")) }}</h2></div>
				@if ($l->status !== 'active')<span class="a-pill bad">Revoked</span>
				@elseif ($l->isExpired())<span class="a-pill info">Term ended</span>
				@elseif ($l->expires && $l->expires->lte(now()->addDays(30)))<span class="a-pill warn">Ends in {{ max(0, (int) now()->startOfDay()->diffInDays($l->expires->copy()->startOfDay())) }} days</span>
				@elseif ($l->activations_count === 0)<span class="a-pill info">Ready to activate</span>
				@else<span class="a-pill ok">Active</span>@endif
			</div>
			<div style="margin-top:16px"><x-copy-field :value="$l->license_key" label="Copy key" /></div>
			<div class="a-grid" style="margin-top:20px">
				<div>
					<p class="a-small a-muted" style="margin:0 0 6px">Sites in use</p>
					<p class="a-h3" style="margin:0 0 8px">{{ $l->activations_count }} of {{ $l->site_limit ?: 'unlimited' }} sites</p>
					<div class="a-meter" role="img" aria-label="{{ $l->activations_count }} of {{ $l->site_limit ?: 'unlimited' }} sites"><span style="width:{{ $l->site_limit ? min(100, round($l->activations_count / $l->site_limit * 100)) : 0 }}%"></span></div>
				</div>
				<div>
					<p class="a-small a-muted" style="margin:0 0 6px">Updates and support</p>
					<p class="a-h3" style="margin:0">@if (! $l->expires) No expiry @elseif ($l->isExpired()) Ended {{ $l->expires->format('j M Y') }} @elseif ($l->expires->lte(now()->addDays(30))) Ends {{ $l->expires->format("j M Y") }} @else Until {{ $l->expires->format("j M Y") }} @endif</p>
				</div>
			</div>
			<div class="a-row" style="margin-top:20px">
				@if ($l->status === 'active')<a class="a-btn primary" href="/account/downloads">Download Pro{{ $version ? " ".$version : "" }}</a>@endif
				<a class="a-btn secondary" href="/account/licences">Manage sites</a>
				@if ($l->status !== 'active' || $l->isExpired() || ($l->expires && $l->expires->lte(now()->addDays(30))))<a class="a-btn secondary" href="/pricing">Renew licence</a>@endif
			</div>
		</section>
		@endforeach

		<section class="a-card" aria-labelledby="start">
			<h2 id="start">Get started</h2>
			<div class="a-check"><span class="a-dot done" aria-hidden="true">✓</span><div><p class="a-h3" style="margin:0">Create your account</p><p class="a-small a-muted" style="margin:0">Done.</p></div></div>
			<div class="a-check"><span class="a-dot @if($hasSite) done @endif" aria-hidden="true">@if($hasSite)✓@endif</span><div><p class="a-h3" style="margin:0">Install Pro on your site</p><p class="a-small a-muted" style="margin:0">Upload the zip under Plugins, Add New. Free ElevenRank must be active too.</p></div></div>
			<div class="a-check"><span class="a-dot @if($hasSite) done @endif" aria-hidden="true">@if($hasSite)✓@endif</span><div><p class="a-h3" style="margin:0">Activate with your key</p><p class="a-small a-muted" style="margin:0">Paste the key under ElevenRank, Licence.</p></div></div>
		</section>
	@endif

	<div class="a-grid">
		<section class="a-card"><h2 class="a-h3">Latest release</h2>@if ($version)<p class="a-small a-muted" style="margin:4px 0 12px">Pro {{ $version }}</p>@else<p class="a-small a-muted" style="margin:4px 0 12px">No release published yet.</p>@endif<a class="a-btn ghost sm" href="/account/downloads" style="padding-left:0">See downloads</a></section>
		<section class="a-card"><h2 class="a-h3">Support</h2><p class="a-small a-muted" style="margin:4px 0 12px">{{ $tickets ? $tickets.' open '.\Illuminate\Support\Str::plural('ticket', $tickets).'.' : 'No open tickets.' }}</p><a class="a-btn secondary sm" href="/account/tickets">Open a ticket</a></section>
		<section class="a-card">
			<h2 class="a-h3">Last order</h2>
			@if ($orders->isNotEmpty())@php $o = $orders->first(); @endphp
				<p class="a-small a-muted" style="margin:4px 0 12px">{{ $o->number }} · {{ $o->plan->name }}{{ $o->plan->sites ? ", ".$o->plan->sites." sites" : "" }} · {{ $o->status }}</p><a class="a-btn ghost sm" href="/account/orders/{{ $o->number }}" style="padding-left:0">View invoice</a>
			@else<p class="a-small a-muted" style="margin:4px 0 0">No orders yet.</p>@endif
		</section>
	</div>
</div>
@endsection
