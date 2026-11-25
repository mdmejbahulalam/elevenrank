@extends('layouts.auth')
@section('title', 'Create account')
@section('content')
<x-card>
	<h1 style="margin-top:0">Create your account</h1>
	<form method="post" action="/account/register">
		@csrf
		<x-field name="name" label="Name" autocomplete="name" required />
		<x-field name="email" label="Email" type="email" autocomplete="username" required />
		<x-field name="password" label="Password (10 characters or more)" type="password" autocomplete="new-password" required />
		<x-field name="password_confirmation" label="Repeat password" type="password" autocomplete="new-password" required />
		<p class="muted small">By creating an account you agree to the <a href="/terms">terms</a> and <a href="/privacy">privacy policy</a>.</p>
		<p><x-button class="block">Create account</x-button></p>
	</form>
</x-card>
<p>Already registered? <a href="/account/login">Sign in</a>.</p>
@endsection
