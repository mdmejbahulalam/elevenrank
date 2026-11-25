<!doctype html>
<html lang="en">
<head>
	@include('layouts.head')
	<title>@yield('title') · ElevenRank @yield('suffix')</title>
	<link rel="stylesheet" href="/assets/account.css?v={{ @filemtime(public_path('assets/account.css')) }}">
</head>
<body>
<main id="main" class="auth-box"><div class="box">
	<x-logo :suffix="trim($__env->yieldContent('suffix')) ?: null" />
	@if (session('status'))<div class="flash" role="status">{{ session('status') }}</div>@endif
	@yield('content')
</div></main>
<script src="/assets/app.js" defer></script>
</body>
</html>
