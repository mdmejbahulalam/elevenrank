@extends('layouts.admin')
@section('title', 'Plans')
@section('content')
<div class="a-between" style="align-items:flex-start;margin-bottom:24px"><div><p class="a-eyebrow">Plans</p><h1 style="margin:0">Plans and prices</h1></div><button type="button" class="a-btn secondary" data-open="plan-new">Add plan</button></div>
<x-table :headings="['Plan', 'Sites', 'Term', 'Price', 'Status', '']" :right="[3]">
@foreach ($plans as $p)
	<tr><td>{{ $p->name }}</td><td>{{ $p->sites ?: '∞' }}</td><td>{{ $p->months ? $p->months.' months' : 'No end' }}</td><td>{{ $p->priceFull() }}</td>
		<td>@if ($p->active)<span class="a-pill ok">On sale</span>@else<span class="a-pill">Off sale</span>@endif</td>
		<td><button type="button" class="a-btn secondary sm" data-open="plan-{{ $p->id }}">Edit</button></td></tr>
@endforeach
</x-table>
<p class="a-muted" style="margin-top:8px">Changing a price here does not change past orders. 0 sites means unlimited. Finance and super admin only.</p>
@foreach ($plans as $p)
<x-modal id="plan-{{ $p->id }}" title="Edit {{ $p->name }}">
	<form method="post" action="/admin/plans/{{ $p->id }}">@csrf
		<label class="a-label" for="n{{ $p->id }}" style="margin-top:0">Name</label><input id="n{{ $p->id }}" name="name" value="{{ $p->name }}" required>
		<label class="a-label" for="s{{ $p->id }}">Sites (0 = unlimited)</label><input id="s{{ $p->id }}" type="number" name="sites" value="{{ $p->sites }}" min="0" required>
		<label class="a-label" for="p{{ $p->id }}">Price ({{ $p->currency }})</label><input id="p{{ $p->id }}" type="number" name="price" value="{{ number_format($p->price_cents / 100, 2, '.', '') }}" step="0.01" min="0" required>
		<label class="a-label" for="m{{ $p->id }}">Months</label><input id="m{{ $p->id }}" type="number" name="months" value="{{ $p->months }}" min="0" required>
		<label style="display:flex;gap:8px;align-items:center;margin-top:16px"><input type="checkbox" name="active" value="1" style="width:auto" @checked($p->active)> On sale</label>
		<p class="a-row" style="margin:20px 0 0;justify-content:flex-end"><button type="button" class="a-btn secondary" data-close>Cancel</button><button class="a-btn primary">Save</button></p>
	</form>
</x-modal>
@endforeach
<x-modal id="plan-new" title="Add plan">
	<form method="post" action="/admin/plans">@csrf
		<label class="a-label" for="nn" style="margin-top:0">Name</label><input id="nn" name="name" required>
		<label class="a-label" for="ns">Sites (0 = unlimited)</label><input id="ns" type="number" name="sites" value="1" min="0" required>
		<label class="a-label" for="np">Price (USD)</label><input id="np" type="number" name="price" value="0" step="0.01" min="0" required>
		<label class="a-label" for="nm">Months</label><input id="nm" type="number" name="months" value="12" min="0" required>
		<label style="display:flex;gap:8px;align-items:center;margin-top:16px"><input type="checkbox" name="active" value="1" style="width:auto"> On sale</label>
		<p class="a-row" style="margin:20px 0 0;justify-content:flex-end"><button type="button" class="a-btn secondary" data-close>Cancel</button><button class="a-btn primary">Add plan</button></p>
	</form>
</x-modal>
@endsection
