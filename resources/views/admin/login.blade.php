@extends('admin.layout')
@section('title', 'Sign in')
@section('content')
<div class="card" style="max-width:420px;margin-inline:auto">
	<h1>Staff sign in</h1>
	<form method="post" action="/admin/login">
		@csrf
		<label for="email">Email</label>
		<input id="email" type="email" name="email" value="{{ old('email') }}" autocomplete="username" required autofocus>
		<label for="password">Password</label>
		<input id="password" type="password" name="password" autocomplete="current-password" required>
		@error('email') <p class="err" role="alert">{{ $message }}</p> @enderror
		<div class="row"><button>Sign in</button></div>
	</form>
</div>
@endsection
