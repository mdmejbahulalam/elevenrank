@extends('layouts.auth')
@section('title', 'Create account')
@section('content')
<x-card>
	<h1 style="margin-top:0;font-size:20px">Create your account</h1>
	<form method="post" action="/account/register">
		@csrf
		<x-field name="name" label="Name" autocomplete="name" placeholder="Your name" required />
		<x-field name="email" label="Email" type="email" autocomplete="username" placeholder="you@example.com" required />
		<x-field name="password" label="Password" type="password" autocomplete="new-password" placeholder="At least 10 characters" help="Use a phrase you will remember." required />
		<p class="muted" style="margin:16px 0">By continuing you agree to the <a href="/terms">terms</a> and <a href="/privacy">privacy policy</a>.</p>
		<x-button class="block">Create account</x-button>
	</form>
</x-card>
<p>Have an account? <a href="/account/login">Sign in</a></p>
@endsection
