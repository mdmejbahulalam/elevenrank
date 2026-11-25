<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\AuditLog;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\View\View;

class AuthController extends Controller
{
    public function show(): View
    {
        return view('admin.login');
    }

    public function login(Request $request): RedirectResponse
    {
        $credentials = $request->validate(['email' => ['required', 'email'], 'password' => ['required']]);

        // Only staff sign in here; a customer's correct password must not open the admin area.
        $provider = Auth::getProvider();
        $user = $provider->retrieveByCredentials($credentials + ['is_admin' => true]);

        if (! $user || ! $provider->validateCredentials($user, $credentials)) {
            return back()->withInput($request->only('email'))->withErrors(['email' => 'Those details do not match a staff account.']);
        }

        // With two-factor on, the password only earns a code prompt; nobody is signed in yet.
        if ($user->two_factor_confirmed_at) {
            // The code can be typed on this page; if it was left empty the next page asks for it.
            $code = trim((string) $request->input('code'));
            if ($code !== '') {
                if (! app(TwoFactorController::class)->accept($user, $code)) {
                    return back()->withInput($request->only('email'))->withErrors(['code' => 'That code did not work. Try the next one, or use a recovery code.']);
                }
                Auth::login($user, $request->boolean('remember'));
                $user->forceFill(['last_login_at' => now()])->save();
                $request->session()->regenerate();
                AuditLog::record('staff.signed_in');

                return redirect()->intended('/admin');
            }

            $request->session()->regenerate();
            $request->session()->put('staff_2fa', ['id' => $user->id, 'remember' => $request->boolean('remember'), 'at' => now()->timestamp]);

            return redirect('/admin/two-factor');
        }

        Auth::login($user, $request->boolean('remember'));
        $user->forceFill(["last_login_at" => now()])->save();
        $request->session()->regenerate();

        return redirect()->intended('/admin/releases');
    }

    public function logout(Request $request): RedirectResponse
    {
        Auth::logout();
        $request->session()->invalidate();
        $request->session()->regenerateToken();

        return redirect('/admin/login');
    }
}
