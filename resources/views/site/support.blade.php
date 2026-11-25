@extends('site.layout')
@section('title', 'Support')
@section('description', 'Get help with ElevenRank: documentation, community support for the free plugin, and tickets for Pro customers.')
@section('content')
<div class="wrap page narrow">
	<h1>Support</h1>
	<div class="grid" style="margin-top:20px">
		<div class="card"><h3>Documentation</h3><p>Setup, importing and common questions. <a href="/docs">Read the docs</a>.</p></div>
		<div class="card"><h3>Pro customers</h3><p>Open a ticket from your account and we reply there. <a href="/account/tickets">Open a ticket</a>.</p></div>
	</div>
	<h2>Free plugin</h2>
	<p>Free-plugin questions are answered on the WordPress.org support forum for ElevenRank once the plugin is listed there.</p>
	<h2>Before you write</h2>
	<ul>
		<li>Tell us the plugin and WordPress versions (ElevenRank &gt; Status has a debug text you can copy; it contains no addresses, keys or e-mails).</li>
		<li>Describe what you expected and what happened.</li>
	</ul>
</div>
@endsection
