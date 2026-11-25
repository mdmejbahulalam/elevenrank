@extends('site.layout')
@section('title', 'Sign in')
@section('content')
<div class="wrap page" style="max-width:440px">
	<h1>Sign in</h1>
	<div class="card">
		<form method="post" action="/account/login">
			@csrf
			<label for="email">Email</label>
			<input id="email" type="email" name="email" value="{{ old('email') }}" autocomplete="username" required autofocus>
			<label for="password">Password</label>
			<input id="password" type="password" name="password" autocomplete="current-password" required>
			@error('email') <p class="err" role="alert">{{ $message }}</p> @enderror
			<p><button class="btn">Sign in</button></p>
		</form>
	</div>
	<p>New here? <a href="/account/register">Create an account</a>.</p>
</div>
@endsection
