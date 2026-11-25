<!doctype html>
<html lang="en">
<head>
	@include('layouts.head')
	<title>@yield('title') · ElevenRank HQ</title>
	<link rel="stylesheet" href="/assets/admin.css?v={{ @filemtime(public_path('assets/admin.css')) }}">
</head>
<body class="a-page">
<a class="skip" href="#main">Skip to content</a>
<header class="a-top"><div class="in">
	<a class="a-brand" href="/admin"><img src="/assets/mark.svg" alt="" width="28" height="28"><span>Eleven<b>Rank</b></span> <span class="h-tag">HQ</span></a>
	@auth
	<div class="a-user">
		<span class="h-role">{{ ucfirst(str_replace('_', ' ', auth()->user()->staffRole() ?? 'staff')) }}</span>
		<span class="nm">{{ strtok(auth()->user()->name, ' ') }} (staff)</span>
		<span class="a-av" aria-hidden="true">{{ strtoupper(mb_substr(auth()->user()->name, 0, 1)) }}</span>
		<form method="post" action="/admin/logout">@csrf<button class="a-btn secondary sm">Sign out</button></form>
	</div>
	@endauth
</div></header>
@auth
<div class="a-shell hq">
	<nav class="a-side" aria-label="Staff">
		@foreach ([[null, '/admin', 'Overview', 'admin', 'staff.overview'], ['Sales', '/admin/customers', 'Customers', 'admin/customers*', 'staff.customers'], [null, '/admin/licences', 'Licences', 'admin/licences*', 'staff.licences'], [null, '/admin/orders', 'Orders', 'admin/orders*', 'staff.orders.view'], ['Product', '/admin/releases', 'Releases', 'admin/releases*', 'staff.releases'], [null, '/admin/plans', 'Plans', 'admin/plans*', 'staff.plans'], ['Support', '/admin/tickets', 'Tickets', 'admin/tickets*', 'staff.tickets'], ['System', '/admin/staff', 'Staff', 'admin/staff*', 'staff.staff'], [null, '/admin/webhooks', 'Webhooks', 'admin/webhooks*', 'staff.webhooks'], [null, '/admin/audit', 'Audit log', 'admin/audit*', 'staff.audit'], [null, '/admin/settings', 'Settings', 'admin/settings*', 'staff.system']] as [$grp, $href, $label, $pat, $can])
			@continue(! \Illuminate\Support\Facades\Gate::allows($can))
			@if ($grp)<p class="grp">{{ $grp }}</p>@endif
			<a class="l @if(request()->is($pat)) on @endif" href="{{ $href }}" @if(request()->is($pat)) aria-current="page" @endif><span class="ring" aria-hidden="true"></span>{{ $label }}</a>
		@endforeach
	</nav>
	<main id="main" class="a-main">
		@if (session('status'))<div class="flash" role="status">{{ session('status') }}</div>@endif
		@yield('content')
	</main>
</div>
@else
<main id="main" class="a-main" style="max-width:480px;margin:32px auto;padding:0 16px">@yield('content')</main>
@endauth
<script src="/assets/app.js" defer></script>
</body>
</html>
