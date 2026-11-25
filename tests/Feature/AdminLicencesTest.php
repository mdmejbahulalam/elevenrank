<?php

namespace Tests\Feature;

use App\Models\Licence;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class AdminLicencesTest extends TestCase
{
    use RefreshDatabase;

    private function staff(): User
    {
        return User::factory()->create(['is_admin' => true]);
    }

    public function test_only_staff_can_manage_licences(): void
    {
        $this->get('/admin/licences')->assertRedirect('/admin/login');
        $this->post('/admin/licences', ['site_limit' => 1, 'months' => 12])->assertRedirect('/admin/login');

        $this->actingAs(User::factory()->create())->get('/admin/licences')->assertForbidden();
        $this->post('/admin/licences', ['site_limit' => 1, 'months' => 12])->assertForbidden();
        $this->assertSame(0, Licence::count());
    }

    public function test_staff_create_a_licence_that_the_api_accepts(): void
    {
        $this->actingAs($this->staff())
            ->post('/admin/licences', ['email' => 'buyer@example.com', 'site_limit' => 3, 'months' => 12])
            ->assertRedirect('/admin/licences')
            ->assertSessionHas('created');

        $licence = Licence::firstOrFail();
        $this->assertSame(3, $licence->site_limit);
        $this->assertTrue($licence->expires->isFuture());
        $this->get('/admin/licences')->assertSee($licence->license_key)->assertSee('buyer@example.com');

        $this->postJson('/wp-json/elevenrank-licence/v1/activate', ['key' => $licence->license_key, 'site' => 'shop.test'])
            ->assertJson(['status' => 'valid']);
    }

    public function test_zero_months_means_no_expiry_and_input_is_validated(): void
    {
        $this->actingAs($this->staff());

        $this->post('/admin/licences', ['site_limit' => 0, 'months' => 0]);
        $this->assertNull(Licence::firstOrFail()->expires);

        $this->post('/admin/licences', ['email' => 'nope', 'site_limit' => -1, 'months' => 'x'])
            ->assertSessionHasErrors(['email', 'site_limit', 'months']);
        $this->assertSame(1, Licence::count());
    }

    public function test_revoking_blocks_the_key_and_reinstating_restores_it(): void
    {
        $this->actingAs($this->staff());
        $licence = Licence::issue('a@example.com', 1, now()->addYear());
        $body = ['key' => $licence->license_key, 'site' => 'x.test'];

        $this->post("/admin/licences/{$licence->id}/status", ['status' => 'revoked'])->assertRedirect('/admin/licences');
        $this->postJson('/wp-json/elevenrank-licence/v1/check', $body)->assertJson(['status' => 'revoked']);

        $this->post("/admin/licences/{$licence->id}/status", ['status' => 'active']);
        $this->postJson('/wp-json/elevenrank-licence/v1/activate', $body)->assertJson(['status' => 'valid']);
    }
}
