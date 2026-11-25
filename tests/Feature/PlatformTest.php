<?php

namespace Tests\Feature;

use App\Models\Licence;
use App\Models\Order;
use App\Models\Plan;
use App\Models\Ticket;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class PlatformTest extends TestCase
{
    use RefreshDatabase;

    public function test_marketing_pages_render(): void
    {
        foreach (['/', '/features', '/pro', '/pricing', '/docs', '/docs/hooks', '/changelog', '/support', '/terms', '/privacy', '/refunds', '/gpl', '/account/login', '/account/register'] as $url) {
            $this->get($url)->assertOk();
        }
        $this->get('/pricing')->assertSee('Personal')->assertSee('Studio');
    }

    public function test_guests_are_sent_to_the_right_login(): void
    {
        $this->get('/account')->assertRedirect('/account/login');
        $this->get('/checkout/personal')->assertRedirect('/account/login');
        $this->get('/admin/orders')->assertRedirect('/admin/login');
    }

    public function test_registration_never_creates_staff(): void
    {
        $this->post('/account/register', ['name' => 'Ann', 'email' => 'ann@example.com', 'password' => 'a-long-password', 'password_confirmation' => 'a-long-password', 'is_admin' => 1])->assertRedirect('/account');
        $this->assertFalse(User::firstWhere('email', 'ann@example.com')->is_admin);
    }

    public function test_order_to_licence_to_refund_flow(): void
    {
        $user = User::factory()->create();
        $staff = User::factory()->create(['is_admin' => true]);

        $this->actingAs($user)->post('/checkout/agency')->assertRedirect();
        $order = Order::firstOrFail();
        $this->assertSame('pending', $order->status);
        $this->assertSame(0, Licence::count());
        $this->get('/account/downloads')->assertOk()->assertDontSee('Download elevenrank-pro.zip');

        $this->actingAs($staff)->post("/admin/orders/{$order->id}", ['do' => 'paid'])->assertRedirect();
        $this->post("/admin/orders/{$order->id}", ['do' => 'paid']); // idempotent
        $licence = Licence::firstOrFail();
        $this->assertSame(1, Licence::count());
        $this->assertSame(3, $licence->site_limit);
        $this->assertSame($user->id, $licence->user_id);

        $this->actingAs($user)->get('/account/licences')->assertSee($licence->license_key);
        $this->postJson('/wp-json/elevenrank-licence/v1/activate', ['key' => $licence->license_key, 'site' => 'a.test'])->assertJson(['status' => 'valid']);

        $this->actingAs($staff)->post("/admin/orders/{$order->id}", ['do' => 'refund', 'reason' => 'Customer asked']);
        $this->assertSame('revoked', $licence->fresh()->status);
        $this->postJson('/wp-json/elevenrank-licence/v1/check', ['key' => $licence->license_key, 'site' => 'a.test'])->assertJson(['status' => 'revoked']);
    }

    public function test_customers_cannot_see_each_others_data_or_the_staff_area(): void
    {
        $a = User::factory()->create();
        $b = User::factory()->create();
        $order = Order::create(['number' => 'ER-X', 'user_id' => $a->id, 'plan_id' => Plan::first()->id]);
        $ticket = Ticket::create(['user_id' => $a->id, 'subject' => 'Hi']);

        $this->actingAs($b)->get('/account/orders/ER-X')->assertNotFound();
        $this->get("/account/tickets/{$ticket->id}")->assertNotFound();
        $this->get('/admin/orders')->assertForbidden();
        $this->post("/admin/orders/{$order->id}", ['do' => 'paid'])->assertForbidden();
    }

    public function test_customer_can_deactivate_own_site_only(): void
    {
        $a = User::factory()->create();
        $b = User::factory()->create();
        $licence = Licence::issue('a@example.com', 1, now()->addYear());
        $licence->update(['user_id' => $a->id]);
        $licence->activateSite('x.test');

        $this->actingAs($b)->post("/account/licences/{$licence->id}/deactivate", ['site' => 'x.test'])->assertNotFound();
        $this->assertSame(['x.test'], $licence->fresh()->sites());
        $this->actingAs($a)->post("/account/licences/{$licence->id}/deactivate", ['site' => 'x.test'])->assertRedirect();
        $this->assertSame([], $licence->fresh()->sites());
    }

    public function test_tickets_flow_between_customer_and_staff(): void
    {
        $user = User::factory()->create();
        $staff = User::factory()->create(['is_admin' => true]);

        $this->actingAs($user)->post('/account/tickets', ['subject' => 'Help', 'body' => 'It broke'])->assertRedirect();
        $ticket = Ticket::firstOrFail();
        $this->actingAs($staff)->post("/admin/tickets/{$ticket->id}/reply", ['body' => 'Try this', 'close' => 1]);
        $this->assertSame('closed', $ticket->fresh()->status);
        $this->actingAs($user)->get("/account/tickets/{$ticket->id}")->assertSee('Try this');
    }

    public function test_webhook_is_off_without_a_secret_and_checks_the_signature(): void
    {
        $this->postJson('/webhooks/paddle', [])->assertNotFound();

        config(['services.paddle.secret' => 's3cret', 'services.paddle.prices' => '{"pri_77":"personal"}']);
        $body = json_encode(['event_type' => 'transaction.completed', 'data' => ['id' => 'txn_9', 'items' => [['price' => ['id' => 'pri_77']]], 'custom_data' => ['email' => 'buyer@example.com', 'name' => 'Buyer']]]);
        $headers = fn (string $sig) => ['CONTENT_TYPE' => 'application/json', 'HTTP_PADDLE_SIGNATURE' => $sig];

        $this->call('POST', '/webhooks/paddle', [], [], [], $headers('ts='.time().';h1=bad'), $body)->assertUnauthorized();
        $ts = time();
        $sig = "ts={$ts};h1=".hash_hmac('sha256', $ts.':'.$body, 's3cret');
        $this->call('POST', '/webhooks/paddle', [], [], [], $headers($sig), $body)->assertOk();
        $this->call('POST', '/webhooks/paddle', [], [], [], $headers($sig), $body)->assertOk();

        $this->assertSame(1, Licence::count());
        $this->assertSame('buyer@example.com', Licence::first()->email);
    }

    public function test_staff_can_edit_plans_and_extend_licences(): void
    {
        $staff = User::factory()->create(['is_admin' => true]);
        $plan = Plan::where('slug', 'personal')->first();
        $this->actingAs($staff)->post("/admin/plans/{$plan->id}", ['name' => 'Solo', 'sites' => 1, 'price' => '59.50', 'months' => 12, 'active' => 1])->assertRedirect();
        $this->assertSame(5950, $plan->fresh()->price_cents);

        $licence = Licence::issue('a@example.com', 1, now()->addMonth());
        $before = $licence->expires;
        $this->post("/admin/licences/{$licence->id}", ['extend_months' => 12, 'site_limit' => 5]);
        $this->assertSame(5, $licence->fresh()->site_limit);
        $this->assertTrue($licence->fresh()->expires->gt($before->copy()->addMonths(11)));
        $this->get('/admin')->assertOk();
        $this->get('/admin/system')->assertOk();
        $this->get('/admin/audit')->assertOk();
    }
}
