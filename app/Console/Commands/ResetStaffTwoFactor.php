<?php

namespace App\Console\Commands;

use App\Models\User;
use Illuminate\Console\Command;

class ResetStaffTwoFactor extends Command
{
    protected $signature = 'admin:reset-2fa {email}';

    protected $description = 'Turns off two-factor for a staff account that lost its device. They must enrol again at next sign-in.';

    public function handle(): int
    {
        $user = User::where('is_admin', true)->where('email', $this->argument('email'))->first();
        if (! $user) {
            $this->error('No staff account with that email.');

            return self::FAILURE;
        }
        $user->forceFill(['two_factor_secret' => null, 'two_factor_recovery_codes' => null, 'two_factor_confirmed_at' => null, 'two_factor_last_step' => null])->save();
        $this->info("Two-factor reset for {$user->email}.");

        return self::SUCCESS;
    }
}
