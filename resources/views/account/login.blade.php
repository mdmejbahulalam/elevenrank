@extends('layouts.auth')
@section('title', 'Sign in')
@section('content')
<x-card>
	<h1 style="margin-top:0">Sign in</h1>
	<form method="post" action="/account/login">
		@csrf
		<x-field name="email" label="Email" type="email" autocomplete="username" required />
		<x-field name="password" label="Password" type="password" autocomplete="current-password" required />
		<p style="margin-top:20px"><x-button class="block">Sign in</x-button></p>
	</form>
</x-card>
<p>New here? <a href="/account/register">Create an account</a>.</p>
@endsection
