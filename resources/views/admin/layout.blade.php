<!doctype html>
<html lang="en">
<head>
	<meta charset="utf-8">
	<meta name="viewport" content="width=device-width, initial-scale=1">
	<title>@yield('title') · ElevenRank HQ</title>
	<style>
		:root { --bg:#f6f7fb; --card:#fff; --ink:#14142b; --muted:#5f6178; --line:#e3e5ee; --accent:#4338ca; --bad:#b42318; --ok:#067647; }
		@media (prefers-color-scheme: dark) { :root { --bg:#0f1020; --card:#181a30; --ink:#eceefb; --muted:#9a9cb8; --line:#2a2d4a; --accent:#8b83ff; --bad:#ff8a80; --ok:#6ce9a6; } }
		* { box-sizing: border-box; }
		body { margin:0; background:var(--bg); color:var(--ink); font:16px/1.5 system-ui,-apple-system,"Segoe UI",sans-serif; }
		header { display:flex; align-items:center; justify-content:space-between; padding:14px 24px; background:var(--card); border-bottom:1px solid var(--line); }
		header strong { letter-spacing:.01em; }
		main { max-width:1000px; margin:32px auto; padding:0 16px; }
		header nav { flex-wrap:wrap; } header nav a { font-size:.92rem; }
		input[type=text], input[type=number], textarea { width:100%; padding:10px 12px; border:1px solid var(--line); border-radius:12px; background:var(--bg); color:var(--ink); font:inherit; }
		.flash { background:var(--bg); border:1px solid var(--line); border-radius:12px; padding:10px 14px; margin-bottom:16px; }
		.grid { display:grid; gap:12px; grid-template-columns:repeat(auto-fit,minmax(170px,1fr)); margin-bottom:20px; } .stat { font-size:1.8rem; font-weight:800; }
		.card { background:var(--card); border:1px solid var(--line); border-radius:12px; padding:20px; margin-bottom:20px; }
		h1 { font-size:1.4rem; margin:0 0 16px; } h2 { font-size:1.05rem; margin:0 0 12px; }
		label { display:block; font-weight:600; margin:12px 0 6px; }
		input[type=email], input[type=password], input[type=file], select { width:100%; padding:10px 12px; border:1px solid var(--line); border-radius:12px; background:var(--bg); color:var(--ink); font:inherit; }
		button { padding:10px 16px; border:0; border-radius:12px; background:var(--accent); color:#fff; font:inherit; font-weight:600; cursor:pointer; }
		button.link { background:none; color:var(--muted); padding:0; font-weight:400; text-decoration:underline; }
		.row { display:flex; gap:12px; align-items:center; flex-wrap:wrap; margin-top:12px; }
		.ok { color:var(--ok); } .err { color:var(--bad); margin:6px 0 0; }
		a { color:var(--accent); text-decoration:none; } table { width:100%; border-collapse:collapse; } th, td { text-align:left; padding:8px 6px; border-bottom:1px solid var(--line); }
		.muted { color:var(--muted); } code { background:var(--bg); padding:2px 6px; border-radius:6px; }
	</style>
</head>
<body>
	<header>
		<strong>ElevenRank HQ</strong>
		@auth
			<nav style="display:flex;gap:16px;align-items:center">
				<a href="/admin">Overview</a>
				<a href="/admin/customers">Customers</a>
				<a href="/admin/orders">Orders</a>
				<a href="/admin/licences">Licences</a>
				<a href="/admin/releases">Releases</a>
				<a href="/admin/tickets">Tickets</a>
				<a href="/admin/plans">Plans</a>
				<a href="/admin/audit">Audit</a>
				<a href="/admin/system">System</a>
				<form method="post" action="/admin/logout">@csrf<button class="link">Sign out</button></form>
			</nav>
		@endauth
	</header>
	<main>@if (session('status'))<div class="flash" role="status">{{ session('status') }}</div>@endif @yield('content')</main>
</body>
</html>
