<?php

namespace Tests\Feature;

use App\Mail\TemplateMail;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\View;
use Tests\TestCase;

class EmailTemplatesTest extends TestCase
{
    use RefreshDatabase;

    public function test_every_email_template_renders_with_its_variables(): void
    {
        $vars = ['name' => 'Alex', 'url' => 'https://example.test/account', 'number' => 'ER-1', 'plan' => 'Agency', 'amount' => '$99.00', 'key' => 'ER-PRO-TEST', 'date' => '14 Nov 2027', 'subject' => 'Help', 'excerpt' => 'We freed that slot.'];
        foreach (['welcome', 'receipt', 'licence', 'renewal30', 'renewal7', 'expired', 'refund', 'revoked', 'ticket_reply', 'reset', 'verify'] as $t) {
            $html = View::make("emails.$t", $vars)->render();
            $this->assertStringContainsString('ElevenRank', $html);
            $this->assertStringContainsString('<title>', $html);
        }
        $this->assertStringContainsString('Pro keeps working', View::make('emails.expired', $vars)->render());
        $this->assertStringContainsString('ER-PRO-TEST', View::make('emails.licence', $vars)->render());
        $plain = (new TemplateMail('licence', 'Your ElevenRank Pro licence key', $vars))->plain();
        $this->assertStringContainsString('ER-PRO-TEST', $plain);
        $this->assertStringNotContainsString('<', $plain);
    }
}
