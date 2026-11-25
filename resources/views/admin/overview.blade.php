@extends('layouts.admin')
@section('title', 'Overview')
@section('content')
@php $max = max(1, $weeks->max()); $h = now()->hour; $greet = $h < 12 ? 'Good morning' : ($h < 18 ? 'Good afternoon' : 'Good evening'); @endphp
<div class="a-between" style="margin-bottom:24px;align-items:flex-start"><div><p class="a-eyebrow">Overview</p><h1 style="margin:0">{{ $greet }}, {{ strtok(auth()->user()->name, ' ') }}</h1></div>
	@can('staff.search')<form method="get" action="/admin/search" role="search" style="margin:0"><input type="search" name="q" placeholder="Search customers, keys, orders" aria-label="Search customers, keys, orders" style="width:280px"></form>@endcan</div>
<div class="a-stack" style="gap:24px">
	<div class="h-stats" style="grid-template-columns:repeat(4,minmax(0,1fr))">
		<div class="a-card h-stat"><p class="n">{{ $licences }}</p><p class="l">Active licences</p></div>
		<div class="a-card h-stat"><p class="n">${{ number_format($revenue) }}</p><p class="l">Revenue, last 30 days</p></div>
		<div class="a-card h-stat"><p class="n">{{ $openTickets }}</p><p class="l">Open tickets</p></div>
		<div class="a-card h-stat"><p class="n">{{ $pending }}</p><p class="l">Pending orders</p></div>
	</div>
	<div class="h-two" style="grid-template-columns:minmax(0,1fr) 434px">
		<section class="a-card"><h2>Orders per week</h2>
			<div class="h-bars" role="img" aria-label="Orders per week, last 8 weeks: {{ $weeks->implode(', ') }}">@foreach ($weeks as $i => $n)<div><i style="height:{{ max(3, round($n / $max * 100)) }}%" title="{{ $n }}"></i><span>W{{ $i + 1 }}</span></div>@endforeach</div>
		</section>
		<section class="a-card"><h2>Needs attention</h2>
			@if ($refunds)<div class="h-attn"><span class="ic">!</span><div><p class="a-h3" style="margin:0"><a href="/admin/tickets">{{ $refunds }} refund {{ str('request')->plural($refunds) }}</a></p><p class="a-small a-muted" style="margin:0">Check the 14-day window.</p></div></div>@endif
			@if ($pending)<div class="h-attn"><span class="ic">!</span><div><p class="a-h3" style="margin:0"><a href="/admin/orders?status=pending">{{ $pending }} {{ str('order')->plural($pending) }} to confirm</a></p></div></div>@endif
			@if ($waiting)<div class="h-attn"><span class="ic">!</span><div><p class="a-h3" style="margin:0"><a href="/admin/tickets">{{ $waiting }} {{ str('ticket')->plural($waiting) }} waiting for a reply</a></p><p class="a-small a-muted" style="margin:0">Oldest is {{ $oldestDays }} {{ str('day')->plural($oldestDays) }}.</p></div></div>@endif
			@if ($expiring)<div class="h-attn"><span class="ic">!</span><div><p class="a-h3" style="margin:0"><a href="/admin/licences?ends=30">{{ $expiring }} {{ str('licence')->plural($expiring) }} end within 30 days</a></p></div></div>@endif
			@if (! $refunds && ! $pending && ! $waiting && ! $expiring)<p class="a-muted" style="margin:12px 0 0">Nothing needs attention.</p>@endif
		</section>
	</div>
	<section><h2>Latest activity</h2>
		<div class="a-table-wrap"><table class="a-table"><thead><tr><th>When</th><th>Who</th><th>What</th></tr></thead><tbody>
		@forelse ($activity as $e)<tr><td>{{ $e->created_at->diffForHumans(short: true) }} ago</td><td>{{ $e->user?->name ? strtok($e->user->name, ' ') : 'System' }}</td><td>{{ $e->action }} {{ $e->subject }}</td></tr>
		@empty<tr><td colspan="3" class="a-muted" style="text-align:center">No activity yet.</td></tr>@endforelse
		</tbody></table></div>
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
