@extends('layouts.auth')
@section('title', 'Reset password')
@section('content')
<x-card>
	<h1 style="margin-top:0">Reset your password</h1>
	<p class="muted">Enter your account email and we will send a link.</p>
	<form method="post" action="/account/forgot-password">
		@csrf
		<x-field name="email" label="Email" type="email" autocomplete="username" required />
		<p style="margin-top:20px"><x-button class="block">Send reset link</x-button></p>
	</form>
</x-card>
<p><a href="/account/login">Back to sign in</a></p>
@endsection
