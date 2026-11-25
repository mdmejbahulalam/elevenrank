@extends('layouts.admin')
@section('title', 'Two-factor sign-in')
@section('content')
<h1>Two-factor sign-in</h1>
@if ($codes)
	<x-alert tone="warn" title="Save your recovery codes now">They are shown once. Each works one time if you lose your phone. Keep them somewhere safe, away from this password.</x-alert>
	<x-card><ul style="font:16px ui-monospace,Menlo,Consolas,monospace;columns:2;list-style:none;padding:0;margin:0">@foreach ($codes as $c)<li>{{ $c }}</li>@endforeach</ul></x-card>
	<p style="margin-top:16px"><x-button href="/admin">I have saved them</x-button></p>
@else
	<x-card>
		<ol class="checklist">
			<li>Install an authenticator app (any app that supports time-based codes).</li>
			<li>Add an account using this key: <x-copy-field :value="$secret" label="Copy key" /></li>
			<li>Type the 6-digit code the app shows to turn it on.</li>
		</ol>
		<p class="muted" style="margin-top:12px">Your app may also accept this address instead of the key: <code style="word-break:break-all">{{ $uri }}</code></p>
		<form method="post" action="/admin/two-factor/setup" style="margin-top:12px">
			@csrf
			<x-field name="code" label="6-digit code" autocomplete="one-time-code" required />
			<x-button style="margin-top:16px">Turn on two-factor</x-button>
		</form>
	</x-card>
@endif
@endsection
