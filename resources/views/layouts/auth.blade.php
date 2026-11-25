<!doctype html>
<html lang="en">
<head>
	@include('layouts.head')
	<title>@yield('title') · ElevenRank @yield('suffix')</title>
	<link rel="stylesheet" href="/assets/account.css?v={{ @filemtime(public_path('assets/account.css')) }}">
</head>
<body class="a-page">
<main id="main" class="a-auth"><div class="box">
	<a class="a-brand" href="{{ trim($__env->yieldContent('suffix')) ? '/admin' : '/' }}"><img src="/assets/mark.svg" alt="" width="28" height="28"><span>Eleven<b>Rank</b></span>@if (trim($__env->yieldContent('suffix')))<span class="h-tag">{{ trim($__env->yieldContent('suffix')) }}</span>@endif</a>
	@if (session('status'))<div class="flash" role="status">{{ session('status') }}</div>@endif
	@yield('content')
</div></main>
<script src="/assets/app.js" defer></script>
</body>
</html>
