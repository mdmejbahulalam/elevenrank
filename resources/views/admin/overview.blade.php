@extends('layouts.admin')
@section('title', 'Overview')
@section('content')
<h1>Overview</h1>
<div class="grid">
	<x-stat label="Customers" :value="$customers" />
	<x-stat label="Active licences" :value="$licences" />
	<x-stat label="Sites activated" :value="$sites" />
	<x-stat label="Paid this month" :value="'$'.number_format($revenue, 2)" />
	<div class="card"><div class="stat">{{ $pending }}</div><div class="muted small"><a href="/admin/orders?status=pending">Orders to confirm</a></div></div>
	<div class="card"><div class="stat">{{ $openTickets }}</div><div class="muted small"><a href="/admin/tickets">Open tickets</a></div></div>
	<x-stat label="Expiring in 30 days" :value="$expiring" />
</div>
@can('staff.plans')
<x-card title="Before you launch">
	<ul>
		<li>Set the real prices in <a href="/admin/plans">Plans</a>. The ones there now are placeholders.</li>
		<li>Choose a payment provider. Until then, mark orders paid by hand in <a href="/admin/orders">Orders</a>.</li>
		<li>Set up outgoing mail so licence keys are e-mailed. Right now mail is only written to the log.</li>
		<li>Have the legal pages (terms, privacy, refunds) reviewed.</li>
	</ul>
</x-card>
@endcan
@endsection
