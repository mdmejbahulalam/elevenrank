@extends('layouts.account')
@section('page', 'Downloads')
@section('body')
<p class="a-eyebrow">Downloads</p>
<h1>Get ElevenRank Pro</h1>
<p class="a-sub">Pro is an add-on. Install the free ElevenRank plugin first, then upload this zip.</p>
<div class="a-stack">
	<section class="a-card hl">
		<div class="a-between"><div><p class="a-eyebrow">Latest</p><h2 style="margin:0">ElevenRank Pro @if($release){{ $release['version'] }}@endif</h2></div>@if($release)<span class="a-pill ok">Current</span>@endif</div>
		@if (! $release)
			<p class="a-small a-muted" style="margin:8px 0 0">No release has been published yet.</p>
		@elseif ($eligible)
			<p class="a-small a-muted" style="margin:8px 0 16px">Size {{ $release['size'] }} · Requires WordPress 6.4 or newer, PHP 7.4 or newer, and the free ElevenRank plugin active.</p>
			<div class="a-row"><a class="a-btn primary" href="/account/download">Download elevenrank-pro.zip</a><a class="a-btn ghost" href="/changelog?product=pro">Read the changelog</a></div>
			<p class="a-small a-muted" style="margin:16px 0 0">SHA-256 <code style="word-break:break-all">{{ $release['sha256'] }}</code></p>
			@if (count($history) > 1)<p class="a-small a-muted" style="margin:8px 0 0">Earlier versions kept by us: {{ implode(', ', $history) }}. Ask support if you need one.</p>@endif
		@else
			<p class="a-small a-muted" style="margin:8px 0 0">Downloads are available to accounts with a licence whose term is current. <a href="/pricing">See pricing</a>.</p>
		@endif
	</section>
	<section class="a-card">
		<h2>Install in 3 steps</h2>
		<div class="a-check"><span class="a-dot" aria-hidden="true">1</span><p style="margin:0">In WordPress, open Plugins, Add New, Upload Plugin and choose the zip.</p></div>
		<div class="a-check"><span class="a-dot" aria-hidden="true">2</span><p style="margin:0">Activate it. Keep the free ElevenRank plugin active too.</p></div>
		<div class="a-check"><span class="a-dot" aria-hidden="true">3</span><p style="margin:0">Open ElevenRank, Licence and paste your key.</p></div>
	</section>
	<section>
		<h2>Free plugin</h2>
		<div class="a-card a-between"><div><p class="a-h3" style="margin:0">ElevenRank</p><p class="a-small a-muted" style="margin:4px 0 0">Free and GPL. Available from your WordPress dashboard or WordPress.org.</p></div><a class="a-btn secondary" href="{{ config('site.plugin_url') }}">View on WordPress.org</a></div>
	</section>
</div>
@endsection
