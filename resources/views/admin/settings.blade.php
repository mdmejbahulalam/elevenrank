@extends('layouts.admin')
@section('title', 'Settings')
@section('content')
<p class="a-eyebrow">System</p><h1 style="margin:0 0 24px">Settings</h1>
<form method="post" action="/admin/settings" style="max-width:720px">@csrf
	<section class="a-card"><h2>Business</h2>
		<x-field name="seller_name" label="Seller name on invoices" :value="old('seller_name', $seller)" placeholder="[Legal name]" />
		<x-field name="support_email" type="email" label="Support email" :value="old('support_email', $support)" placeholder="[support address]" />
		<x-field name="refund_days" type="number" label="Refund window (days)" :value="old('refund_days', $refund)" min="0" required />
	</section>
	<section class="a-card" style="margin-top:24px"><h2>Payment provider</h2>
		@if ($provider)<div class="a-alert ok"><div><strong>Connected.</strong> {{ $provider }} webhook is active.</div></div>
		@else<div class="a-alert warn"><div><strong>Not connected.</strong><br>Choose a provider before launch. Orders are created by the Fulfilment service either way.</div></div>@endif
	</section>
	<p style="margin:24px 0 0"><button class="a-btn primary">Save settings</button> @can('staff.system')<a class="a-btn secondary" href="/admin/system" style="margin-left:8px">System tools</a>@endcan</p>
</form>
@endsection
