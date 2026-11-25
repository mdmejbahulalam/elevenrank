@extends('layouts.account')
@section('page', 'Support')
@section('body')
<h1>Support</h1>
<x-card style="margin-bottom:20px">
	<h3>Open a ticket</h3>
	<form method="post" action="/account/tickets">
		@csrf
		<x-field name="subject" label="Subject" maxlength="160" required />
		<x-field name="site" label="Site address (optional)" maxlength="190" help="The site this is about, for example example.com." />
		<x-field name="body" label="How can we help?" :rows="5" maxlength="5000" required />
		<x-button style="margin-top:16px">Send</x-button>
	</form>
</x-card>
<h2>Your tickets</h2>
@forelse ($tickets as $t)
	<p><a href="/account/tickets/{{ $t->id }}">{{ $t->subject }}</a> · <x-status :value="$t->status" /> · {{ $t->updated_at->format('j M Y') }}</p>
@empty
	<p class="muted">No tickets yet.</p>
@endforelse
@endsection
