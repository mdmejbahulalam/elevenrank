@extends('layouts.admin')
@section('title', 'Search')
@section('content')
<h1>Search</h1>
<form method="get" action="/admin/search" class="row" style="margin:0 0 16px"><input type="search" name="q" value="{{ $q }}" placeholder="Email, name, licence key, site or order number" aria-label="Search" style="max-width:420px" autofocus><x-button>Search</x-button></form>
@if (strlen($q) < 2)
<x-card><x-empty icon="search" title="Type at least two characters.">Search finds customers, licence keys, sites, orders and tickets.</x-empty></x-card>
@elseif ($customers->isEmpty() && $licences->isEmpty() && $orders->isEmpty() && $tickets->isEmpty())
<x-card><x-empty icon="search" title="Nothing found.">Try part of an email, a key or an order number.</x-empty></x-card>
@else
	@if ($customers->isNotEmpty())<h2>Customers</h2>@foreach ($customers as $u)<p><a href="/admin/customers/{{ $u->id }}">{{ $u->name }}</a> · {{ $u->email }}</p>@endforeach @endif
	@if ($licences->isNotEmpty())<h2>Licences</h2>@foreach ($licences as $l)<p><code>{{ $l->license_key }}</code> · {{ $l->email ?: '—' }} · <x-status :value="$l->status" />@if ($l->user_id) · <a href="/admin/customers/{{ $l->user_id }}">customer</a>@endif</p>@endforeach @endif
	@if ($orders->isNotEmpty())<h2>Orders</h2>@foreach ($orders as $o)<p>{{ $o->number }} · {{ $o->user->email }} · <x-status :value="$o->status" /></p>@endforeach @endif
	@if ($tickets->isNotEmpty())<h2>Tickets</h2>@foreach ($tickets as $t)<p><a href="/admin/tickets/{{ $t->id }}">{{ $t->subject }}</a> · {{ $t->user->email }}</p>@endforeach @endif
@endif
@endsection
