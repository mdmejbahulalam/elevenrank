<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\AuditLog;
use App\Models\Setting;
use App\Services\Releases;
use DomainException;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Illuminate\View\View;

class ReleaseController extends Controller
{
    public function index(Releases $releases): View
    {
        $current = $releases->currentVersion();
        $rows = collect($releases->history())->map(function (string $v) use ($current) {
            $path = "releases/elevenrank-pro-{$v}.zip";

            return [
                'version' => $v, 'current' => $v === $current,
                'released' => date('j M Y', Storage::disk('local')->lastModified($path)),
                'downloads' => (int) Setting::get('downloads.'.$v, '0'),
            ];
        })->all();

        return view('admin.releases', ['rows' => $rows]);
    }

    public function create(Releases $releases): View
    {
        return view('admin.release-new', ['current' => $releases->currentVersion()]);
    }

    public function store(Request $request, Releases $releases): RedirectResponse
    {
        $request->validate(['zip' => ['required', 'file', 'mimes:zip'], 'notes' => ['nullable', 'string', 'max:5000'], 'requires' => ['nullable', 'string', 'max:120']]);

        try {
            $version = $releases->publish($request->file('zip')->getRealPath(), $request->boolean('allow_older'));
        } catch (DomainException $e) {
            return back()->withInput()->withErrors(['zip' => $e->getMessage()]);
        }

        Setting::put('release.notes.'.$version, (string) $request->input('notes'));
        Setting::put('release.requires.'.$version, (string) $request->input('requires'));
        AuditLog::record('release.published', $version);

        return redirect('/admin/releases')->with('status', "Now serving version {$version}.");
    }

    public function restore(Request $request, Releases $releases): RedirectResponse
    {
        try {
            $version = $releases->restore((string) $request->input('version'));
        } catch (DomainException $e) {
            return back()->withErrors(['version' => $e->getMessage()]);
        }
        AuditLog::record('release.restored', $version);

        return redirect('/admin/releases')->with('status', "Now serving version {$version}.");
    }
}
