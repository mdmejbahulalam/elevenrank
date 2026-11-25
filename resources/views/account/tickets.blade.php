@extends('layouts.account')
@section('page', 'Support')
@section('body')
<p class="a-eyebrow">Support</p>
<h1>Your tickets</h1>
<p class="a-sub">Support is available during your term. Pro keeps working after it ends.</p>
<div class="a-stack">
	@if ($tickets->isEmpty())
		<section class="a-card"><p class="a-muted" style="margin:0">No tickets yet.</p></section>
	@else
		<div class="a-table-wrap"><table class="a-table">
			<thead><tr><th>Subject</th><th>Updated</th><th>Status</th></tr></thead>
			<tbody>@foreach ($tickets as $t)<tr><td><a href="/account/tickets/{{ $t->id }}">{{ $t->subject }}</a></td><td>{{ $t->updated_at->format('j M Y') }}</td><td><x-status :value="$t->status" /></td></tr>@endforeach</tbody>
		</table></div>
	@endif
	<section class="a-card" style="max-width:680px">
		<h2>Open a ticket</h2>
		<form method="post" action="/account/tickets">
			@csrf
			<x-field name="subject" label="Subject" maxlength="160" required />
			<x-field name="site" label="Site address (optional)" maxlength="190" help="The site this is about, for example example.com." />
			<x-field name="body" label="How can we help?" :rows="5" maxlength="5000" required help="Never paste your licence key or passwords here. We can already see your licence." />
			<p style="margin-top:20px"><button class="a-btn primary">Send ticket</button></p>
		</form>
	</section>
</div>
@endsection
