<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Licence;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;

class LicenceController extends Controller
{
    public function index(Request $request): View
    {
        $q = trim((string) $request->query('q'));
        $status = (string) $request->query('status', '');
        $ends = (string) $request->query('ends', '');
        $licences = Licence::with(['plan', 'user'])->withCount('activations')
            ->when($q !== '', fn ($query) => $query->where(fn ($w) => $w->where('license_key', 'like', "%{$q}%")->orWhere('email', 'like', "%{$q}%")))
            ->when($status === 'revoked', fn ($query) => $query->where('status', 'revoked'))
            ->when($status === 'active', fn ($query) => $query->where('status', 'active')->where(fn ($w) => $w->whereNull('expires')->orWhere('expires', '>', now())))
            ->when($status === 'expired', fn ($query) => $query->where('status', 'active')->where('expires', '<=', now()))
            ->when($ends === '30', fn ($query) => $query->where('status', 'active')->whereBetween('expires', [now(), now()->addDays(30)]))
            ->latest('id')->limit(200)->get();

        return view('admin.licences', compact('licences', 'q', 'status', 'ends'));
    }

    public function show(Licence $licence): View
    {
        return view('admin.licence', ['licence' => $licence->load(['plan', 'user', 'activations']), 'order' => \App\Models\Order::where('licence_id', $licence->id)->first()]);
    }

    public function deactivate(Request $request, Licence $licence): RedirectResponse
    {
        $site = Licence::normaliseSite((string) $request->validate(['site' => ['required', 'string', 'max:190']])['site']);
        $licence->deactivateSite($site);
        \App\Models\AuditLog::record('licence.site_deactivated', $licence->license_key.' '.$site);

        return back()->with('status', 'Site deactivated.');
    }

    public function store(Request $request): RedirectResponse
    {
        $data = $request->validate([
            'email' => ['nullable', 'email', 'max:190'],
            'site_limit' => ['required', 'integer', 'min:0', 'max:1000'], // 0 = unlimited
            'months' => ['required', 'integer', 'min:0', 'max:120'],      // 0 = no expiry
        ]);

        $licence = Licence::issue(
            (string) ($data['email'] ?? ''),
            (int) $data['site_limit'],
            $data['months'] > 0 ? now()->addMonths((int) $data['months']) : null,
            'manual',
        );

        return redirect('/admin/licences')->with('created', $licence->license_key);
    }

    /** Extend the term or change the site limit of an existing licence. */
    public function update(Request $request, Licence $licence): RedirectResponse
    {
        $data = $request->validate([
            'extend_months' => ['nullable', 'integer', 'min:0', 'max:120'],
            'site_limit' => ['nullable', 'integer', 'min:0', 'max:1000'],
        ]);

        if (! empty($data['extend_months'])) {
            $base = $licence->expires && $licence->expires->isFuture() ? $licence->expires : now();
            $licence->expires = $base->copy()->addMonths((int) $data['extend_months']);
        }
        if (isset($data['site_limit'])) {
            $licence->site_limit = (int) $data['site_limit'];
        }
        $licence->save();
        \App\Models\AuditLog::record('licence.updated', $licence->license_key);

        return redirect('/admin/licences');
    }

    public function setStatus(Request $request, Licence $licence): RedirectResponse
    {
        $active = $request->input('status') === 'active';
        $reason = $active ? null : $request->validate(['reason' => ['required', 'string', 'min:3', 'max:255']])['reason'];
        $licence->update(['status' => $active ? 'active' : 'revoked']);
        \App\Models\AuditLog::record($active ? 'licence.reinstated' : 'licence.revoked', $licence->license_key, $reason);
        if (! $active) {
            app(\App\Services\Notifier::class)->revoked($licence);
        }

        return back()->with('status', $active ? 'Licence reinstated.' : 'Licence revoked.');
    }
}
