@extends('layouts.site')
@section('title', 'About')
@section('description', 'Who makes ElevenRank and the principles behind it: privacy, the GPL and honest claims.')
@section('content')
<section class="s-sec" style="padding-bottom:32px">
	<div class="s-wrap s-narrow">
		<p class="s-eyebrow">About</p>
		<h1 class="s-d-xl" style="font-size:clamp(34px,5vw,44px)">SEO software that respects your site</h1>
		<p class="s-lead">An SEO plugin for WordPress that does its job and stays out of the way.</p>
	</div>
</section>
<section class="s-sec alt" style="padding:56px 0">
	<div class="s-wrap">
		<h2 class="s-d-m">What we hold to</h2>
		<div class="s-grid" style="margin-top:20px">
			<div class="s-card"><h3 class="s-h">Private</h3><p>The free plugin sends nothing to us or anyone else.</p></div>
			<div class="s-card"><h3 class="s-h">Free software</h3><p>ElevenRank and Pro are GPL, like WordPress. The key unlocks updates and support, not your rights.</p></div>
			<div class="s-card"><h3 class="s-h">Honest</h3><p>We say what the product does and does not do, and we keep the <a href="/changelog">changelog</a> current.</p></div>
		</div>
	</div>
</section>
<section class="s-sec" style="padding:56px 0">
	<div class="s-wrap s-narrow">
		<h2 class="s-d-m">The person behind it</h2>
		<div class="s-card s-person rv"><div class="s-avatar" aria-hidden="true">[Photo]</div><div><h3 class="s-h" style="margin:0 0 4px">[Name]</h3><p>[Short bio and where to find them.] <a href="/support">Contact and support</a></p></div></div>
	</div>
</section>
@endsection
