<?php

namespace App\Console\Commands;

use App\Models\User;
use Illuminate\Console\Command;

class CreateAdmin extends Command
{
    protected $signature = 'admin:create {email} {--name=Admin} {--role=super_admin : super_admin, support or finance}';

    protected $description = 'Creates a staff account, or promotes an existing user, and asks for the password.';

    public function handle(): int
    {
        if (! in_array($this->option('role'), User::ROLES, true)) {
            $this->error('The role must be one of: '.implode(', ', User::ROLES).'.');

            return self::FAILURE;
        }

        $password = (string) $this->secret('Password (at least 12 characters)');

        if (strlen($password) < 12) {
            $this->error('The password must be at least 12 characters.');

            return self::FAILURE;
        }

        $user = User::firstOrNew(['email' => $this->argument('email')]);
        $user->name = $user->name ?: $this->option('name');
        $user->password = $password;
        $user->is_admin = true;
        $user->role = $this->option('role');
        $user->save();

        $this->info("{$user->email} can now sign in at /admin/login.");

        return self::SUCCESS;
    }
}
