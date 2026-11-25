<?php

namespace Tests\Feature;

use App\Models\AuditLog;
use App\Models\Licence;
use App\Models\Order;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class StaffRolesTest extends TestCase
{
    use RefreshDatabase;

    private function staff(string $role): User
    {
        return User::factory()->create(['is_admin' => true, 'role' => $role]);
    }

    private function order(): Order
    {
        $customer = User::factory()->create();
        $this->actingAs($customer)->post('/checkout/personal');

        return Order::firstOrFail();
    }

    public function test_a_staff_account_without_a_role_is_super_admin(): void
    {
        $legacy = User::factory()->create(['is_admin' => true]);
        $this->assertSame('super_admin', $legacy->staffRole());
        foreach (['/admin', '/admin/plans', '/admin/releases', '/admin/system', '/admin/audit', '/admin/search?q=ab'] as $url) {
            $this->actingAs($legacy)->get($url)->assertOk();
        }
        $this->assertSame('none', User::factory()->create(['is_admin' => true, 'role' => 'bogus'])->staffRole());
        $this->actingAs(User::firstWhere('role', 'bogus'))->get('/admin')->assertForbidden();
    }

    public function test_support_can_help_customers_but_not_run_the_business(): void
    {
        $support = $this->staff('support');
        $this->actingAs($support);
        foreach (['/admin', '/admin/customers', '/admin/licences', '/admin/tickets', '/admin/orders'] as $url) {
            $this->get($url)->assertOk();
        }
        foreach (['/admin/plans', '/admin/releases', '/admin/system', '/admin/audit', '/admin/orders.csv/x'] as $url) {
            $this->get($url)->assertStatus($url === '/admin/orders.csv/x' ? 404 : 403);
        }
        $order = $this->order();
        $this->actingAs($support)->post("/admin/orders/{$order->id}", ['do' => 'paid'])->assertForbidden();
        $this->post("/admin/orders/{$order->id}", ['do' => 'refund', 'reason' => 'x y z'])->assertForbidden();
        $this->assertSame('pending', $order->fresh()->status);
        $this->get('/admin')->assertDontSee('>Plans</a>', false)->assertDontSee('>System</a>', false)->assertSee('>Tickets</a>', false);
    }

    public function test_finance_handles_orders_but_not_licences_or_releases(): void
    {
        $finance = $this->staff('finance');
        $order = $this->order();
        $this->actingAs($finance);
        $this->get('/admin/orders')->assertOk();
        $this->get('/admin/orders.csv')->assertOk();
        $this->get('/admin/licences')->assertForbidden();
        $this->get('/admin/customers')->assertForbidden();
        $this->get('/admin/releases')->assertForbidden();
        $this->post("/admin/orders/{$order->id}", ['do' => 'paid'])->assertRedirect();
        $this->assertSame('paid', $order->fresh()->status);
    }

    public function test_refund_needs_a_reason_which_is_audited_with_the_ip(): void
    {
        $order = $this->order();
        $boss = $this->staff('super_admin');
        $this->actingAs($boss)->post("/admin/orders/{$order->id}", ['do' => 'paid']);

        $this->post("/admin/orders/{$order->id}", ['do' => 'refund'])->assertSessionHasErrors('reason');
        $this->assertSame('paid', $order->fresh()->status);

        $this->post("/admin/orders/{$order->id}", ['do' => 'refund', 'reason' => 'Duplicate order']);
        $row = AuditLog::where('action', 'order.refunded')->firstOrFail();
        $this->assertSame('Duplicate order', $row->reason);
        $this->assertNotNull($row->ip);
        $this->get('/admin/audit')->assertSee('Duplicate order');
    }

    public function test_global_search_finds_keys_orders_and_customers_within_the_role(): void
    {
        $order = $this->order();
        $licence = Licence::issue('someone@example.com', 1, null);
        $boss = $this->staff('super_admin');

        $this->actingAs($boss)->get('/admin/search?q='.$licence->license_key)->assertOk()->assertSee($licence->license_key);
        $this->get('/admin/search?q='.$order->number)->assertOk()->assertSee($order->number);
        $this->get('/admin/search?q=x')->assertOk()->assertSee('at least two characters');

        $finance = $this->staff('finance');
        $this->actingAs($finance)->get('/admin/search?q='.$licence->license_key)->assertOk()->assertSee('Nothing found');
    }

    public function test_orders_csv_neutralises_spreadsheet_formulas(): void
    {
        $order = $this->order();
        $order->user->update(['email' => '=cmd@example.com']);
        $csv = $this->actingAs($this->staff('super_admin'))->get('/admin/orders.csv')->streamedContent();
        $this->assertStringContainsString("'=cmd@example.com", $csv);
        $this->assertStringContainsString($order->number, $csv);
    }

    public function test_create_admin_command_accepts_a_role(): void
    {
        $this->artisan('admin:create', ['email' => 's@example.com', '--role' => 'support'])->expectsQuestion('Password (at least 12 characters)', 'a-very-long-password')->assertSuccessful();
        $this->assertSame('support', User::firstWhere('email', 's@example.com')->staffRole());
        $this->artisan('admin:create', ['email' => 'x@example.com', '--role' => 'wizard'])->assertFailed();
    }
}
