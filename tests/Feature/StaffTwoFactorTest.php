<?php

namespace Tests\Feature;

use App\Models\AuditLog;
use App\Models\User;
use App\Support\Totp;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class StaffTwoFactorTest extends TestCase
{
    use RefreshDatabase;

    private const PASSWORD = 'a-long-staff-password';

    public function test_totp_matches_the_rfc_6238_vectors(): void
    {
        $secret = Totp::base32Encode('12345678901234567890');
        $this->assertSame('GEZDGNBVGY3TQOJQGEZDGNBVGY3TQOJQ', $secret);
        $this->assertSame('287082', Totp::code($secret, intdiv(59, 30)));
        $this->assertSame('081804', Totp::code($secret, intdiv(1111111109, 30)));
        $this->assertSame('005924', Totp::code($secret, intdiv(1234567890, 30)));
        $this->assertSame(intdiv(59, 30), Totp::verify($secret, '287 082', 59));
        $this->assertNull(Totp::verify($secret, '000000', 59));
        $this->assertNull(Totp::verify($secret, 'abcdef', 59));
        $this->assertSame('12345678901234567890', Totp::base32Decode($secret));
    }

    private function enrolledStaff(): array
    {
        $secret = Totp::generateSecret();
        $user = User::factory()->create(['is_admin' => true, 'password' => self::PASSWORD]);
        $user->forceFill(['two_factor_secret' => $secret, 'two_factor_confirmed_at' => now(), 'two_factor_recovery_codes' => [hash('sha256', 'abcde-fghij')]])->save();

        return [$user, $secret];
    }

    public function test_a_password_alone_does_not_sign_in_staff_with_two_factor(): void
    {
        [$user] = $this->enrolledStaff();

        $this->post('/admin/login', ['email' => $user->email, 'password' => self::PASSWORD])->assertRedirect('/admin/two-factor');
        $this->assertGuest();
        $this->get('/admin')->assertRedirect('/admin/login');
        $this->get('/admin/two-factor')->assertOk()->assertSee('Enter your code');
    }

    public function test_a_valid_code_signs_in_once_and_a_replay_is_refused(): void
    {
        [$user, $secret] = $this->enrolledStaff();
        $code = Totp::code($secret, Totp::currentStep());

        $this->post('/admin/login', ['email' => $user->email, 'password' => self::PASSWORD]);
        $this->post('/admin/two-factor', ['code' => '000000'])->assertSessionHasErrors('code');
        $this->assertGuest();
        $this->post('/admin/two-factor', ['code' => $code])->assertRedirect();
        $this->assertAuthenticatedAs($user);

        $this->post('/admin/logout');
        $this->post('/admin/login', ['email' => $user->email, 'password' => self::PASSWORD]);
        $this->post('/admin/two-factor', ['code' => $code])->assertSessionHasErrors('code'); // same time step
        $this->assertGuest();
    }

    public function test_a_recovery_code_works_exactly_once(): void
    {
        [$user] = $this->enrolledStaff();

        $this->post('/admin/login', ['email' => $user->email, 'password' => self::PASSWORD]);
        $this->post('/admin/two-factor', ['code' => 'abcde-fghij'])->assertRedirect();
        $this->assertAuthenticatedAs($user);
        $this->assertNotNull(AuditLog::where('action', 'staff.recovery_code_used')->first());

        $this->post('/admin/logout');
        $this->post('/admin/login', ['email' => $user->email, 'password' => self::PASSWORD]);
        $this->post('/admin/two-factor', ['code' => 'abcde-fghij'])->assertSessionHasErrors('code');
        $this->assertGuest();
    }

    public function test_the_code_page_needs_a_password_first_and_customers_cannot_use_it(): void
    {
        $this->get('/admin/two-factor')->assertRedirect('/admin/login');
        $this->post('/admin/two-factor', ['code' => '123456'])->assertRedirect('/admin/login');

        $customer = User::factory()->create(['password' => self::PASSWORD]);
        $this->post('/admin/login', ['email' => $customer->email, 'password' => self::PASSWORD])->assertSessionHasErrors('email');
        $this->assertGuest();
    }

    public function test_required_two_factor_forces_enrolment_then_allows_the_area(): void
    {
        config(['site.staff_2fa_required' => true]);
        $staff = User::factory()->create(['is_admin' => true, 'password' => self::PASSWORD]);

        $this->post('/admin/login', ['email' => $staff->email, 'password' => self::PASSWORD]);
        $this->get('/admin')->assertRedirect('/admin/two-factor/setup');
        $this->get('/admin/orders')->assertRedirect('/admin/two-factor/setup');

        $this->get('/admin/two-factor/setup')->assertOk()->assertSee('Install an authenticator app');
        $secret = $staff->fresh()->two_factor_secret;
        $this->assertNotEmpty($secret);

        $this->post('/admin/two-factor/setup', ['code' => '000000'])->assertSessionHasErrors('code');
        $this->assertNull($staff->fresh()->two_factor_confirmed_at);

        $this->post('/admin/two-factor/setup', ['code' => Totp::code($secret, Totp::currentStep())])->assertRedirect('/admin/two-factor/setup');
        $this->get('/admin/two-factor/setup')->assertOk()->assertSee('Save your recovery codes');
        $this->assertNotNull($staff->fresh()->two_factor_confirmed_at);
        $this->assertCount(8, $staff->fresh()->two_factor_recovery_codes);

        $this->get('/admin')->assertOk();
    }

    public function test_the_secret_is_stored_encrypted_and_the_reset_command_clears_it(): void
    {
        [$user, $secret] = $this->enrolledStaff();
        $raw = \Illuminate\Support\Facades\DB::table('users')->where('id', $user->id)->value('two_factor_secret');
        $this->assertNotSame($secret, $raw);
        $this->assertStringNotContainsString($secret, (string) $raw);

        $this->artisan('admin:reset-2fa', ['email' => $user->email])->assertSuccessful();
        $this->assertNull($user->fresh()->two_factor_confirmed_at);
        $this->assertNull($user->fresh()->two_factor_secret);
        $this->artisan('admin:reset-2fa', ['email' => 'nobody@example.com'])->assertFailed();
    }
}
