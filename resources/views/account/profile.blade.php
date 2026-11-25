@extends('layouts.account')
@section('page', 'Profile')
@section('body')
<h1>Profile</h1>
<x-card>
	<form method="post" action="/account/profile">
		@csrf
		<x-field name="name" label="Name" :value="auth()->user()->name" required />
		<x-field name="email" type="email" label="Email" :value="auth()->user()->email" required />
		<h3 style="margin-top:22px">Change password</h3>
		<x-field name="current_password" type="password" label="Current password" autocomplete="current-password" />
		<x-field name="password" type="password" label="New password (10 characters or more)" autocomplete="new-password" />
		<x-field name="password_confirmation" type="password" label="Repeat new password" autocomplete="new-password" />
		<x-button style="margin-top:20px">Save</x-button>
	</form>
	<h3 style="margin-top:28px">Your data</h3>
	<p class="muted">Download everything we hold about your account as a file.</p>
	<p style="margin-top:12px"><x-button href="/account/export" variant="secondary">Export my data</x-button></p>
</x-card>
@endsection
