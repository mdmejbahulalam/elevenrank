<?php

namespace App\Http\Controllers\Account;

use App\Http\Controllers\Controller;
use App\Models\AuditLog;
use App\Models\Licence;
use App\Models\Order;
use App\Models\Plan;
use App\Models\Ticket;
use App\Services\Releases;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\Rules\Password;
use Illuminate\View\View;
use Symfony\Component\HttpFoundation\BinaryFileResponse;

class DashboardController extends Controller
{
    public function overview(Request $request): View
    {
        $user = $request->user();

        return view('account.overview', [
            'licences' => $user->licences()->withCount('activations')->latest('id')->get(),
            'orders' => $user->orders()->with('plan')->latest('id')->limit(3)->get(),
            'tickets' => $user->tickets()->where('status', '!=', 'closed')->count(),
        ]);
    }

    public function licences(Request $request): View
    {
        return view('account.licences', ['licences' => $request->user()->licences()->with('activations')->latest('id')->get()]);
    }

    public function deactivateSite(Request $request, Licence $licence): RedirectResponse
    {
        abort_unless($licence->user_id === $request->user()->id, 404);
        $licence->deactivateSite(Licence::normaliseSite((string) $request->input('site')));

        return back()->with('status', 'Site deactivated. The licence can be used on another site.');
    }

    public function downloads(Request $request, Releases $releases): View
    {
        return view('account.downloads', ['release' => $releases->current(), 'eligible' => $this->eligible($request)]);
    }

    public function download(Request $request, Releases $releases): BinaryFileResponse
    {
        $info = $releases->current();
        abort_unless($info && $this->eligible($request), 403);

        return response()->download($info['path'], 'elevenrank-pro.zip', ['Content-Type' => 'application/zip', 'Cache-Control' => 'no-store']);
    }

    public function orders(Request $request): View
    {
        return view('account.orders', ['orders' => $request->user()->orders()->with(['plan', 'licence'])->latest('id')->get()]);
    }

    public function order(Request $request, Order $order): View
    {
        abort_unless($order->user_id === $request->user()->id, 404);

        return view('account.order', ['order' => $order->load(['plan', 'licence'])]);
    }

    public function checkout(Plan $plan): View
    {
        abort_unless($plan->active, 404);

        return view('account.checkout', ['plan' => $plan]);
    }

    public function placeOrder(Request $request, Plan $plan): RedirectResponse
    {
        abort_unless($plan->active, 404);

        $order = Order::create([
            'number' => Order::nextNumber(),
            'user_id' => $request->user()->id,
            'plan_id' => $plan->id,
            'amount_cents' => $plan->price_cents,
            'currency' => $plan->currency,
        ]);
        AuditLog::record('order.placed', $order->number);

        return redirect('/account/orders/'.$order->number)->with('status', 'Order placed.');
    }

    public function profile(): View
    {
        return view('account.profile');
    }

    public function updateProfile(Request $request): RedirectResponse
    {
        $user = $request->user();
        $data = $request->validate([
            'name' => ['required', 'string', 'max:120'],
            'email' => ['required', 'email', 'max:190', 'unique:users,email,'.$user->id],
            'current_password' => ['nullable', 'current_password'],
            'password' => ['nullable', 'confirmed', Password::min(10)],
        ]);

        if (! empty($data['password']) && empty($data['current_password'])) {
            return back()->withErrors(['current_password' => 'Enter your current password to set a new one.']);
        }

        $user->forceFill(['name' => $data['name'], 'email' => $data['email']]);
        if (! empty($data['password'])) {
            $user->password = Hash::make($data['password']);
        }
        $user->save();

        return back()->with('status', 'Profile saved.');
    }

    public function tickets(Request $request): View
    {
        return view('account.tickets', ['tickets' => $request->user()->tickets()->latest('updated_at')->get()]);
    }

    public function storeTicket(Request $request): RedirectResponse
    {
        $data = $request->validate(['subject' => ['required', 'string', 'max:160'], 'body' => ['required', 'string', 'max:5000']]);
        $ticket = $request->user()->tickets()->create(['subject' => $data['subject']]);
        $ticket->replies()->create(['user_id' => $request->user()->id, 'is_staff' => false, 'body' => $data['body']]);

        return redirect('/account/tickets/'.$ticket->id)->with('status', 'Ticket opened. We will reply here.');
    }

    public function ticket(Request $request, Ticket $ticket): View
    {
        abort_unless($ticket->user_id === $request->user()->id, 404);

        return view('account.ticket', ['ticket' => $ticket->load('replies.user')]);
    }

    public function replyTicket(Request $request, Ticket $ticket): RedirectResponse
    {
        abort_unless($ticket->user_id === $request->user()->id, 404);
        $data = $request->validate(['body' => ['required', 'string', 'max:5000']]);

        $ticket->replies()->create(['user_id' => Auth::id(), 'is_staff' => false, 'body' => $data['body']]);
        $ticket->update(['status' => 'open']);

        return back()->with('status', 'Reply sent.');
    }

    /** Downloads need a licence that is active and inside its term. */
    private function eligible(Request $request): bool
    {
        return $request->user()->licences()->where('status', 'active')->get()->contains(fn (Licence $l) => ! $l->isExpired());
    }
}
