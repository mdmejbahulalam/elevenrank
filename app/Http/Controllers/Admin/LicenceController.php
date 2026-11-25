<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Licence;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;

class LicenceController extends Controller
{
    public function index(): View
    {
        return view('admin.licences', ['licences' => Licence::withCount('activations')->latest('id')->limit(200)->get()]);
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
        $licence->update(['status' => $request->input('status') === 'active' ? 'active' : 'revoked']);

        return redirect('/admin/licences');
    }
}
