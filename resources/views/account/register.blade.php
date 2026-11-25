@extends('site.layout')
@section('title', 'Create account')
@section('content')
<div class="wrap page" style="max-width:440px">
	<h1>Create your account</h1>
	<div class="card">
		<form method="post" action="/account/register">
			@csrf
			<label for="name">Name</label>
			<input id="name" type="text" name="name" value="{{ old('name') }}" autocomplete="name" required autofocus>
			@error('name') <p class="err" role="alert">{{ $message }}</p> @enderror
			<label for="email">Email</label>
			<input id="email" type="email" name="email" value="{{ old('email') }}" autocomplete="username" required>
			@error('email') <p class="err" role="alert">{{ $message }}</p> @enderror
			<label for="password">Password (10 characters or more)</label>
			<input id="password" type="password" name="password" autocomplete="new-password" required>
			@error('password') <p class="err" role="alert">{{ $message }}</p> @enderror
			<label for="password_confirmation">Repeat password</label>
			<input id="password_confirmation" type="password" name="password_confirmation" autocomplete="new-password" required>
			<p class="muted">By creating an account you agree to the <a href="/terms">terms</a> and <a href="/privacy">privacy policy</a>.</p>
			<p><button class="btn">Create account</button></p>
		</form>
	</div>
	<p>Already registered? <a href="/account/login">Sign in</a>.</p>
</div>
@endsection
