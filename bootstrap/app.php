<?php

use Illuminate\Foundation\Application;
use Illuminate\Foundation\Configuration\Exceptions;
use Illuminate\Foundation\Configuration\Middleware;

return Application::configure(basePath: dirname(__DIR__))
    ->withRouting(
        web: __DIR__.'/../routes/web.php',
        // Shipped Pro copies call this path, so it stays the same as the old WordPress server's.
        api: __DIR__.'/../routes/api.php',
        apiPrefix: 'wp-json/elevenrank-licence/v1',
        commands: __DIR__.'/../routes/console.php',
        health: '/up',
    )
    ->withMiddleware(function (Middleware $middleware): void {
        $middleware->redirectGuestsTo(fn ($request) => $request->is('admin*') ? '/admin/login' : '/account/login');
        $middleware->validateCsrfTokens(except: ['webhooks/*']);
        $middleware->web(append: [\App\Http\Middleware\EnsureNotDisabled::class]);
        $middleware->alias(['admin' => \App\Http\Middleware\EnsureAdmin::class, 'staff2fa' => \App\Http\Middleware\EnsureStaffTwoFactor::class]);
    })
    ->withExceptions(function (Exceptions $exceptions): void {
        // Staff who open an area their role does not cover see a calm page, and the attempt is recorded.
        $exceptions->render(function (\Symfony\Component\HttpKernel\Exception\AccessDeniedHttpException $e, \Illuminate\Http\Request $request) {
            $user = $request->user();
            if (! $request->is('admin/*') || ! $user?->is_admin || $request->expectsJson()) {
                return null;
            }
            $areas = [
                'customers' => ['Customers', 'staff.customers'], 'licences' => ['Licences', 'staff.licences'], 'orders' => ['Orders', 'staff.orders.view'],
                'plans' => ['Plans', 'staff.plans'], 'tickets' => ['Tickets', 'staff.tickets'], 'releases' => ['Releases', 'staff.releases'],
                'audit' => ['the audit log', 'staff.audit'], 'audit.csv' => ['the audit log', 'staff.audit'], 'system' => ['System', 'staff.system'], 'settings' => ['Settings', 'staff.system'],
                'staff' => ['Staff', 'staff.staff'], 'webhooks' => ['Webhooks', 'staff.webhooks'], 'search' => ['Search', 'staff.search'],
            ];
            [$area, $ability] = $areas[$request->segment(2)] ?? ['this page', null];
            $roles = $ability ? array_merge(\App\Providers\AppServiceProvider::ABILITIES[$ability] ?? [], ['super_admin']) : ['super_admin'];
            $names = ['super_admin' => 'super admin', 'support' => 'support', 'finance' => 'finance'];
            $list = collect($roles)->map(fn ($r) => $names[$r])->sort()->values();
            $who = 'This area is for '.($list->count() > 1 ? $list->slice(0, -1)->implode(', ').' and '.$list->last() : $list->first()).' staff.';
            \App\Models\AuditLog::record('staff.denied', '/'.$request->path());

            return response()->view('admin.no-access', ['area' => $area, 'who' => $who], 403);
        });
    })->create();
