<?php

namespace App\Http\Controllers\Account;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use App\Services\Notifier;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\URL;
use Illuminate\Validation\Rules\Password;
use Illuminate\View\View;

class AuthController extends Controller
{
    public function login(): View
    {
        return view('account.login');
    }

    public function authenticate(Request $request): RedirectResponse
    {
        $credentials = $request->validate(['email' => ['required', 'email'], 'password' => ['required']]);

        if (! Auth::attempt($credentials, $request->boolean('remember'))) {
            return back()->withInput($request->only('email'))->withErrors(['email' => 'Those details do not match an account.']);
        }

        $request->session()->regenerate();

        return redirect()->intended('/account');
    }

    public function register(): View
    {
        return view('account.register');
    }

    public function store(Request $request): RedirectResponse
    {
        $data = $request->validate([
            'name' => ['required', 'string', 'max:120'],
            'email' => ['required', 'email', 'max:190', 'unique:users,email'],
            'password' => ['required', Password::min(10)],
        ]);

        $user = User::create($data); // is_admin is not fillable and defaults to false.
        Auth::login($user);
        $request->session()->regenerate();
        app(Notifier::class)->welcome($user);
        $this->sendVerification($user);

        return redirect()->intended('/account');
    }

    private function sendVerification(User $user): void
    {
        if (config('services.verify_email') && ! $user->email_verified_at) {
            app(Notifier::class)->verify($user, URL::temporarySignedRoute('account.verify', now()->addDay(), ['user' => $user->id]));
        }
    }

    public function verify(Request $request, User $user): RedirectResponse
    {
        $user->forceFill(['email_verified_at' => $user->email_verified_at ?? now()])->save();

        return redirect(Auth::check() ? '/account' : '/account/login')->with('status', 'Email confirmed.');
    }

    public function resend(Request $request): RedirectResponse
    {
        $this->sendVerification($request->user());

        return back()->with('status', 'Confirmation email sent.');
    }

    public function logout(Request $request): RedirectResponse
    {
        Auth::logout();
        $request->session()->invalidate();
        $request->session()->regenerateToken();

        return redirect('/');
    }
}
