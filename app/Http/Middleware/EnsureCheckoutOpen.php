<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;

/** Keeps checkout closed until the payment provider is set up (CHECKOUT_OPEN=true). */
class EnsureCheckoutOpen
{
    public function handle(Request $request, Closure $next)
    {
        abort_unless(config('services.checkout_open'), 404);

        return $next($request);
    }
}
