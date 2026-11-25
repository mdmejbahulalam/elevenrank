@extends('layouts.auth')
@section('title', 'Choose a new password')
@section('content')
<x-card>
	<h1 style="margin-top:0">Choose a new password</h1>
	<form method="post" action="/account/reset-password">
		@csrf
		<input type="hidden" name="token" value="{{ $token }}">
		<x-field name="email" label="Email" type="email" :value="$email" autocomplete="username" required />
		<x-field name="password" label="New password (10 characters or more)" type="password" autocomplete="new-password" required />
		<x-field name="password_confirmation" label="Repeat new password" type="password" autocomplete="new-password" required />
		<p style="margin-top:20px"><x-button class="block">Change password</x-button></p>
	</form>
</x-card>
@endsection
