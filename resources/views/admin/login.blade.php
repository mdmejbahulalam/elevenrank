@extends('layouts.auth')
@section('suffix', 'HQ')
@section('title', 'Sign in')
@section('content')
<x-card>
	<h1 style="margin-top:0">Staff sign in</h1>
	<form method="post" action="/admin/login">
		@csrf
		<x-field name="email" label="Email" type="email" autocomplete="username" required />
		<x-field name="password" label="Password" type="password" autocomplete="current-password" placeholder="Your password" required />
		<x-field name="code" label="Two-step code" inputmode="numeric" autocomplete="one-time-code" placeholder="6 digits" />
		<p style="margin-top:20px"><x-button class="block">Sign in</x-button></p>
	</form>
</x-card>
<p class="a-muted" style="text-align:center;margin-top:16px">Customers sign in on the main site.</p>
@endsection
