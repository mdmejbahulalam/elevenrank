<!doctype html>
<html lang="en">
<head>
	@include('layouts.head')
	<title>{{ trim($__env->yieldContent('page')) ?: 'My account' }} · ElevenRank</title>
	<link rel="stylesheet" href="/assets/account.css?v={{ @filemtime(public_path('assets/account.css')) }}">
</head>
<body>
<a class="skip" href="#main">Skip to content</a>
<header class="acc-top"><div class="in">
	<x-logo />
	<div class="row" style="margin:0"><a class="btn ghost small" href="/pricing">Back to site</a><span class="muted small">{{ auth()->user()->name }}</span></div>
</div></header>
<div class="acc-shell">
	<nav class="acc-side" aria-label="Account">
		@foreach ([['/account', 'Overview', 'account', 'home'], ['/account/licences', 'Licences', 'account/licences*', 'key'], ['/account/downloads', 'Downloads', 'account/downloads', 'download'], ['/account/orders', 'Orders', 'account/orders*', 'receipt'], ['/account/tickets', 'Support', 'account/tickets*', 'chat'], ['/account/profile', 'Profile', 'account/profile', 'user']] as [$href, $label, $pat, $ic])
			<a href="{{ $href }}" @if(request()->is($pat)) aria-current="page" @endif><x-icon :name="$ic" />{{ $label }}</a>
		@endforeach
		@if (auth()->user()->is_admin)<a class="desk" href="/admin"><x-icon name="cog" />Staff area</a>@endif
		<form method="post" action="/account/logout" class="desk">@csrf<button class="link">Sign out</button></form>
	</nav>
	<main id="main" class="acc-main">
		@if (session('status'))<div class="flash" role="status">{{ session('status') }}</div>@endif
		@yield('body')
	</main>
</div>
<script src="/assets/app.js" defer></script>
</body>
</html>
