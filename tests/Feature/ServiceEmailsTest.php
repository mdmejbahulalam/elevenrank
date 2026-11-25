<?php

namespace Tests\Feature;

use App\Mail\TemplateMail;
use App\Models\Licence;
use App\Models\Order;
use App\Models\Ticket;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Mail;
use Tests\TestCase;

class ServiceEmailsTest extends TestCase
{
    use RefreshDatabase;

    public function test_welcome_receipt_refund_and_ticket_reply_are_sent(): void
    {
        Mail::fake();
        $staff = User::factory()->create(['is_admin' => true]);

        $this->post('/account/register', ['name' => 'Ann', 'email' => 'ann@example.com', 'password' => 'a-long-password', 'password_confirmation' => 'a-long-password']);
        Mail::assertSent(TemplateMail::class, fn ($m) => $m->template === 'welcome' && $m->hasTo('ann@example.com'));

        $user = User::firstWhere('email', 'ann@example.com');
        $this->actingAs($user)->post('/checkout/personal');
        $order = Order::firstOrFail();
        Mail::assertNotSent(fn (TemplateMail $m) => $m->template === 'receipt');

        $this->actingAs($staff)->post("/admin/orders/{$order->id}", ['do' => 'paid']);
        $key = Licence::firstOrFail()->license_key;
        Mail::assertSent(TemplateMail::class, fn ($m) => $m->template === 'receipt' && $m->hasTo('ann@example.com') && $m->vars['key'] === $key);

        $this->post("/admin/orders/{$order->id}", ['do' => 'paid']); // idempotent: no second receipt
        Mail::assertSentCount(2); // welcome and one receipt

        $this->post("/admin/orders/{$order->id}", ['do' => 'refund']);
        Mail::assertSent(TemplateMail::class, fn ($m) => $m->template === 'refund' && $m->hasTo('ann@example.com'));

        $ticket = Ticket::create(['user_id' => $user->id, 'subject' => 'Help']);
        $this->post("/admin/tickets/{$ticket->id}/reply", ['body' => 'We fixed it.']);
        Mail::assertSent(TemplateMail::class, fn ($m) => $m->template === 'ticket_reply' && str_contains($m->vars['excerpt'], 'We fixed it'));
    }

    public function test_a_mail_failure_does_not_undo_the_order(): void
    {
        Mail::shouldReceive('to')->andThrow(new \RuntimeException('smtp down'));
        $staff = User::factory()->create(['is_admin' => true]);
        $user = User::factory()->create();
        $this->actingAs($user)->post('/checkout/personal');
        $order = Order::firstOrFail();

        $this->actingAs($staff)->post("/admin/orders/{$order->id}", ['do' => 'paid'])->assertRedirect();
        $this->assertSame('paid', $order->fresh()->status);
        $this->assertSame(1, Licence::count());
    }

    public function test_term_notices_go_out_once_each(): void
    {
        Mail::fake();
        $a = Licence::issue('a@example.com', 1, now()->addDays(20));
        $b = Licence::issue('b@example.com', 1, now()->addDays(5));
        $c = Licence::issue('c@example.com', 1, now()->subDays(2));
        Licence::issue('d@example.com', 1, now()->addMonths(6));
        Licence::issue('e@example.com', 1, null);

        $this->artisan('emails:term-notices')->assertSuccessful();
        Mail::assertSent(TemplateMail::class, 3);
        Mail::assertSent(TemplateMail::class, fn ($m) => $m->template === 'renewal30' && $m->hasTo('a@example.com'));
        Mail::assertSent(TemplateMail::class, fn ($m) => $m->template === 'renewal7' && $m->hasTo('b@example.com'));
        Mail::assertSent(TemplateMail::class, fn ($m) => $m->template === 'expired' && $m->hasTo('c@example.com'));

        $this->artisan('emails:term-notices')->assertSuccessful();
        Mail::assertSent(TemplateMail::class, 3); // not repeated
    }

    public function test_mail_renders_html_with_the_layout(): void
    {
        $html = (new TemplateMail('refund', 'Your ElevenRank refund', ['number' => 'ER-1', 'amount' => '$49.00']))->render();
        $this->assertStringContainsString('Licence revoked', $html);
        $this->assertStringContainsString('no unsubscribe link', $html);
    }
}
