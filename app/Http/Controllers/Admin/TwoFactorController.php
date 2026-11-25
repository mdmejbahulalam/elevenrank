<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\AuditLog;
use App\Models\User;
use App\Support\Totp;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Str;
use Illuminate\View\View;

class TwoFactorController extends Controller
{
    private const WINDOW_SECONDS = 300;

    /** The code prompt shown after a correct password. */
    public function challenge(Request $request): View|RedirectResponse
    {
        return $this->pending($request) ? view('admin.two-factor-challenge') : redirect('/admin/login');
    }

    public function verify(Request $request): RedirectResponse
    {
        $pending = $this->pending($request);
        if (! $pending) {
            return redirect('/admin/login');
        }
        $user = User::where('is_admin', true)->find($pending['id']);
        $code = trim((string) $request->validate(['code' => ['required', 'string', 'max:32']])['code']);

        if (! $user || ! $user->two_factor_confirmed_at || ! $this->accept($user, $code)) {
            return back()->withErrors(['code' => 'That code did not work. Try the next one, or use a recovery code.']);
        }

        $request->session()->forget('staff_2fa');
        Auth::login($user, $pending['remember']);
        $request->session()->regenerate();
        AuditLog::record('staff.signed_in');

        return redirect()->intended('/admin');
    }

    public function setup(Request $request): View|RedirectResponse
    {
        $user = $request->user();
        if ($user->two_factor_confirmed_at && ! session('recovery_codes')) {
            return redirect('/admin')->with('status', 'Two-factor sign-in is already on.');
        }
        if (! $user->two_factor_confirmed_at && ! $user->two_factor_secret) {
            $user->forceFill(['two_factor_secret' => Totp::generateSecret()])->save();
        }

        return view('admin.two-factor-setup', [
            'secret' => $user->two_factor_confirmed_at ? null : $user->two_factor_secret,
            'uri' => $user->two_factor_confirmed_at ? null : Totp::uri($user->two_factor_secret, $user->email),
            'codes' => session('recovery_codes'),
        ]);
    }

    public function confirm(Request $request): RedirectResponse
    {
        $user = $request->user();
        $code = (string) $request->validate(['code' => ['required', 'string', 'max:16']])['code'];
        $step = ($user->two_factor_confirmed_at || ! $user->two_factor_secret) ? null : Totp::verify($user->two_factor_secret, $code);

        if ($step === null) {
            return back()->withErrors(['code' => 'That code did not match. Check the time on your phone and try again.']);
        }

        $codes = collect(range(1, 8))->map(fn () => Str::lower(Str::random(5).'-'.Str::random(5)))->all();
        $user->forceFill([
            'two_factor_confirmed_at' => now(),
            'two_factor_last_step' => $step,
            'two_factor_recovery_codes' => array_map(fn ($c) => hash('sha256', $c), $codes),
        ])->save();
        AuditLog::record('staff.2fa_enabled', $user->email);

        return redirect('/admin/two-factor/setup')->with('recovery_codes', $codes);
    }

    /** A valid authenticator code (each time step usable once) or an unused recovery code. */
    private function accept(User $user, string $code): bool
    {
        $step = Totp::verify($user->two_factor_secret, $code);
        if ($step !== null && $step > (int) $user->two_factor_last_step) {
            $user->forceFill(['two_factor_last_step' => $step])->save();

            return true;
        }

        $hash = hash('sha256', Str::lower(trim($code)));
        $codes = $user->two_factor_recovery_codes ?? [];
        if (in_array($hash, $codes, true)) {
            $user->forceFill(['two_factor_recovery_codes' => array_values(array_diff($codes, [$hash]))])->save();
            AuditLog::record('staff.recovery_code_used', $user->email);

            return true;
        }

        return false;
    }

    /** @return array{id:int, remember:bool, at:int}|null */
    private function pending(Request $request): ?array
    {
        $p = $request->session()->get('staff_2fa');

        return is_array($p) && ($p['at'] ?? 0) > now()->timestamp - self::WINDOW_SECONDS ? $p : null;
    }
}
