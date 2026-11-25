<?php

/*
 * Documentation content. Groups, then pages. A page marked 'draft' has bracketed
 * placeholders for facts that still need to be verified, and is kept out of search.
 * Section 'html' is trusted markup written here, never user input.
 */

$draft = '<p class="s-muted">[Write this page from the verified behaviour of the plugin before launch.]</p>';

return [
    'updated' => '25 November 2026',
    'groups' => [
        'Start' => [
            'getting-started' => [
                'title' => 'Getting started',
                'intro' => 'ElevenRank needs WordPress 6.5 or newer and PHP 7.4 or newer. It works on single sites and multisite networks.',
                'sections' => [
                    ['install', 'Install', '<p>Add ElevenRank from Plugins, then Add New, or upload the folder to <code>/wp-content/plugins/</code>. Activate it and the setup wizard opens.</p><div class="s-code" data-code>wp plugin install elevenrank --activate<button type="button" class="s-copy" data-copy="wp plugin install elevenrank --activate">Copy</button></div>'],
                    ['check-your-titles', 'Check your titles', '<p>Open ElevenRank, then Titles &amp; Meta, to review how titles and descriptions are built. Your theme has to let WordPress set the page title; block themes and nearly all modern themes do.</p><div class="s-callout"><p><strong>Already using another SEO plugin?</strong> ElevenRank adds no tags while it is active, so nothing is printed twice.</p></div>'],
                    ['next-steps', 'Next steps', '<p><a href="/docs/import">Import from another plugin</a> · <a href="/docs/sitemaps">Set up sitemaps</a> · <a href="/docs/redirects">Add redirects</a></p>'],
                ],
            ],
            'setup-wizard' => [
                'title' => 'The setup wizard',
                'intro' => 'A short wizard runs when you activate the plugin.',
                'sections' => [
                    ['what-it-checks', 'What it checks', '<p>It checks your site title, tagline and whether search engines are allowed to see the site. You can run through it again at any time from the ElevenRank menu.</p>'],
                ],
            ],
            'import' => [
                'title' => 'Import from another plugin',
                'intro' => 'Importers for Yoast SEO, Rank Math, All in One SEO and SEOPress, with a check first.',
                'sections' => [
                    ['check-then-import', 'Check, then import', '<ol><li>Open ElevenRank, then Import. Each plugin whose data is found is listed with what it holds.</li><li>Press <strong>Check what would change</strong> to see the result without writing anything.</li><li>Press <strong>Import now</strong>.</li></ol><p>Nothing of the other plugin is changed or deleted, so you can go back.</p>'],
                    ['while-both-are-active', 'While both are active', '<p>While another SEO plugin is active, ElevenRank adds no titles, meta tags or canonical links, so nothing is printed twice. Deactivate the old plugin when you are happy with the result.</p>'],
                ],
            ],
        ],
        'Free plugin' => [
            'titles-and-meta' => [
                'title' => 'Titles and meta',
                'intro' => 'Templates with variables for titles and descriptions across the site.',
                'sections' => [
                    ['templates', 'Templates', '<p>Titles and descriptions are built from templates with variables, for posts, pages, custom post types, terms, archives, search and the homepage.</p>'],
                    ['robots-and-canonical', 'Robots and canonical links', '<p>Robots directives have sensible defaults, and canonical links are correct on paginated pages.</p>'],
                    ['sharing', 'Sharing', '<p>Open Graph and X card tags are added with a default sharing image. Image alt text and title are filled from your own patterns when an image has none.</p>'],
                ],
            ],
            'sitemaps' => [
                'title' => 'Sitemaps',
                'intro' => 'XML sitemaps for posts, pages, post types and terms.',
                'sections' => [
                    ['where-is-it', 'Where is my sitemap?', '<p>At <code>/sitemap_index.xml</code>; <code>/sitemap.xml</code> redirects there. WordPress\'s own sitemap is switched off while ElevenRank\'s is active.</p>'],
                    ['robots-txt', 'robots.txt', '<p>If a real <code>robots.txt</code> file exists in the site folder, the web server serves it and WordPress never sees the request. Delete or rename the file to use the editor.</p>'],
                ],
            ],
            'schema' => [
                'title' => 'Schema',
                'intro' => 'One JSON-LD graph per page.',
                'sections' => [
                    ['the-graph', 'The graph', '<p>Each page prints one graph: organization or person, website, page and breadcrumb. Article, product, recipe, event, video and review types are available. Nothing is printed for a type that is missing something Google requires.</p>'],
                    ['breadcrumbs', 'Breadcrumbs', '<p>Add the Breadcrumbs block, use the <code>[elevenrank_breadcrumbs]</code> shortcode, or call <code>elevenrank_breadcrumbs();</code> in your theme.</p>'],
                ],
            ],
            'redirects' => [
                'title' => 'Redirects and the 404 monitor',
                'intro' => 'Redirect rules and a monitor for missing pages.',
                'sections' => [
                    ['rules', 'Rules', '<p>Match exactly, contains, starts with, ends with, or with a regular expression. Codes 301, 302, 307, 410 and 451 are supported, and you can import rules from a CSV file.</p>'],
                    ['automatic', 'Automatic redirects', '<p>An automatic 301 is created when you change the address of a published post.</p>'],
                    ['monitor', 'The 404 monitor', '<p>The monitor lists missing pages so you can turn them into redirects. It stores no IP addresses or browser details.</p>'],
                ],
            ],
            'wp-cli' => [
                'title' => 'WP-CLI',
                'intro' => 'Command-line access to the plugin.',
                'draft' => true,
                'sections' => [['commands', 'Commands', $draft]],
            ],
        ],
        'Pro' => [
            'licence-and-updates' => [
                'title' => 'Licence and updates',
                'intro' => 'Installing ElevenRank Pro and keeping it up to date.',
                'sections' => [
                    ['install-pro', 'Installing ElevenRank Pro', '<ol><li>Buy a licence and sign in to your <a href="/account">account</a>.</li><li>Download ElevenRank Pro from <strong>Downloads</strong>. It is delivered only to accounts with a licence.</li><li>Upload and activate it next to the free plugin.</li><li>Open <strong>ElevenRank &gt; Pro licence</strong>, paste your key and activate. Your site address is registered against the key.</li></ol>'],
                    ['moving-a-site', 'Moving a site', '<p>Deactivate the licence in the plugin or in your account, then activate the new site.</p>'],
                    ['when-it-ends', 'When your licence ends', '<p>Pro keeps working on your sites. Updates and support stop until you renew.</p>'],
                ],
            ],
            'ai-account-setup' => [
                'title' => 'AI account setup',
                'intro' => 'The AI writer uses your own AI account.',
                'draft' => true,
                'sections' => [['setup', 'Setup', $draft]],
            ],
            'search-console-setup' => [
                'title' => 'Search Console setup',
                'intro' => 'Connect Search Console to see clicks and impressions next to your posts.',
                'draft' => true,
                'sections' => [['setup', 'Setup', $draft]],
            ],
        ],
        'Developers' => [
            'hooks' => ['title' => 'Hooks and filters', 'external' => '/docs/hooks'],
        ],
    ],
];
