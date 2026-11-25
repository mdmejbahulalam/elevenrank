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
        $middleware->alias(['admin' => \App\Http\Middleware\EnsureAdmin::class, 'staff2fa' => \App\Http\Middleware\EnsureStaffTwoFactor::class]);
    })
    ->withExceptions(function (Exceptions $exceptions): void {
        //
    })->create();
