<?php

namespace App\Providers;

use App\Models\User;
use Illuminate\Support\Facades\Gate;
use Illuminate\Support\ServiceProvider;

class AppServiceProvider extends ServiceProvider
{
    /** Staff abilities and the roles that hold them. super_admin holds every one. */
    public const ABILITIES = [
        'staff.overview' => ['support', 'finance'],
        'staff.search' => ['support', 'finance'],
        'staff.customers' => ['support'],
        'staff.orders.view' => ['support', 'finance'],
        'staff.orders.settle' => ['finance'],   // mark paid, cancel
        'staff.orders.refund' => ['finance'],
        'staff.licences' => ['support'],
        'staff.tickets' => ['support'],
        'staff.plans' => ['finance'],
        'staff.releases' => [],
        'staff.system' => [],
        'staff.audit' => [],
        'staff.staff' => [],
        'staff.webhooks' => [],
    ];

    public function register(): void
    {
        //
    }

    public function boot(): void
    {
        foreach (self::ABILITIES as $ability => $roles) {
            Gate::define($ability, fn (User $user) => $user->is_admin && in_array($user->staffRole(), ['super_admin', ...$roles], true));
        }
    }
}
