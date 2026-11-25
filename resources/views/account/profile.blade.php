@extends('layouts.account')
@section('page', 'Profile')
@section('body')
<h1>Profile</h1>
<div class="card">
	<form method="post" action="/account/profile">
		@csrf
		<label for="name">Name</label>
		<input id="name" type="text" name="name" value="{{ old('name', auth()->user()->name) }}" required>
		@error('name') <p class="err" role="alert">{{ $message }}</p> @enderror
		<label for="email">Email</label>
		<input id="email" type="email" name="email" value="{{ old('email', auth()->user()->email) }}" required>
		@error('email') <p class="err" role="alert">{{ $message }}</p> @enderror
		<h3 style="margin-top:22px">Change password</h3>
		<label for="current_password">Current password</label>
		<input id="current_password" type="password" name="current_password" autocomplete="current-password">
		@error('current_password') <p class="err" role="alert">{{ $message }}</p> @enderror
		<label for="password">New password (10 characters or more)</label>
		<input id="password" type="password" name="password" autocomplete="new-password">
		@error('password') <p class="err" role="alert">{{ $message }}</p> @enderror
		<label for="password_confirmation">Repeat new password</label>
		<input id="password_confirmation" type="password" name="password_confirmation" autocomplete="new-password">
		<p><button class="btn">Save</button></p>
	</form>
</div>
@endsection
