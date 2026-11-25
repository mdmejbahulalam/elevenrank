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
	@else
		<p>Downloads are available to accounts with a licence whose term is current. <a href="/pricing">See pricing</a>.</p>
	@endif
</x-card>
<p class="muted" style="margin-top:16px">The free plugin is available from your WordPress dashboard or WordPress.org.</p>
@endsection
