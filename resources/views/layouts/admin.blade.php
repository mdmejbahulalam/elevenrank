<!doctype html>
<html lang="en">
<head>
	@include('layouts.head')
	<title>@yield('title') · ElevenRank HQ</title>
	<link rel="stylesheet" href="/assets/admin.css?v={{ @filemtime(public_path('assets/admin.css')) }}">
</head>
<body>
<a class="skip" href="#main">Skip to content</a>
<header class="hq-top"><div class="in">
	<x-logo href="/admin" suffix="HQ" />
	@auth<form method="post" action="/admin/logout" style="margin:0">@csrf<button class="link">Sign out</button></form>@endauth
</div></header>
@auth
<div class="hq-shell">
	<nav class="hq-side" aria-label="Staff">
		@foreach ([[null, '/admin', 'Overview', 'admin'], ['Sales', '/admin/customers', 'Customers', 'admin/customers*'], [null, '/admin/orders', 'Orders', 'admin/orders*'], [null, '/admin/licences', 'Licences', 'admin/licences*'], ['Product', '/admin/releases', 'Releases', 'admin/releases*'], [null, '/admin/plans', 'Plans', 'admin/plans*'], ['Support', '/admin/tickets', 'Tickets', 'admin/tickets*'], ['System', '/admin/audit', 'Audit log', 'admin/audit'], [null, '/admin/system', 'System', 'admin/system']] as [$grp, $href, $label, $pat])
			@if ($grp)<p class="grp">{{ $grp }}</p>@endif
			<a href="{{ $href }}" @if(request()->is($pat)) aria-current="page" @endif>{{ $label }}</a>
		@endforeach
	</nav>
	<main id="main" class="hq-main">
		@if (session('status'))<div class="flash" role="status">{{ session('status') }}</div>@endif
		@yield('content')
	</main>
</div>
@else
<main id="main" class="hq-main" style="max-width:480px;margin:32px auto;padding:0 16px">@yield('content')</main>
@endauth
<script src="/assets/app.js" defer></script>
</body>
</html>
