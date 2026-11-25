<!doctype html>
<html lang="en">
<head>
	@include('layouts.head')
	<title>{{ trim($__env->yieldContent('page')) ?: 'My account' }} · ElevenRank</title>
	<link rel="stylesheet" href="/assets/account.css?v={{ @filemtime(public_path('assets/account.css')) }}">
</head>
<body class="a-page">
<a class="skip" href="#main">Skip to content</a>
<header class="a-top"><div class="in">
	<a class="a-brand" href="/account"><img src="/assets/mark.svg" alt="" width="28" height="28"><span>Eleven<b>Rank</b></span></a>
	<div class="a-user">
		<a class="a-btn ghost sm" href="/pricing" style="color:var(--muted)">Back to site</a>
		<span class="nm">{{ strtok(auth()->user()->name, ' ') }}</span>
		<span class="a-av" aria-hidden="true">{{ strtoupper(mb_substr(auth()->user()->name, 0, 1)) }}</span>
		<form method="post" action="/account/logout">@csrf<button class="a-btn secondary sm">Sign out</button></form>
	</div>
</div></header>
<div class="a-shell">
	<nav class="a-side" aria-label="Account">
		@foreach ([['/account', 'Overview', 'account', 'home'], ['/account/licences', 'Licences', 'account/licences*', 'key'], ['/account/downloads', 'Downloads', 'account/downloads', 'download'], ['/account/orders', 'Orders', 'account/orders*', 'receipt'], ['/account/tickets', 'Support', 'account/tickets*', 'chat'], ['/account/profile', 'Profile', 'account/profile', 'user']] as [$href, $label, $pat, $ic])
			<a class="l @if(request()->is($pat)) on @endif" href="{{ $href }}" @if(request()->is($pat)) aria-current="page" @endif><x-icon :name="$ic" />{{ $label }}</a>
		@endforeach
		@if (auth()->user()->is_admin)<a class="l desk" href="/admin"><x-icon name="cog" />Staff area</a>@endif
	</nav>
	<main id="main" class="a-main">
		@if (session('status'))<div class="flash" role="status">{{ session('status') }}</div>@endif
		@if (config('services.verify_email') && ! auth()->user()?->email_verified_at)<div class="flash" role="status">Confirm your email to keep your account secure. <form method="post" action="/account/verify/resend" style="display:inline">@csrf<button style="background:none;border:0;padding:0;text-decoration:underline;cursor:pointer;color:inherit;font:inherit">Send the email again</button></form></div>@endif
		@yield('body')
	</main>
</div>
<script src="/assets/app.js" defer></script>
</body>
</html>
