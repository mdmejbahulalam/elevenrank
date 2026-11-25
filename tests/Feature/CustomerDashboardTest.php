<?php

namespace Tests\Feature;

use App\Models\Licence;
use App\Models\Order;
use App\Models\User;
use Illuminate\Auth\Notifications\ResetPassword;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Notification;
use Illuminate\Support\Facades\Password;
use Tests\TestCase;

class CustomerDashboardTest extends TestCase
{
    use RefreshDatabase;

    public function test_password_reset_does_not_reveal_accounts_and_works(): void
    {
        Notification::fake();
        $user = User::factory()->create(['email' => 'a@example.com']);

        $same = 'If that address has an account';
        $this->post('/account/forgot-password', ['email' => 'nobody@example.com'])->assertSessionHas('status');
        $this->from('/account/forgot-password')->followingRedirects()->post('/account/forgot-password', ['email' => 'a@example.com'])->assertSee($same);
        Notification::assertSentTo($user, ResetPassword::class);
        Notification::assertNothingSentTo(User::factory()->make());

        $token = Password::createToken($user);
        $this->post('/account/reset-password', ['token' => $token, 'email' => 'a@example.com', 'password' => 'a-brand-new-pass', 'password_confirmation' => 'a-brand-new-pass'])->assertRedirect('/account/login');
        $this->post('/account/login', ['email' => 'a@example.com', 'password' => 'a-brand-new-pass'])->assertRedirect();
        $this->post('/account/logout');
        $this->get('/account/reset-password/bad?email=a@example.com')->assertOk();
        $this->post('/account/reset-password', ['token' => 'bad', 'email' => 'a@example.com', 'password' => 'another-long-pass', 'password_confirmation' => 'another-long-pass'])->assertSessionHasErrors('email');
    }

    public function test_invoice_refund_request_label_and_export(): void
    {
        $user = User::factory()->create();
        $other = User::factory()->create();
        $licence = Licence::issue($user->email, 1, now()->addYear());
        $licence->update(['user_id' => $user->id]);
        $order = Order::create(['number' => Order::nextNumber(), 'user_id' => $user->id, 'plan_id' => 1, 'amount_cents' => 4900, 'currency' => 'USD', 'status' => 'paid', 'paid_at' => now(), 'licence_id' => $licence->id]);

        $this->actingAs($user);
        $this->get("/account/orders/{$order->number}/invoice")->assertOk()->assertSee('Invoice '.$order->number);
        $this->get("/account/orders/{$order->number}")->assertSee('Request a refund');
        $this->post("/account/orders/{$order->number}/refund")->assertRedirect();
        $this->assertSame(1, $user->tickets()->count());

        $order->update(['paid_at' => now()->subDays(20)]);
        $this->post("/account/orders/{$order->number}/refund")->assertSessionHasErrors('refund');

        $this->post("/account/licences/{$licence->id}/label", ['label' => 'Client site'])->assertRedirect();
        $this->get('/account/licences')->assertSee('Client site');
        $this->get('/account/export')->assertOk()->assertJsonPath('licences.0.label', 'Client site')->assertJsonPath('account.email', $user->email);

        $this->actingAs($other);
        $this->get("/account/orders/{$order->number}/invoice")->assertNotFound();
        $this->post("/account/licences/{$licence->id}/label", ['label' => 'x'])->assertNotFound();
    }

    public function test_ticket_records_the_site_address(): void
    {
        $user = User::factory()->create();
        $this->actingAs($user)->post('/account/tickets', ['subject' => 'Help', 'body' => 'It broke', 'site' => 'example.com'])->assertRedirect();
        $this->assertStringContainsString('Site: example.com', $user->tickets()->first()->replies()->first()->body);
    }
}
