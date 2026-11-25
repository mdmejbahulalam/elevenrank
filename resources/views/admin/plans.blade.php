@extends('layouts.admin')
@section('title', 'Plans')
@section('content')
<h1>Plans</h1>
<p class="muted">Prices are placeholders until you set them. Changes apply to new orders only. 0 sites means unlimited.</p>
@foreach ($plans as $p)
	<x-card style="margin-bottom:16px">
		<form method="post" action="/admin/plans/{{ $p->id }}">
			@csrf
			<h2 style="margin-top:0">{{ $p->slug }} @if ($p->active)<x-badge tone="ok">On sale</x-badge>@else<x-badge>Off sale</x-badge>@endif</h2>
			<div class="grid">
				<div><label for="name-{{ $p->id }}">Name</label><input id="name-{{ $p->id }}" type="text" name="name" value="{{ $p->name }}" required></div>
				<div><label for="sites-{{ $p->id }}">Sites</label><input id="sites-{{ $p->id }}" type="number" name="sites" value="{{ $p->sites }}" min="0" required></div>
				<div><label for="price-{{ $p->id }}">Price ({{ $p->currency }})</label><input id="price-{{ $p->id }}" type="number" name="price" value="{{ number_format($p->price_cents / 100, 2, '.', '') }}" step="0.01" min="0" required></div>
				<div><label for="months-{{ $p->id }}">Months</label><input id="months-{{ $p->id }}" type="number" name="months" value="{{ $p->months }}" min="0" required></div>
			</div>
			<label style="display:inline-flex;gap:8px;align-items:center"><input type="checkbox" name="active" value="1" @checked($p->active)> On sale</label>
			<div class="row" style="margin-top:16px"><x-button>Save</x-button></div>
		</form>
	</x-card>
@endforeach
@endsection
