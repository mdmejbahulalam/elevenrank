@extends('layouts.admin')
@section('title', 'Licence')
@section('content')
@php $short = substr($licence->license_key, 0, 7).'…'.substr($licence->license_key, -4); $name = ($licence->plan?->name ?? 'ElevenRank Pro').($licence->site_limit ? ', '.$licence->site_limit.' '.str('site')->plural($licence->site_limit) : ''); $n = $licence->activations->count(); @endphp
<div class="a-between" style="align-items:flex-start;margin-bottom:24px"><div><p class="a-eyebrow"><a href="/admin/licences">Licences</a> / <span class="a-mono">{{ $short }}</span></p><p class="a-eyebrow" style="margin-top:0">Licence</p><h1 style="margin:0">{{ $name }}</h1></div>
	<div class="a-row">
		<button type="button" class="a-btn secondary" data-open="extend">Extend term</button>
		@if ($licence->status === 'active')<button type="button" class="a-btn danger" data-open="revoke">Revoke licence</button>
		@else<form method="post" action="/admin/licences/{{ $licence->id }}/status" style="margin:0">@csrf<input type="hidden" name="status" value="active"><button class="a-btn secondary">Reinstate</button></form>@endif
	</div></div>
<div class="h-two">
	<div class="a-stack" style="gap:24px">
		<section class="a-card"><h2>Activated sites</h2>
			@if ($licence->activations->isEmpty())<p class="a-muted">Not activated on any site.</p>@else
			<x-table :headings="['Site', 'Activated', 'Last check', 'Action']">
			@foreach ($licence->activations as $a)<tr><td class="a-mono">{{ $a->site }}</td><td>{{ $a->created_at->format('j M Y') }}</td><td>{{ $a->updated_at->isToday() ? 'Today' : ($a->updated_at->isYesterday() ? 'Yesterday' : $a->updated_at->format('j M Y')) }}</td><td><form method="post" action="/admin/licences/{{ $licence->id }}/deactivate" style="margin:0">@csrf<input type="hidden" name="site" value="{{ $a->site }}"><button class="a-btn secondary sm">Deactivate</button></form></td></tr>@endforeach
			</x-table>@endif
		</section>
		<section class="a-card"><h2>History</h2>
			<ul class="h-hist">
			@foreach ($licence->activations->sortByDesc('created_at') as $a)<li><strong>Site activated</strong><br>{{ $a->site }} · {{ $a->created_at->format('j M Y') }}</li>@endforeach
			<li><strong>Licence issued</strong><br>@if ($order)Order {{ $order->number }} · @endif{{ $licence->created_at->format('j M Y') }}</li>
			</ul>
		</section>
	</div>
	<aside class="a-card"><h2>Summary</h2>
		<dl class="h-kv"><dt>Customer</dt><dd>@if ($licence->user)<a href="/admin/customers/{{ $licence->user_id }}">{{ $licence->user->name }}</a>@else{{ $licence->email ?: '—' }}@endif</dd><dt>Status</dt><dd><x-status :value="$licence->status === 'active' && $licence->isExpired() ? 'expired' : $licence->status" /></dd><dt>Term</dt><dd>{{ $licence->created_at->format('j M Y') }} to {{ $licence->expires?->format('j M Y') ?? 'no end' }}</dd><dt>Sites</dt><dd>{{ $n }} of {{ $licence->site_limit ?: '∞' }}</dd>@if ($order)<dt>Order</dt><dd><a href="/admin/orders/{{ $order->number }}">{{ $order->number }}</a></dd>@endif<dt>Key</dt><dd class="a-mono" style="word-break:break-all">{{ $licence->license_key }}</dd></dl>
	</aside>
</div>
<x-modal id="revoke" title="Revoke this licence?">
	<p class="a-muted">Pro stops being valid on {{ $n === 1 ? 'the site' : ($n === 0 ? 'any site' : 'all '.$n.' sites') }} at the next check. The customer is emailed. This is recorded in the audit log.</p>
	<form method="post" action="/admin/licences/{{ $licence->id }}/status">@csrf<input type="hidden" name="status" value="revoked">
		<label class="a-label" for="rv">Reason (required)</label><textarea id="rv" name="reason" rows="4" minlength="3" maxlength="255" required placeholder="Why is this being revoked?"></textarea>
		<p class="a-row" style="margin:20px 0 0;justify-content:flex-end"><button type="button" class="a-btn secondary" data-close>Cancel</button><button class="a-btn danger">Revoke licence</button></p></form>
</x-modal>
<x-modal id="extend" title="Extend term">
	<form method="post" action="/admin/licences/{{ $licence->id }}">@csrf
		<label class="a-label" for="em" style="margin-top:0">Add months</label><input id="em" type="number" name="extend_months" min="1" max="120" value="12" required>
		<label class="a-label" for="sl">Sites allowed (0 = unlimited)</label><input id="sl" type="number" name="site_limit" min="0" value="{{ $licence->site_limit }}">
		<p class="a-row" style="margin:20px 0 0;justify-content:flex-end"><button type="button" class="a-btn secondary" data-close>Cancel</button><button class="a-btn primary">Save</button></p></form>
</x-modal>
@endsection
