@extends('layouts.account')
@section('page', 'Support')
@section('body')
<h1>Support</h1>
<div class="card" style="margin-bottom:20px">
	<h3>Open a ticket</h3>
	<form method="post" action="/account/tickets">
		@csrf
		<label for="subject">Subject</label>
		<input id="subject" type="text" name="subject" value="{{ old('subject') }}" maxlength="160" required>
		@error('subject') <p class="err" role="alert">{{ $message }}</p> @enderror
		<label for="body">How can we help?</label>
		<textarea id="body" name="body" rows="5" maxlength="5000" required>{{ old('body') }}</textarea>
		@error('body') <p class="err" role="alert">{{ $message }}</p> @enderror
		<p><button class="btn">Send</button></p>
	</form>
</div>
<h2>Your tickets</h2>
@forelse ($tickets as $t)
	<p><a href="/account/tickets/{{ $t->id }}">{{ $t->subject }}</a> · {{ $t->status }} · {{ $t->updated_at->format('j M Y') }}</p>
@empty
	<p class="muted">No tickets yet.</p>
@endforelse
@endsection
