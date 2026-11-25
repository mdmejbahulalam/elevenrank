<?php

namespace Tests\Feature;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class MarketingSeoTest extends TestCase
{
    use RefreshDatabase;

    public function test_pages_have_canonical_open_graph_and_json_ld(): void
    {
        foreach (['/', '/pricing', '/about'] as $path) {
            $this->get($path)->assertOk()
                ->assertSee('<link rel="canonical"', false)
                ->assertSee('property="og:title"', false)
                ->assertSee('application/ld+json', false);
        }
        $this->get('/')->assertSee('SoftwareApplication', false)->assertSee('Get the free plugin');
    }

    public function test_robots_sitemap_and_llms_files(): void
    {
        $this->get('/robots.txt')->assertOk()->assertSee('Disallow: /admin')->assertSee('Sitemap:');
        $this->get('/sitemap.xml')->assertOk()->assertSee('/pricing')->assertDontSee('/account');
        $this->get('/llms.txt')->assertOk()->assertSee('# ElevenRank');
    }

    public function test_unknown_page_shows_the_branded_404(): void
    {
        $this->get('/no-such-page')->assertNotFound()->assertSee('Page not found');
    }

    public function test_content_pages_render_their_new_sections(): void
    {
        $this->get('/features')->assertOk()->assertSee('On this page')->assertSee('id="editor"', false);
        $this->get('/pro')->assertOk()->assertSee('Launch safety')->assertSee('Where Pro stands today');
        $this->get('/docs')->assertOk()->assertSee('Search the docs');
        $this->get('/changelog?product=pro')->assertOk()->assertSee('0.1.0')->assertDontSee('ElevenRank (free) 1.1.0');
        $this->get('/changelog')->assertOk()->assertSee('ElevenRank (free) 1.0.0');
    }
}
