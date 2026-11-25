@extends('layouts.account')
@section('page', 'Downloads')
@section('body')
<h1>Downloads</h1>
<x-card>
	<h3>ElevenRank Pro @if($release) <x-badge>{{ $release['version'] }}</x-badge> @endif</h3>
	@if (! $release)
		<p>No release has been published yet.</p>
	@elseif ($eligible)
		<p>Upload and activate it next to the free plugin, then enter your key under ElevenRank &gt; Pro licence.</p>
		<p style="margin-top:16px"><x-button href="/account/download"><x-icon name="download" /> Download elevenrank-pro.zip</x-button></p>
		<p class="muted" style="margin-top:12px">Size {{ $release['size'] }} · SHA-256 <code style="word-break:break-all">{{ $release['sha256'] }}</code></p>
		<p class="muted">Requires WordPress 6.4 or newer, PHP 7.4 or newer, and the free ElevenRank plugin active.</p>
		@if (count($history) > 1)<p class="muted">Earlier versions kept by us: {{ implode(', ', $history) }}. Ask support if you need one.</p>@endif
	@else
		<p>Downloads are available to accounts with a licence whose term is current. <a href="/pricing">See pricing</a>.</p>
	@endif
</x-card>
<p class="muted" style="margin-top:16px">The free plugin is available from your WordPress dashboard or WordPress.org.</p>
@endsection
