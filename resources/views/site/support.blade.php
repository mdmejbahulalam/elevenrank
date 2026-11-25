@extends('layouts.site')
@section('title', 'Support')
@section('description', 'Get help with ElevenRank: documentation, community support for the free plugin, and tickets for Pro customers.')
@section('content')
<section class="s-sec" style="padding-bottom:32px">
	<div class="s-wrap s-narrow">
		<p class="s-eyebrow">Support</p>
		<h1 class="s-d-xl" style="font-size:clamp(34px,5vw,44px)">How can we help?</h1>
		<p class="s-lead">Search the docs first. Most answers are there.</p>
		<form class="s-search" role="search" action="/docs" method="get"><svg width="18" height="18" viewBox="0 0 20 20" fill="none" stroke="currentColor" stroke-width="1.75" stroke-linecap="round" aria-hidden="true"><circle cx="9" cy="9" r="5.5"/><path d="M13.5 13.5L17 17"/></svg><input type="search" name="q" placeholder="Search the docs" autocomplete="off" aria-label="Search the docs"></form>
	</div>
</section>
<section class="s-sec alt" style="padding:48px 0">
	<div class="s-wrap">
		<div class="s-grid s-grid3">
			<div class="s-card rv"><h2 class="s-h">Free plugin</h2><p>Ask in the WordPress.org support forum. Replies come from the community and the maker.</p><p style="margin-top:16px"><a class="s-btn secondary" href="{{ rtrim(str_replace('/plugins/', '/support/plugin/', config('site.plugin_url')), '/') }}/">Open the forum</a></p></div>
			<div class="s-card rv d1"><h2 class="s-h">Pro customers</h2><p>Sign in and open a ticket tied to your licence. [Response time: state it honestly here.]</p><p style="margin-top:16px"><a class="s-btn primary" href="/account/tickets">Sign in to open a ticket</a></p></div>
			<div class="s-card rv d2"><h2 class="s-h">Email</h2><p>[Support channel decision: add the address here, or remove this card.]</p></div>
		</div>
	</div>
</section>
<section class="s-sec" style="padding:56px 0">
	<div class="s-wrap s-narrow">
		<h2 class="s-d-m">Before you write</h2>
		<p class="s-muted">Include your site address, WordPress and PHP versions and the debug text from ElevenRank, then Status. It contains no addresses, keys or emails.</p>
		<form class="s-form" method="get" action="/account/tickets/new">
			<label for="s-subject">Subject</label>
			<input id="s-subject" name="subject" type="text" maxlength="160" placeholder="What is the problem?">
			<label for="s-body">Message</label>
			<textarea id="s-body" name="body" rows="4" maxlength="5000" placeholder="Describe what you expected and what happened."></textarea>
			<p style="margin:20px 0 0"><button class="s-btn primary" type="submit">Send</button></p>
		</form>
	</div>
</section>
@endsection
