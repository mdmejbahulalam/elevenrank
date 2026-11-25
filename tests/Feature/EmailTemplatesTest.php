<?php

namespace Tests\Feature;

use Illuminate\Support\Facades\View;
use Tests\TestCase;

class EmailTemplatesTest extends TestCase
{
    public function test_every_email_template_renders_with_its_variables(): void
    {
        $vars = ['name' => 'Alex', 'url' => 'https://example.test/account', 'number' => 'ER-1', 'plan' => 'Agency', 'amount' => '$99.00', 'key' => 'ER-PRO-TEST', 'date' => '14 Nov 2027', 'subject' => 'Help', 'excerpt' => 'We freed that slot.'];
        foreach (['welcome', 'receipt', 'renewal30', 'renewal7', 'expired', 'refund', 'ticket_reply'] as $t) {
            $html = View::make("emails.$t", $vars)->render();
            $this->assertStringContainsString('ElevenRank', $html);
            $this->assertStringContainsString('<title>', $html);
        }
        $this->assertStringContainsString('Pro keeps working', View::make('emails.expired', $vars)->render());
        $this->assertStringContainsString('ER-PRO-TEST', View::make('emails.receipt', $vars)->render());
    }
}
