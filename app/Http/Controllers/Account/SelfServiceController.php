<?php

namespace App\Http\Controllers\Account;

use App\Http\Controllers\Controller;
use App\Models\AuditLog;
use App\Models\Licence;
use App\Models\Order;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;

class SelfServiceController extends Controller
{
    public function invoice(Request $request, Order $order): View
    {
        abort_unless($order->user_id === $request->user()->id && $order->status !== 'pending', 404);

        return view('account.order', ['order' => $order->load(['plan', 'user', 'licence'])]);
    }

    /** Provider-neutral: records the request as a ticket for staff to act on. Only paid orders inside 14 days. */
    public function requestRefund(Request $request, Order $order): RedirectResponse
    {
        abort_unless($order->user_id === $request->user()->id, 404);

        if ($order->status !== 'paid' || ! $order->paid_at || $order->paid_at->lt(now()->subDays(14))) {
            return back()->withErrors(['refund' => 'This order is not eligible for a refund request.']);
        }

        $ticket = $request->user()->tickets()->create(['subject' => 'Refund request for '.$order->number]);
        $ticket->replies()->create(['user_id' => $request->user()->id, 'is_staff' => false, 'body' => 'Please refund order '.$order->number.'.']);
        AuditLog::record('refund.requested', $order->number);

        return redirect('/account/tickets/'.$ticket->id)->with('status', 'Refund request sent. We will reply here.');
    }

    public function renameLicence(Request $request, Licence $licence): RedirectResponse
    {
        abort_unless($licence->user_id === $request->user()->id, 404);
        $data = $request->validate(['label' => ['nullable', 'string', 'max:80']]);
        $licence->update(['label' => $data['label'] ?: null]);

        return back()->with('status', 'Label saved.');
    }

    /** Everything we hold about the signed-in customer, as JSON. Licence keys are included: it is their own data. */
    public function export(Request $request): JsonResponse
    {
        $u = $request->user();

        return response()->json([
            'account' => $u->only(['name', 'email', 'created_at']),
            'orders' => $u->orders()->with('plan')->get()->map(fn ($o) => ['number' => $o->number, 'plan' => $o->plan->name, 'amount' => $o->amount(), 'status' => $o->status, 'placed' => $o->created_at])->all(),
            'licences' => $u->licences()->with('activations')->get()->map(fn ($l) => ['key' => $l->license_key, 'label' => $l->label, 'status' => $l->status, 'expires' => $l->expires, 'sites' => $l->activations->pluck('site')->all()])->all(),
            'tickets' => $u->tickets()->with('publicReplies')->get()->map(fn ($t) => ['subject' => $t->subject, 'status' => $t->status, 'replies' => $t->publicReplies->map(fn ($r) => ['staff' => $r->is_staff, 'body' => $r->body, 'at' => $r->created_at])->all()])->all(),
        ], 200, ['Content-Disposition' => 'attachment; filename="elevenrank-my-data.json"'], JSON_PRETTY_PRINT);
    }
}
