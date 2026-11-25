@extends('layouts.account')
@section('page', 'Checkout')
@section('body')
<h1>Confirm your order</h1>
<x-card>
	<h3>{{ $plan->name }}</h3>
	<p>{{ $plan->sitesLabel() }} · {{ $plan->months }} months of updates and support</p>
	<p class="price">{{ $plan->price() }}</p>
	<form method="post" action="/checkout/{{ $plan->slug }}">
		@csrf
		<p class="muted">You will receive payment instructions by e-mail, and your licence key is issued as soon as payment is confirmed. 14-day refund. By ordering you accept the <a href="/terms">terms</a>.</p>
		<x-button style="margin-top:16px">Place order</x-button>
	</form>
</x-card>
@endsection
