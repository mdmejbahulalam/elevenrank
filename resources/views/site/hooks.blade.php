@extends('layouts.site')
@section('title', 'Developer hooks')
@section('description', 'Public actions and filters in ElevenRank for building add-ons and customising output.')
@section('content')
<div class="wrap page">
	<h1>Developer hooks</h1>
	<p class="sub">Public actions and filters. Anything not listed here is internal and may change.</p>

	<h2>Actions</h2>
	<table>
		<thead><tr><th>Hook</th><th>Arguments</th><th>Fires</th></tr></thead>
		<tbody>
		@foreach ([
			['elevenrank_register_modules', 'Module_Registry $registry', 'Before modules boot. Add-ons register their own Module implementations here.'],
			['elevenrank_loaded', 'none', 'After ElevenRank and every active module have booted.'],
			['elevenrank_deactivate', 'bool $network_wide', 'When the plugin is deactivated.'],
			['elevenrank_sitemap_cache_cleared', 'string[] $names', 'After sitemap cache entries were dropped.'],
		] as [$h, $a, $d])
			<tr><td><code>{{ $h }}</code></td><td><code>{{ $a }}</code></td><td>{{ $d }}</td></tr>
		@endforeach
		</tbody>
	</table>

	<h2>Filters</h2>
	<table>
		<thead><tr><th>Hook</th><th>Arguments</th><th>Purpose</th></tr></thead>
		<tbody>
		@foreach ([
			['elevenrank_settings_schema', 'array $definitions', 'Add or change settings groups. Values are validated against the schema everywhere.'],
			['elevenrank_variables', 'array $callbacks', 'Add or replace template variables.'],
			['elevenrank_detected_seo_plugins', 'array $found', 'Other SEO plugins that are active; a non-empty list pauses front-end output.'],
			['elevenrank_sitemap_entry', 'array $entry, string $name, object', 'Change or drop one sitemap entry.'],
			['elevenrank_sitemap_alternates', 'array $alternates, WP_Post $post', 'Language alternates for a post.'],
			['elevenrank_sitemap_sources', 'Entry_Source[] $sources', 'Add or replace the sources that provide sitemap files.'],
			['elevenrank_sitemap_post_types / _taxonomies', 'string[] $slugs', 'Post types and taxonomies that get a sitemap.'],
			['elevenrank_sitemap_cache_ttl', 'int $seconds, string $name', 'Cache lifetime of a sitemap file.'],
			['elevenrank_robots_file_paths', 'string[] $paths', 'Where a physical robots.txt is looked for.'],
			['elevenrank_breadcrumb_items', 'array $items, Context $context', 'The trail before it is rendered.'],
			['elevenrank_redirect_destination', 'string $destination, array $rule', 'The address a redirect sends visitors to. Return an empty string to skip.'],
			['elevenrank_redirect_allowed_hosts', 'string[] $hosts', 'Hosts redirects may lead to besides this site.'],
			['elevenrank_schema_graph', 'array $document, Context $context', 'The whole JSON-LD document before it is printed.'],
			['elevenrank_schema_{type}', 'array|null $node, Context $context', 'One node of the graph; return null to leave it out.'],
			['elevenrank_schema_enabled_for_post', 'bool $enabled, int $post_id', 'Whether a post gets structured data at all.'],
			['elevenrank_editor_config', 'array $config', 'Configuration read by the editor scripts.'],
			['elevenrank_admin_config', 'array $config', 'Add screens and settings to the ElevenRank admin app.'],
			['elevenrank_is_pro_active', 'bool $active', 'Whether a Pro add-on is active.'],
		] as [$h, $a, $d])
			<tr><td><code>{{ $h }}</code></td><td><code>{{ $a }}</code></td><td>{{ $d }}</td></tr>
		@endforeach
		</tbody>
	</table>
	<p class="muted" style="margin-top:16px">This page is a summary; the plugin repository holds the full reference.</p>
</div>
@endsection
