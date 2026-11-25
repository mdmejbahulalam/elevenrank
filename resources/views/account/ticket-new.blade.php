@extends('layouts.account')
@section('page', 'Open a ticket')
@section('body')
<p class="a-eyebrow"><a href="/account/tickets">Support</a> / New</p>
<h1>Open a ticket</h1>
<p class="a-sub">Tell us what happened. Include the site address and what you expected.</p>
<section class="a-card" style="max-width:680px">
	<form method="post" action="/account/tickets">
		@csrf
		<label class="a-label" for="t-topic" style="margin-top:0">Topic</label>
		<select id="t-topic" name="topic">@foreach (['Activation or licence', 'Billing or refund', 'Bug report', 'Question about a feature', 'Something else'] as $t)<option @selected(old('topic') === $t)>{{ $t }}</option>@endforeach</select>
		<label class="a-label" for="t-site">Site (optional)</label>
		<input id="t-site" name="site" type="text" maxlength="190" value="{{ old('site') }}" list="t-sites" placeholder="example.com"><datalist id="t-sites">@foreach ($sites as $s)<option value="{{ $s }}">@endforeach</datalist>
		<label class="a-label" for="t-subject">Subject</label>
		<input id="t-subject" name="subject" type="text" maxlength="160" required value="{{ old('subject', request('subject')) }}" placeholder="Short summary">
		@error('subject')<p class="a-small" style="color:var(--bad);margin:4px 0 0">{{ $message }}</p>@enderror
		<label class="a-label" for="t-body">Message</label>
		<textarea id="t-body" name="body" rows="5" maxlength="5000" required placeholder="Describe the problem and the steps to repeat it.">{{ old('body', request('body')) }}</textarea>
		@error('body')<p class="a-small" style="color:var(--bad);margin:4px 0 0">{{ $message }}</p>@enderror
		<p class="a-small a-muted" style="margin:6px 0 0">Never paste your licence key or passwords here. We can already see your licence.</p>
		<p class="a-row" style="margin-top:20px"><button class="a-btn primary">Send ticket</button><a class="a-btn ghost" href="/account/tickets">Cancel</a></p>
	</form>
</section>
@endsection
