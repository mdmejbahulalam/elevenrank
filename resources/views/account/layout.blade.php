@extends('site.layout')
@section('title', trim($__env->yieldContent('page')))
@section('content')
<div class="wrap page">
	<div style="display:flex;gap:28px;flex-wrap:wrap;align-items:flex-start">
		<nav aria-label="Account" style="flex:0 0 180px;display:flex;flex-direction:column;gap:2px">
			@foreach ([['/account', 'Overview', 'account'], ['/account/licences', 'Licences', 'account/licences*'], ['/account/downloads', 'Downloads', 'account/downloads'], ['/account/orders', 'Orders', 'account/orders*'], ['/account/tickets', 'Support', 'account/tickets*'], ['/account/profile', 'Profile', 'account/profile']] as [$href, $label, $pat])
				<a href="{{ $href }}" @if(request()->is($pat)) aria-current="page" style="font-weight:700;background:var(--soft);padding:8px 12px;border-radius:12px;text-decoration:none" @else style="padding:8px 12px;text-decoration:none;color:var(--ink)" @endif>{{ $label }}</a>
			@endforeach
			@if (auth()->user()->is_admin)<a href="/admin" style="padding:8px 12px;text-decoration:none">Staff area</a>@endif
			<form method="post" action="/account/logout" style="padding:8px 12px">@csrf<button class="link">Sign out</button></form>
		</nav>
		<div style="flex:1;min-width:280px">
			@if (session('status'))<div class="flash" role="status">{{ session('status') }}</div>@endif
			@yield('body')
		</div>
	</div>
</div>
@endsection
