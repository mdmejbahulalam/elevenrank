@extends('layouts.auth')
@section('suffix', 'HQ')
@section('title', 'Two-factor code')
@section('content')
<x-card>
	<h1 style="margin-top:0">Enter your code</h1>
	<p class="muted">Open your authenticator app and type the 6-digit code for ElevenRank. Lost your phone? Use one of your recovery codes instead.</p>
	<form method="post" action="/admin/two-factor">
		@csrf
		<x-field name="code" label="Code or recovery code" autocomplete="one-time-code" required />
		<p style="margin-top:20px"><x-button class="block">Verify</x-button></p>
	</form>
</x-card>
<p><a href="/admin/login">Start again</a></p>
@endsection
