@extends('layouts.account')
@section('page', 'Profile')
@section('body')
<p class="a-eyebrow">Profile</p>
<h1>Profile and security</h1>
<p class="a-sub">Your details and how you sign in.</p>
<div class="a-stack">
	<section class="a-card">
		<h2>Your details</h2>
		<form method="post" action="/account/profile">
			@csrf
			<x-field name="name" label="Name" :value="auth()->user()->name" required />
			<x-field name="email" type="email" label="Email" :value="auth()->user()->email" required />
			<x-field name="billing_address" label="Billing address" :value="auth()->user()->billing_address" maxlength="190" placeholder="Used on invoices" />
			<p style="margin:20px 0 0"><button class="a-btn primary">Save changes</button></p>
		</form>
	</section>
	<section class="a-card">
		<h2>Password</h2>
		<form method="post" action="/account/password">
			@csrf
			<x-field name="current_password" type="password" label="Current password" autocomplete="current-password" />
			<x-field name="password" type="password" label="New password" autocomplete="new-password" help="Use at least 10 characters." />
			<x-field name="password_confirmation" type="password" label="Confirm new password" autocomplete="new-password" />
			<p style="margin:20px 0 0"><button class="a-btn primary">Change password</button></p>
		</form>
	</section>
	<section class="a-card">
		<h2>Your data</h2>
		<p class="a-muted" style="margin:0 0 16px">Download everything we hold about your account as a file.</p>
		<a class="a-btn secondary" href="/account/export">Export my data</a>
	</section>
	<section class="a-card">
		<h2>Delete account</h2>
		<p class="a-muted" style="margin:0 0 16px">Deleting removes your sign-in and support history. Active licences must be revoked first. This cannot be undone.</p>
		@error('delete')<div class="a-alert bad" role="alert" style="margin-bottom:16px"><div>{{ $message }}</div></div>@enderror
		<button type="button" class="a-btn danger" data-open="delete-account">Delete my account</button>
	</section>
</div>
<x-modal id="delete-account" title="Delete your account?">
	<p class="a-muted">This removes your sign-in, orders and support history. Enter your password to confirm.</p>
	<form method="post" action="/account/profile/delete">
		@csrf
		<x-field name="delete_password" type="password" label="Password" autocomplete="current-password" required />
		<p class="a-row" style="margin:20px 0 0;justify-content:flex-end"><button type="button" class="a-btn secondary" data-close>Keep my account</button><button class="a-btn danger">Delete account</button></p>
	</form>
</x-modal>
@endsection
