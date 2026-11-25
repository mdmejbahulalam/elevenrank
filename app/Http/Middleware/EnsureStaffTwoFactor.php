<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

/** Staff without two-factor can only reach the page that sets it up (and sign out). */
class EnsureStaffTwoFactor
{
    public function handle(Request $request, Closure $next): Response
    {
        $user = $request->user();

        if ($user && config('site.staff_2fa_required') && ! $user->two_factor_confirmed_at
            && ! $request->is('admin/two-factor*', 'admin/logout')) {
            return redirect('/admin/two-factor/setup')->with('status', 'Set up two-factor sign-in to use the staff area.');
        }

        return $next($request);
    }
}
