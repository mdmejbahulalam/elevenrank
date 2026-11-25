@extends('admin.layout')
@section('title', 'Plans')
@section('content')
<h1>Plans</h1>
<p class="muted">Prices are placeholders until you set them. Changes apply to new orders only. 0 sites means unlimited.</p>
@foreach ($plans as $p)
	<div class="card">
		<form method="post" action="/admin/plans/{{ $p->id }}">
			@csrf
			<h2>{{ $p->slug }}</h2>
			<div class="grid">
				<div><label>Name</label><input type="text" name="name" value="{{ $p->name }}" required></div>
				<div><label>Sites</label><input type="number" name="sites" value="{{ $p->sites }}" min="0" required></div>
				<div><label>Price ({{ $p->currency }})</label><input type="number" name="price" value="{{ number_format($p->price_cents / 100, 2, '.', '') }}" step="0.01" min="0" required></div>
				<div><label>Months</label><input type="number" name="months" value="{{ $p->months }}" min="0" required></div>
			</div>
			<label style="display:inline-flex;gap:8px;align-items:center"><input type="checkbox" name="active" value="1" @checked($p->active)> On sale</label>
			<div class="row"><button>Save</button></div>
		</form>
	</div>
@endforeach
@endsection
