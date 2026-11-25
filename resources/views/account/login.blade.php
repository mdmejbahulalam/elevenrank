@extends('layouts.auth')
@section('title', 'Sign in')
@section('content')
<x-card>
	<h1 style="margin-top:0;font-size:20px">Sign in</h1>
	<form method="post" action="/account/login">
		@csrf
		<x-field name="email" label="Email" type="email" autocomplete="username" placeholder="you@example.com" required />
		<x-field name="password" label="Password" type="password" autocomplete="current-password" placeholder="Your password" required />
		<div class="a-between" style="margin:14px 0 20px"><label class="a-small a-muted" style="display:flex;gap:8px;align-items:center;margin:0;font-weight:400"><input type="checkbox" name="remember" value="1" style="width:auto;min-height:0;margin:0"> Remember me</label><a class="a-small" href="/account/forgot-password">Forgot password?</a></div>
		<x-button class="block">Sign in</x-button>
	</form>
</x-card>
<p>New here? <a href="/account/register">Create an account</a></p>
@endsection
