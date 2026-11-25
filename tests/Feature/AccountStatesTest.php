<?php

namespace Tests\Feature;

use App\Models\Licence;
use App\Models\Plan;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class AccountStatesTest extends TestCase
{
    use RefreshDatabase;

    public function test_overview_warns_when_the_term_is_ending_and_greets_by_first_name(): void
    {
        $u = User::factory()->create(['name' => 'Alex Morgan']);
        Licence::create(['license_key' => 'ER-PRO-TEST', 'email' => $u->email, 'site_limit' => 3, 'expires' => now()->addDays(12), 'status' => 'active', 'user_id' => $u->id, 'plan_id' => Plan::first()->id]);

        $this->actingAs($u)->get('/account')->assertOk()->assertSee('Your term ends soon.')->assertSee('Ends in 12 days')->assertSee('Welcome back, Alex');
    }

    public function test_deleting_an_account_is_blocked_while_a_licence_is_live_and_works_otherwise(): void
    {
        $u = User::factory()->create(['password' => bcrypt('correct-horse-1')]);
        $lic = Licence::create(['license_key' => 'ER-PRO-LIVE', 'email' => $u->email, 'site_limit' => 1, 'expires' => now()->addMonths(6), 'status' => 'active', 'user_id' => $u->id]);

        $this->actingAs($u)->post('/account/profile/delete', ['delete_password' => 'correct-horse-1'])->assertSessionHasErrors('delete');
        $this->assertModelExists($u);

        $lic->update(['status' => 'revoked']);
        $this->actingAs($u)->post('/account/profile/delete', ['delete_password' => 'correct-horse-1'])->assertRedirect('/');
        $this->assertModelMissing($u);
    }
}
