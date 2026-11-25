<?php

namespace App\Http\Controllers\Account;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Auth\Events\PasswordReset;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Password;
use Illuminate\Support\Str;
use Illuminate\Validation\Rules\Password as PasswordRule;
use Illuminate\View\View;

class PasswordResetController extends Controller
{
    public function request(): View
    {
        return view('account.forgot');
    }

    /** Always answers the same way, so the form cannot be used to find out who has an account. */
    public function send(Request $request): RedirectResponse
    {
        $data = $request->validate(['email' => ['required', 'email']]);
        Password::sendResetLink(['email' => $data['email']]);

        return back()->with('status', 'If that address has an account, we have sent a link to reset the password.');
    }

    public function form(Request $request, string $token): View
    {
        return view('account.reset', ['token' => $token, 'email' => (string) $request->query('email')]);
    }

    public function reset(Request $request): RedirectResponse
    {
        $data = $request->validate([
            'token' => ['required'],
            'email' => ['required', 'email'],
            'password' => ['required', 'confirmed', PasswordRule::min(10)],
        ]);

        $status = Password::reset($data + ['password_confirmation' => $request->input('password_confirmation')], function (User $user, string $password) {
            $user->forceFill(['password' => Hash::make($password), 'remember_token' => Str::random(60)])->save();
            event(new PasswordReset($user));
        });

        return $status === Password::PASSWORD_RESET
            ? redirect('/account/login')->with('status', 'Password changed. You can sign in now.')
            : back()->withInput($request->only('email'))->withErrors(['email' => 'This reset link is invalid or has expired. Ask for a new one.']);
    }
}
