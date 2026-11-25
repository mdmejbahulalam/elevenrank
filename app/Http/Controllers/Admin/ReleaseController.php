<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Services\Releases;
use DomainException;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;

class ReleaseController extends Controller
{
    public function index(Releases $releases): View
    {
        return view('admin.releases', ['current' => $releases->currentVersion(), 'history' => $releases->history()]);
    }

    public function store(Request $request, Releases $releases): RedirectResponse
    {
        $request->validate(['zip' => ['required', 'file', 'mimes:zip']]);

        try {
            $version = $releases->publish($request->file('zip')->getRealPath(), $request->boolean('allow_older'));
        } catch (DomainException $e) {
            return back()->withErrors(['zip' => $e->getMessage()]);
        }

        return redirect('/admin/releases')->with('status', "Now serving version {$version}.");
    }

    public function restore(Request $request, Releases $releases): RedirectResponse
    {
        try {
            $version = $releases->restore((string) $request->input('version'));
        } catch (DomainException $e) {
            return back()->withErrors(['version' => $e->getMessage()]);
        }

        return redirect('/admin/releases')->with('status', "Now serving version {$version}.");
    }
}
