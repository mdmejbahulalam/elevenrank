@extends('admin.layout')
@section('title', 'Overview')
@section('content')
<h1>Overview</h1>
<div class="grid">
	<div class="card"><div class="muted">Customers</div><div class="stat">{{ $customers }}</div></div>
	<div class="card"><div class="muted">Active licences</div><div class="stat">{{ $licences }}</div></div>
	<div class="card"><div class="muted">Sites activated</div><div class="stat">{{ $sites }}</div></div>
	<div class="card"><div class="muted">Paid this month</div><div class="stat">${{ number_format($revenue, 2) }}</div></div>
	<div class="card"><div class="muted"><a href="/admin/orders?status=pending">Orders to confirm</a></div><div class="stat">{{ $pending }}</div></div>
	<div class="card"><div class="muted"><a href="/admin/tickets">Open tickets</a></div><div class="stat">{{ $openTickets }}</div></div>
	<div class="card"><div class="muted">Expiring in 30 days</div><div class="stat">{{ $expiring }}</div></div>
</div>
<div class="card"><h2>Before you launch</h2>
	<ul>
		<li>Set the real prices in <a href="/admin/plans">Plans</a>. The ones there now are placeholders.</li>
		<li>Choose a payment provider. Until then, mark orders paid by hand in <a href="/admin/orders">Orders</a>.</li>
		<li>Set up outgoing mail so licence keys are e-mailed. Right now mail is only written to the log.</li>
		<li>Have the legal pages (terms, privacy, refunds) reviewed.</li>
	</ul>
</div>
@endsection
