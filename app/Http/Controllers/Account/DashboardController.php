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
    public function overview(Request $request, Releases $releases): View
    {
        $user = $request->user();

        return view('account.overview', [
            'licences' => $user->licences()->with('plan')->withCount('activations')->latest('id')->get(),
            'version' => $releases->currentVersion(),
            'orders' => $user->orders()->with('plan')->latest('id')->limit(3)->get(),
            'tickets' => $user->tickets()->where('status', '!=', 'closed')->count(),
            'hasSite' => $user->licences()->whereHas('activations')->exists(),
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
        $release = $releases->current();
        $eligible = $this->eligible($request);
        if ($release) {
            $release['size'] = number_format(filesize($release['path']) / 1048576, 2).' MB';
            $release['sha256'] = hash_file('sha256', $release['path']);
        }

        return view('account.downloads', ['release' => $release, 'eligible' => $eligible, 'history' => $eligible ? $releases->history() : []]);
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

        return view('account.checkout', ['plan' => $plan, 'plans' => Plan::where('active', true)->orderBy('sort')->get()]);
    }

    public function placeOrder(Request $request, Plan $plan): RedirectResponse
    {
        abort_unless($plan->active, 404);
        $billing = $request->validate(['billing_country' => ['nullable', 'string', 'max:80']]);
        if (! empty($billing['billing_country'])) {
            $request->user()->forceFill(['billing_country' => $billing['billing_country']])->save();
        }

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
            'billing_address' => ['nullable', 'string', 'max:190'],
        ]);
        $user->forceFill($data)->save();

        return back()->with('status', 'Profile saved.');
    }

    public function updatePassword(Request $request): RedirectResponse
    {
        $data = $request->validate([
            'current_password' => ['required', 'current_password'],
            'password' => ['required', 'confirmed', Password::min(10)],
        ]);
        $request->user()->forceFill(['password' => Hash::make($data['password'])])->save();

        return back()->with('status', 'Password changed.');
    }

    /** Deleting is blocked while a licence is still live, so a customer cannot lose a paid key by accident. */
    public function deleteAccount(Request $request): RedirectResponse
    {
        $request->validate(['delete_password' => ['required', 'current_password']]);
        $user = $request->user();
        if ($this->eligible($request)) {
            return back()->withErrors(['delete' => 'You still have an active licence. Ask support to revoke it first, then delete your account.']);
        }

        AuditLog::record('account.deleted', $user->email);
        Auth::logout();
        $request->session()->invalidate();
        $request->session()->regenerateToken();
        $user->delete();

        return redirect('/');
    }

    public function tickets(Request $request): View
    {
        return view('account.tickets', ['tickets' => $request->user()->tickets()->latest('updated_at')->get()]);
    }

    public function newTicket(Request $request): View
    {
        return view('account.ticket-new', ['sites' => $request->user()->licences()->with('activations')->get()->flatMap(fn ($l) => $l->activations->pluck('site'))->unique()->values()]);
    }

    public function solveTicket(Request $request, Ticket $ticket): RedirectResponse
    {
        abort_unless($ticket->user_id === $request->user()->id, 404);
        $ticket->update(['status' => 'closed']);

        return back()->with('status', 'Marked as solved. Reply on the ticket if it comes back.');
    }

    public function storeTicket(Request $request): RedirectResponse
    {
        $data = $request->validate(['subject' => ['required', 'string', 'max:160'], 'body' => ['required', 'string', 'max:5000'], 'site' => ['nullable', 'string', 'max:190'], 'topic' => ['nullable', 'string', 'max:60']]);
        $ticket = $request->user()->tickets()->create(['subject' => $data['subject']]);
        $body = ! empty($data['site']) ? 'Site: '.$data['site']."\n\n".$data['body'] : $data['body'];
        if (! empty($data['topic'])) {
            $body = 'Topic: '.$data['topic']."\n".$body;
        }
        $ticket->replies()->create(['user_id' => $request->user()->id, 'is_staff' => false, 'body' => $body]);

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
