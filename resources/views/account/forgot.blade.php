@extends('layouts.auth')
@section('title', 'Reset password')
@section('noflash', '1')
@section('content')
<x-card>
	<h1 style="margin-top:0;font-size:20px">Reset your password</h1>
	<p class="muted">We will email you a link. It works for 60 minutes.</p>
	<form method="post" action="/account/forgot-password">
		@csrf
		<x-field name="email" label="Email" type="email" autocomplete="username" placeholder="you@example.com" required />
		<p style="margin:20px 0 0"><x-button class="block">Send reset link</x-button></p>
	</form>
	@if (session('status'))<div class="a-alert ok" role="status" style="margin-top:16px"><div><strong>Check your inbox.</strong> If that address has an account, a link is on its way.</div></div>@endif
</x-card>
<p><a href="/account/login">Back to sign in</a></p>
@endsection
