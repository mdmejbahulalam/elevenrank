<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
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
        if (! Auth::attempt($credentials + ['is_admin' => true], $request->boolean('remember'))) {
            return back()->withInput($request->only('email'))->withErrors(['email' => 'Those details do not match a staff account.']);
        }

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
