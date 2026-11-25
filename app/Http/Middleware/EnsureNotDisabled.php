<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

/** Signs out a disabled account on its next request. */
class EnsureNotDisabled
{
    public function handle(Request $request, Closure $next)
    {
        if ($request->user()?->disabled_at) {
            Auth::logout();
            $request->session()->invalidate();
            $request->session()->regenerateToken();

            return redirect($request->is('admin*') ? '/admin/login' : '/account/login')->withErrors(['email' => 'This account is disabled. Contact support.']);
        }

        return $next($request);
    }
}
