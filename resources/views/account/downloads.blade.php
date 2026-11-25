@extends('layouts.account')
@section('page', 'Downloads')
@section('body')
<h1>Downloads</h1>
<div class="card">
	<h3>ElevenRank Pro @if($release) <span class="tag">{{ $release['version'] }}</span> @endif</h3>
	@if (! $release)
		<p>No release has been published yet.</p>
	@elseif ($eligible)
		<p>Upload and activate it next to the free plugin, then enter your key under ElevenRank &gt; Pro licence.</p>
		<p><a class="btn" href="/account/download">Download elevenrank-pro.zip</a></p>
	@else
		<p>Downloads are available to accounts with a licence whose term is current. <a href="/pricing">See pricing</a>.</p>
	@endif
</div>
<p class="muted">The free plugin is available from your WordPress dashboard or WordPress.org.</p>
@endsection
