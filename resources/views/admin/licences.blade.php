@extends('layouts.admin')
@section('title', 'Licences')
@section('content')
<h1>Licences</h1>
@if (session('created')) <x-alert tone="ok">Key created: <code>{{ session('created') }}</code></x-alert> @endif

<x-card title="Create a licence">
	<form method="post" action="/admin/licences">
		@csrf
		<x-field name="email" type="email" label="Customer email (optional)" />
		<x-field name="site_limit" type="number" label="Sites allowed (0 = unlimited)" :value="1" min="0" required />
		<x-field name="months" type="number" label="Months valid (0 = no expiry)" :value="12" min="0" required />
		<div class="row" style="margin-top:16px"><x-button>Create</x-button></div>
	</form>
</x-card>

<h2 style="margin-top:24px">All licences</h2>
@if ($licences->isEmpty())
<x-card><x-empty icon="key" title="No licences yet.">Created and purchased keys appear here.</x-empty></x-card>
@else
<x-table :headings="['Key', 'Email', 'Sites', 'Expires', 'Status', 'Actions']">
@foreach ($licences as $l)
	<tr>
		<td><code>{{ $l->license_key }}</code></td>
		<td>{{ $l->email ?: '—' }}</td>
		<td>{{ $l->activations_count }} / {{ $l->site_limit ?: '∞' }}</td>
		<td>{{ $l->expires ? $l->expires->format('Y-m-d') : 'never' }}</td>
		<td><x-status :value="$l->status" /></td>
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
</x-table>
@endif
@endsection
