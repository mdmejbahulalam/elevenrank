<?php

namespace Tests\Feature;

use App\Models\Order;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class PortedPagesTest extends TestCase
{
    use RefreshDatabase;

    public function test_every_account_and_staff_page_renders_with_data(): void
    {
        $user = User::factory()->create();
        $staff = User::factory()->create(['is_admin' => true]);

        // Empty states first.
        $this->actingAs($user);
        foreach (['/account', '/account/licences', '/account/downloads', '/account/orders', '/account/tickets', '/account/profile', '/checkout/personal'] as $url) {
            $this->get($url)->assertOk();
        }
        $this->actingAs($staff);
        foreach (['/admin', '/admin/customers', '/admin/orders', '/admin/plans', '/admin/tickets', '/admin/audit', '/admin/system', '/admin/licences', '/admin/releases'] as $url) {
            $this->get($url)->assertOk();
        }

        // With data.
        $this->actingAs($user)->post('/checkout/personal');
        $order = Order::firstOrFail();
        $this->post('/account/tickets', ['subject' => 'Help me', 'body' => 'Please']);
        $this->get('/account/orders')->assertOk()->assertSee($order->number)->assertSee('pending');
        $this->get("/account/orders/{$order->number}")->assertOk()->assertSee('Payment is being finalised');
        $this->get('/account/tickets')->assertOk()->assertSee('Help me');
        $this->get('/account/tickets/1')->assertOk()->assertSee('Reply');

        $this->actingAs($staff);
        $this->post("/admin/orders/{$order->id}", ['do' => 'paid']);
        $this->get('/admin/orders')->assertOk()->assertSee($order->number)->assertSee('Refund');
        $this->get('/admin/licences')->assertOk()->assertSee('Revoke');
        $this->get('/admin/customers')->assertOk()->assertSee($user->email);
        $this->get("/admin/customers/{$user->id}")->assertOk()->assertSee($order->number);
        $this->get('/admin/tickets?status=all')->assertOk()->assertSee('Help me');
        $this->get('/admin/tickets/1')->assertOk()->assertSee('Close the ticket');
        $this->get('/admin/audit')->assertOk();
    }
}
