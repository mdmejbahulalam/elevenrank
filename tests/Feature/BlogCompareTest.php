<?php

namespace Tests\Feature;

use Tests\TestCase;

class BlogCompareTest extends TestCase
{
    public function test_empty_blog_shows_the_empty_state_and_is_noindex(): void
    {
        $this->get('/blog')->assertOk()->assertSee('No posts yet')->assertSee('noindex', false);
        $this->get('/blog/nothing')->assertNotFound();
    }

    public function test_posts_come_from_config_and_filter_by_category(): void
    {
        config(['blog.posts' => [
            ['slug' => 'a-post', 'title' => 'A post', 'category' => 'How-to', 'summary' => 'Sum', 'minutes' => 3, 'author' => 'Me', 'date' => '1 Dec 2026', 'body' => [['h' => 'Head', 'p' => 'Text']]],
            ['slug' => 'b-post', 'title' => 'B post', 'category' => 'Releases', 'summary' => 'Sum', 'minutes' => 2, 'author' => 'Me', 'date' => '2 Dec 2026', 'body' => []],
        ]]);

        $this->get('/blog')->assertOk()->assertSee('A post')->assertSee('B post')->assertDontSee('noindex', false);
        $this->get('/blog?category=Releases')->assertOk()->assertSee('B post')->assertDontSee('A post');
        $this->get('/blog/a-post')->assertOk()->assertSee('Head')->assertSee('B post');
    }

    public function test_compare_page_does_not_claim_unchecked_facts(): void
    {
        $this->get('/compare/yoast-seo')->assertOk()->assertSee('Switching, step by step')->assertSee('Not yet checked')->assertSee('noindex', false);
    }
}
