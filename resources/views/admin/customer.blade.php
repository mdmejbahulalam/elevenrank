@extends('layouts.admin')
@section('title', $user->name)
@section('content')
<div class="a-between" style="align-items:flex-start;margin-bottom:24px"><div><p class="a-eyebrow"><a href="/admin/customers">Customers</a> / {{ $user->name }}</p><p class="a-eyebrow" style="margin-top:0">Customer</p><h1 style="margin:0">{{ $user->name }}</h1></div>
	<div class="a-row"><a class="a-btn secondary" href="mailto:{{ $user->email }}">Message</a></div></div>
<div class="h-two">
	<div class="a-stack" style="gap:24px">
		<section class="a-card"><h2>Licences</h2>
			@if ($licences->isEmpty())<p class="a-muted">None.</p>@else
			<x-table :headings="['Key', 'Plan', 'Sites', 'Ends', 'Status']">
				@foreach ($licences as $l)<tr><td><a class="a-mono" href="/admin/licences/{{ $l->id }}">{{ \Illuminate\Support\Str::limit($l->license_key, 12, '…').substr($l->license_key, -4) }}</a></td><td>{{ $l->plan?->name ?? '—' }}</td><td>{{ $l->activations_count }} of {{ $l->site_limit ?: '∞' }}</td><td>{{ $l->expires?->format('j M Y') ?? '—' }}</td><td><x-status :value="$l->status === 'active' && $l->isExpired() ? 'expired' : $l->status" /></td></tr>@endforeach
			</x-table>@endif
		</section>
		<section class="a-card"><h2>Orders</h2>
			@if ($orders->isEmpty())<p class="a-muted">None.</p>@else
			<x-table :headings="['Order', 'Date', 'Total', 'Status']" :right="[2]">
				@foreach ($orders as $o)<tr><td><a href="/admin/orders/{{ $o->number }}">{{ $o->number }}</a></td><td>{{ $o->created_at->format('j M Y') }}</td><td style="text-align:right">{{ $o->amount() }}</td><td><x-status :value="$o->status" /></td></tr>@endforeach
			</x-table>@endif
		</section>
		<section class="a-card"><h2>Tickets</h2>
			@if ($tickets->isEmpty())<p class="a-muted">None.</p>@else
			<x-table :headings="['Subject', 'Status']">
				@foreach ($tickets as $t)<tr><td><a href="/admin/tickets/{{ $t->id }}">{{ $t->subject }}</a></td><td><x-status :value="$t->status" /></td></tr>@endforeach
			</x-table>@endif
		</section>
	</div>
	<aside class="a-card"><h2>Details</h2>
		<dl class="h-kv"><dt>Email</dt><dd>{{ $user->email }}</dd><dt>Country</dt><dd>{{ $user->billing_country ?: '—' }}</dd><dt>Joined</dt><dd>{{ $user->created_at->format('j M Y') }}</dd><dt>Last sign-in</dt><dd>{{ $user->last_login_at?->diffForHumans() ?? 'Never' }}</dd><dt>Account</dt><dd>{{ $user->disabled_at ? 'Disabled' : 'Active' }}</dd></dl>
		<form method="post" action="/admin/customers/{{ $user->id }}/disable" style="margin-top:16px">@csrf<button class="a-btn secondary sm">{{ $user->disabled_at ? 'Enable account' : 'Disable account' }}</button></form>
		<form method="post" action="/admin/customers/{{ $user->id }}/note" style="margin-top:16px">@csrf
			<label class="a-label" for="note" style="margin-top:0">Staff note</label>
			<textarea id="note" name="staff_note" rows="3" maxlength="1000" placeholder="Only staff see this.">{{ old('staff_note', $user->staff_note) }}</textarea>
			<p style="margin:12px 0 0"><button class="a-btn secondary sm">Save note</button></p>
		</form>
	</aside>
</div>
@endsection
