<?php

namespace Tests\Feature;

use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\URL;
use Tests\TestCase;

class AccountControlTest extends TestCase
{
    use RefreshDatabase;

    private function staff(): User
    {
        return User::factory()->create(['is_admin' => true, 'role' => 'super_admin']);
    }

    public function test_disabling_a_customer_signs_them_out(): void
    {
        $customer = User::factory()->create();
        $this->actingAs($this->staff())->post("/admin/customers/{$customer->id}/disable")->assertRedirect();
        $this->assertNotNull($customer->fresh()->disabled_at);

        auth()->logout();
        $this->actingAs($customer->fresh())->get('/account')->assertRedirect('/account/login');
    }

    public function test_staff_cannot_be_disabled_here(): void
    {
        $other = $this->staff();
        $this->actingAs($this->staff())->post("/admin/customers/{$other->id}/disable")->assertForbidden();
    }

    public function test_invite_creates_staff_with_role(): void
    {
        $this->actingAs($this->staff())->post('/admin/staff', ['name' => 'New Person', 'email' => 'new@example.test', 'role' => 'support'])->assertRedirect();
        $u = User::where('email', 'new@example.test')->first();
        $this->assertSame('support', $u->staffRole());
    }

    public function test_email_confirmation_link_confirms_the_address(): void
    {
        config(['services.verify_email' => true]);
        Mail::fake();
        $this->post('/account/register', ['name' => 'Ann', 'email' => 'ann@example.test', 'password' => 'a-long-password-1']);
        $u = User::where('email', 'ann@example.test')->first();
        $this->assertNull($u->email_verified_at);
        $url = URL::temporarySignedRoute('account.verify', now()->addHour(), ['user' => $u->id]);
        $this->get($url)->assertRedirect();
        $this->assertNotNull($u->fresh()->email_verified_at);
        $this->get("/account/verify/{$u->id}")->assertForbidden();
    }

    public function test_hooks_page_uses_site_styles_and_pricing_uses_pro_card(): void
    {
        $this->get('/docs/hooks')->assertSee('s-table', false);
        $this->get('/pricing')->assertSee('social-pro.png', false);
    }

    public function test_pages_carry_a_social_image(): void
    {
        $this->get('/')->assertSee('/brand/social-default.png', false);
    }
}
