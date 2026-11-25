@extends('layouts.admin')
@section('title', 'Licences')
@section('content')
<p class="a-eyebrow">Licences</p><h1 style="margin:0 0 24px">Licences</h1>
@if (session('created')) <div class="a-alert ok" style="margin-bottom:16px"><div>Key created: <code>{{ session('created') }}</code></div></div> @endif
<form method="get" class="h-filters">
	<input type="text" name="q" value="{{ $q }}" placeholder="Search key or email" aria-label="Search key or email">
	<select name="status" aria-label="Status"><option value="">All statuses</option>@foreach (['active' => 'Active', 'expired' => 'Term ended', 'revoked' => 'Revoked'] as $v => $label)<option value="{{ $v }}" @selected($status === $v)>{{ $label }}</option>@endforeach</select>
	<select name="ends" aria-label="Term"><option value="">Any end date</option><option value="30" @selected($ends === '30')>Ends in 30 days</option></select>
	<button class="a-btn secondary">Filter</button>
</form>
@if ($licences->isEmpty())
<x-card><x-empty icon="key" title="No licences.">Created and purchased keys appear here.</x-empty></x-card>
@else
<x-table :headings="['Key', 'Customer', 'Plan', 'Sites', 'Ends', 'Status']">
@foreach ($licences as $l)
	@php $soon = $l->status === 'active' && $l->expires && ! $l->isExpired() && $l->expires->lte(now()->addDays(30)); @endphp
	<tr>
		<td><a class="a-mono" href="/admin/licences/{{ $l->id }}">{{ substr($l->license_key, 0, 7) }}…{{ substr($l->license_key, -4) }}</a></td>
		<td>{{ $l->user?->name ?? ($l->email ?: '—') }}</td>
		<td>{{ $l->plan?->name ?? '—' }}</td>
		<td>{{ $l->activations_count }} of {{ $l->site_limit ?: '∞' }}</td>
		<td>{{ $l->expires ? $l->expires->format('j M Y') : '—' }}</td>
		<td>@if ($soon)<span class="a-pill warn">Ends in {{ (int) now()->startOfDay()->diffInDays($l->expires->copy()->startOfDay()) }} days</span>@else<x-status :value="$l->status === 'active' && $l->isExpired() ? 'expired' : $l->status" />@endif</td>
	</tr>
@endforeach
</x-table>
@endif
<details class="a-card" style="margin-top:24px"><summary class="a-h3" style="cursor:pointer">Create a licence by hand</summary>
	<form method="post" action="/admin/licences" style="margin-top:12px;max-width:520px">@csrf
		<x-field name="email" type="email" label="Customer email (optional)" />
		<x-field name="site_limit" type="number" label="Sites allowed (0 = unlimited)" :value="1" min="0" required />
		<x-field name="months" type="number" label="Months valid (0 = no expiry)" :value="12" min="0" required />
		<p style="margin:16px 0 0"><button class="a-btn primary">Create</button></p>
	</form>
</details>
@endsection
