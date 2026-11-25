@extends('layouts.account')
@section('page', 'Profile')
@section('body')
<p class="a-eyebrow">Profile</p>
<h1>Profile and security</h1>
<p class="a-sub">Your details and how you sign in.</p>
<form method="post" action="/account/profile" class="a-stack">
	@csrf
	<section class="a-card">
		<h2>Your details</h2>
		<x-field name="name" label="Name" :value="auth()->user()->name" required />
		<x-field name="email" type="email" label="Email" :value="auth()->user()->email" required />
	</section>
	<section class="a-card">
		<h2>Password</h2>
		<x-field name="current_password" type="password" label="Current password" autocomplete="current-password" />
		<x-field name="password" type="password" label="New password (10 characters or more)" autocomplete="new-password" />
		<x-field name="password_confirmation" type="password" label="Repeat new password" autocomplete="new-password" />
	</section>
	<div class="a-row"><button class="a-btn primary">Save changes</button></div>
</form>
<section class="a-card" style="margin-top:24px">
	<h2>Your data</h2>
	<p class="a-muted" style="margin:0 0 16px">Download everything we hold about your account as a file.</p>
	<a class="a-btn secondary" href="/account/export">Export my data</a>
</section>
@endsection
