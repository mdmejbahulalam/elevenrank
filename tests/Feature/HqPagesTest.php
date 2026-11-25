<?php

namespace Tests\Feature;

use App\Models\AuditLog;
use App\Models\Plan;
use App\Models\Setting;
use App\Models\Ticket;
use App\Models\User;
use App\Models\WebhookEvent;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class HqPagesTest extends TestCase
{
    use RefreshDatabase;

    private function staff(?string $role = null): User
    {
        return User::factory()->create(['is_admin' => true, 'role' => $role ?? 'super_admin', 'name' => 'Sam Staff']);
    }

    public function test_every_hq_page_renders_for_a_super_admin(): void
    {
        $this->actingAs($this->staff());
        foreach (['/admin', '/admin/staff', '/admin/settings', '/admin/webhooks', '/admin/releases', '/admin/releases/new', '/admin/plans', '/admin/audit', '/admin/tickets', '/admin/customers', '/admin/orders', '/admin/licences'] as $url) {
            $this->get($url)->assertOk();
        }
        $this->get('/admin/staff')->assertSee('What each role can do')->assertSee('Plans only');
        $this->get('/admin/audit.csv')->assertOk();
    }

    public function test_a_role_without_access_sees_the_calm_page_and_the_attempt_is_recorded(): void
    {
        $support = $this->staff('support');
        $this->actingAs($support)->get('/admin/plans')->assertForbidden()->assertSee('Your role cannot open Plans')->assertSee('finance and super admin staff');
        $this->assertTrue(AuditLog::where('action', 'staff.denied')->where('subject', '/admin/plans')->exists());
        $this->get('/admin/staff')->assertForbidden();
    }

    public function test_settings_save_and_plans_can_be_added(): void
    {
        $this->actingAs($this->staff());
        $this->post('/admin/settings', ['seller_name' => 'Acme Ltd', 'support_email' => 'help@example.com', 'refund_days' => 21])->assertRedirect();
        $this->assertSame('Acme Ltd', Setting::get('seller_name'));
        $this->assertSame('21', Setting::get('refund_days'));

        $this->post('/admin/plans', ['name' => 'Team', 'sites' => 5, 'price' => '149', 'months' => 12, 'active' => 1])->assertRedirect();
        $this->assertSame(14900, Plan::where('slug', 'team')->first()->price_cents);
    }

    public function test_a_failed_webhook_can_be_retried(): void
    {
        $this->actingAs($this->staff());
        $event = WebhookEvent::create(['provider' => 'paddle', 'event' => 'transaction.completed', 'payload' => json_encode(['event_type' => 'transaction.completed', 'data' => ['id' => 'txn_1']]), 'attempts' => 1, 'status' => 'failed']);
        $this->get('/admin/webhooks')->assertSee('Retry now');
        $this->post("/admin/webhooks/{$event->id}/retry")->assertRedirect();
        $this->assertSame('processed', $event->fresh()->status);
        $this->assertSame(2, $event->fresh()->attempts);
    }

    public function test_internal_notes_never_reach_the_customer(): void
    {
        $customer = User::factory()->create();
        $ticket = Ticket::create(['user_id' => $customer->id, 'subject' => 'Help']);
        $ticket->replies()->create(['user_id' => $customer->id, 'is_staff' => false, 'body' => 'It broke']);

        $this->actingAs($this->staff())->post("/admin/tickets/{$ticket->id}/reply", ['do' => 'note', 'body' => 'SECRET staff only'])->assertRedirect();
        $this->post("/admin/tickets/{$ticket->id}/reply", ['do' => 'reply', 'body' => 'Fixed now'])->assertRedirect();
        $this->get("/admin/tickets/{$ticket->id}")->assertSee('SECRET staff only')->assertSee('Internal note.');

        $this->actingAs($customer)->get("/account/tickets/{$ticket->id}")->assertSee('Fixed now')->assertDontSee('SECRET staff only');
        $this->assertSame('answered', $ticket->fresh()->status);
        $this->assertNotNull($ticket->fresh()->assigned_to);
    }

    public function test_staff_can_sign_in_with_the_code_on_the_same_page(): void
    {
        $staff = $this->staff();
        $staff->update(['password' => bcrypt('a-long-password-1')]);
        $this->post('/admin/login', ['email' => $staff->email, 'password' => 'a-long-password-1'])->assertRedirect();
        $this->assertNotNull($staff->fresh()->last_login_at);
    }
}
