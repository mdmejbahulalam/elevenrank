@extends('layouts.admin')
@section('title', 'Overview')
@section('content')
<div class="a-between" style="margin-bottom:24px"><div><p class="a-eyebrow">Overview</p><h1 style="margin:0">Good day, {{ auth()->user()->name }}</h1></div></div>
<div class="a-stack">
	<div class="h-stats">
		<div class="a-card h-stat"><p class="n">{{ $customers }}</p><p class="l">Customers</p></div>
		<div class="a-card h-stat"><p class="n">{{ $licences }}</p><p class="l">Active licences</p></div>
		<div class="a-card h-stat"><p class="n">{{ $sites }}</p><p class="l">Sites activated</p></div>
		<div class="a-card h-stat"><p class="n">${{ number_format($revenue, 2) }}</p><p class="l">Paid this month</p></div>
	</div>
	<section class="a-card">
		<h2>Needs attention</h2>
		<div class="a-check" style="border-top:0"><span class="a-dot" aria-hidden="true">!</span><div><p class="a-h3" style="margin:0"><a href="/admin/orders?status=pending">{{ $pending }} {{ \Illuminate\Support\Str::plural('order', $pending) }} to confirm</a></p></div></div>
		<div class="a-check"><span class="a-dot" aria-hidden="true">!</span><div><p class="a-h3" style="margin:0"><a href="/admin/tickets">{{ $openTickets }} open {{ \Illuminate\Support\Str::plural('ticket', $openTickets) }}</a></p></div></div>
		<div class="a-check"><span class="a-dot" aria-hidden="true">!</span><div><p class="a-h3" style="margin:0"><a href="/admin/licences">{{ $expiring }} {{ \Illuminate\Support\Str::plural('licence', $expiring) }} end within 30 days</a></p></div></div>
	</section>
	@can('staff.plans')
	<section class="a-card">
		<h2>Before you launch</h2>
		<ul>
			<li>Set the real prices in <a href="/admin/plans">Plans</a>. The ones there now are placeholders.</li>
			<li>Choose a payment provider. Until then, mark orders paid by hand in <a href="/admin/orders">Orders</a>.</li>
			<li>Set up outgoing mail so licence keys are e-mailed. Right now mail is only written to the log.</li>
			<li>Have the legal pages (terms, privacy, refunds) reviewed.</li>
		</ul>
	</section>
	@endcan
</div>
@endsection
