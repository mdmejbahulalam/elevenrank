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

    public function setStatus(Request $request, Licence $licence): RedirectResponse
    {
        $licence->update(['status' => $request->input('status') === 'active' ? 'active' : 'revoked']);

        return redirect('/admin/licences');
    }
}
