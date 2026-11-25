@extends('layouts.site')
@section('title', 'Documentation')
@section('description', 'Install ElevenRank, import from another SEO plugin, find your sitemap, set up redirects and structured data. Answers to common questions.')
@section('content')
<div class="wrap page narrow">
	<h1>Documentation</h1>
	<p class="sub">Short answers for the free plugin and ElevenRank Pro. Developers: see the <a href="/docs/hooks">hooks reference</a>.</p>

	<h2>Getting started</h2>
	<ol>
		<li>Upload the <code>elevenrank</code> folder to <code>/wp-content/plugins/</code>, or install it from the Plugins screen.</li>
		<li>Activate it. A short setup wizard checks your site title, tagline and search engine visibility.</li>
		<li>Open <strong>ElevenRank &gt; Titles &amp; Meta</strong> to review how titles and descriptions are built.</li>
	</ol>

	<h2>Installing ElevenRank Pro</h2>
	<ol>
		<li>Buy a licence and sign in to your <a href="/account">account</a>.</li>
		<li>Download ElevenRank Pro from <strong>Downloads</strong>. It is delivered only to accounts with a licence.</li>
		<li>Upload and activate it next to the free plugin.</li>
		<li>Open <strong>ElevenRank &gt; Pro licence</strong>, paste your key and activate. Your site address is registered against the key.</li>
	</ol>
	<p>Moving a site: deactivate it in the plugin or in your account, then activate the new one.</p>

	<h2>Common questions</h2>
	@foreach ([
		['Does ElevenRank send any data to another server?', 'No. The free plugin makes no requests to outside services. The content analysis runs in your browser. The only request it makes goes to your own site, to check whether another post uses the same focus keyword. ElevenRank Pro contacts this site only to check your licence and fetch updates.'],
		['Where is my sitemap?', 'At /sitemap_index.xml (/sitemap.xml redirects there). WordPress\'s own sitemap is switched off while ElevenRank\'s is active.'],
		['My robots.txt edits have no effect.', 'If a real robots.txt file exists in the site folder, the web server serves it and WordPress never sees the request. Delete or rename the file to use the editor.'],
		['How do I show breadcrumbs?', 'Add the Breadcrumbs block, use the [elevenrank_breadcrumbs] shortcode, or call elevenrank_breadcrumbs(); in your theme.'],
		['How do I move from another SEO plugin?', 'Open ElevenRank > Import. Press Check what would change to see the result without writing anything, then Import now. Nothing of the other plugin is changed or deleted.'],
		['Will it clash with another SEO plugin?', 'No. While another SEO plugin is active, ElevenRank adds no titles, meta tags or canonical links, so nothing is printed twice.'],
		['How is the SEO score calculated?', 'Each check is worth points; the score is the share of those points the post earns. 80 or more is good, 50 to 79 needs improvement, below 50 is poor. Checks that cannot apply are left out.'],
		['What happens to my data if I delete the plugin?', 'Nothing, unless you switch on Delete all data on uninstall in ElevenRank > General.'],
	] as [$q, $a])
		<details><summary>{{ $q }}</summary><p>{{ $a }}</p></details>
	@endforeach
	<p style="margin-top:24px">Still stuck? <a href="/support">Contact support</a>.</p>
</div>
@endsection
