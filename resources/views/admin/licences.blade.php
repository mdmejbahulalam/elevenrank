@extends('admin.layout')
@section('title', 'Licences')
@section('content')
<h1>Licences</h1>
@if (session('created')) <p class="ok" role="status">Key created: <code>{{ session('created') }}</code></p> @endif

<div class="card">
	<h2>Create a licence</h2>
	<form method="post" action="/admin/licences">
		@csrf
		<label for="email">Customer email (optional)</label>
		<input id="email" type="email" name="email" value="{{ old('email') }}">
		@error('email') <p class="err" role="alert">{{ $message }}</p> @enderror
		<label for="site_limit">Sites allowed (0 = unlimited)</label>
		<input id="site_limit" type="number" name="site_limit" value="{{ old('site_limit', 1) }}" min="0" required>
		<label for="months">Months valid (0 = no expiry)</label>
		<input id="months" type="number" name="months" value="{{ old('months', 12) }}" min="0" required>
		<div class="row"><button>Create</button></div>
	</form>
</div>

<div class="card" style="overflow-x:auto">
	<h2>All licences</h2>
	@if ($licences->isEmpty())
		<p class="muted">No licences yet.</p>
	@else
	<table>
		<thead><tr><th>Key</th><th>Email</th><th>Sites</th><th>Expires</th><th>Status</th><th></th></tr></thead>
		<tbody>
		@foreach ($licences as $l)
			<tr>
				<td><code>{{ $l->license_key }}</code></td>
				<td>{{ $l->email ?: '—' }}</td>
				<td>{{ $l->activations_count }} / {{ $l->site_limit ?: '∞' }}</td>
				<td>{{ $l->expires ? $l->expires->format('Y-m-d') : 'never' }}</td>
				<td>{{ $l->status }}</td>
				<td>
					<form method="post" action="/admin/licences/{{ $l->id }}/status">
						@csrf
						<input type="hidden" name="status" value="{{ $l->status === 'active' ? 'revoked' : 'active' }}">
						<button class="link">{{ $l->status === 'active' ? 'Revoke' : 'Reinstate' }}</button>
					</form>
					<form method="post" action="/admin/licences/{{ $l->id }}" style="display:flex;gap:6px;margin-top:6px;align-items:center">
						@csrf
						<input type="number" name="extend_months" min="0" max="120" placeholder="+months" style="width:90px" aria-label="Extend by months">
						<input type="number" name="site_limit" min="0" placeholder="sites" style="width:80px" aria-label="Site limit">
						<button class="link">Update</button>
					</form>
				</td>
			</tr>
		@endforeach
		</tbody>
	</table>
	@endif
</div>
@endsection
