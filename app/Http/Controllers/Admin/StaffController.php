<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Activation;
use App\Models\AuditLog;
use App\Models\Licence;
use App\Models\Order;
use App\Models\Plan;
use App\Models\Ticket;
use App\Models\User;
use App\Services\Fulfilment;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Artisan;
use Illuminate\View\View;

/** Overview, customers, orders, plans, tickets, audit log and system tools for staff. */
class StaffController extends Controller
{
    public function overview(): View
    {
        $paid = Order::where('status', 'paid');

        return view('admin.overview', [
            'customers' => User::where('is_admin', false)->count(),
            'licences' => Licence::where('status', 'active')->count(),
            'sites' => Activation::count(),
            'pending' => Order::where('status', 'pending')->count(),
            'revenue' => (clone $paid)->where('paid_at', '>=', now()->startOfMonth())->sum('amount_cents') / 100,
            'openTickets' => Ticket::where('status', '!=', 'closed')->count(),
            'expiring' => Licence::where('status', 'active')->whereBetween('expires', [now(), now()->addDays(30)])->count(),
        ]);
    }

    public function customers(Request $request): View
    {
        $q = trim((string) $request->query('q'));
        $users = User::where('is_admin', false)->withCount(['licences', 'orders'])
            ->when($q !== '', fn ($query) => $query->where(fn ($w) => $w->where('email', 'like', "%{$q}%")->orWhere('name', 'like', "%{$q}%")))
            ->latest('id')->limit(200)->get();

        return view('admin.customers', ['users' => $users, 'q' => $q]);
    }

    public function customer(User $user): View
    {
        return view('admin.customer', ['user' => $user, 'licences' => $user->licences()->withCount('activations')->get(), 'orders' => $user->orders()->with('plan')->latest('id')->get()]);
    }

    public function orders(Request $request): View
    {
        $status = (string) $request->query('status', '');
        $orders = Order::with(['user', 'plan'])->when($status !== '', fn ($q) => $q->where('status', $status))->latest('id')->limit(200)->get();

        return view('admin.orders', ['orders' => $orders, 'status' => $status]);
    }

    public function orderAction(Request $request, Order $order, Fulfilment $fulfilment): RedirectResponse
    {
        match ($request->validate(['do' => ['required', 'in:paid,refund,cancel']])['do']) {
            'paid' => $fulfilment->markPaid($order, (string) $request->input('reference')),
            'refund' => $fulfilment->refund($order),
            'cancel' => $fulfilment->cancel($order),
        };

        return back()->with('status', 'Order '.$order->number.' updated.');
    }

    public function plans(): View
    {
        return view('admin.plans', ['plans' => Plan::orderBy('sort')->get()]);
    }

    public function updatePlan(Request $request, Plan $plan): RedirectResponse
    {
        $data = $request->validate([
            'name' => ['required', 'string', 'max:80'],
            'sites' => ['required', 'integer', 'min:0', 'max:1000'],
            'price' => ['required', 'numeric', 'min:0', 'max:100000'],
            'months' => ['required', 'integer', 'min:0', 'max:120'],
        ]);

        $plan->update([
            'name' => $data['name'], 'sites' => (int) $data['sites'], 'months' => (int) $data['months'],
            'price_cents' => (int) round($data['price'] * 100), 'active' => $request->boolean('active'),
        ]);
        AuditLog::record('plan.updated', $plan->slug);

        return back()->with('status', $plan->name.' saved.');
    }

    public function tickets(Request $request): View
    {
        $status = (string) $request->query('status', 'open');
        $tickets = Ticket::with('user')->when($status !== 'all', fn ($q) => $q->where('status', $status))->latest('updated_at')->limit(200)->get();

        return view('admin.tickets', ['tickets' => $tickets, 'status' => $status]);
    }

    public function ticket(Ticket $ticket): View
    {
        return view('admin.ticket', ['ticket' => $ticket->load(['user', 'replies.user'])]);
    }

    public function replyTicket(Request $request, Ticket $ticket): RedirectResponse
    {
        $data = $request->validate(['body' => ['required', 'string', 'max:5000']]);
        $ticket->replies()->create(['user_id' => $request->user()->id, 'is_staff' => true, 'body' => $data['body']]);
        $ticket->update(['status' => $request->boolean('close') ? 'closed' : 'answered']);
        AuditLog::record('ticket.replied', '#'.$ticket->id);

        return back()->with('status', 'Reply sent.');
    }

    public function audit(): View
    {
        return view('admin.audit', ['logs' => AuditLog::with('user')->latest('id')->limit(200)->get()]);
    }

    public function system(): View
    {
        return view('admin.system', [
            'php' => PHP_VERSION, 'laravel' => app()->version(), 'env' => app()->environment(),
            'mail' => config('mail.default'), 'db' => config('database.default'),
            'provider' => config('services.lemonsqueezy.secret') ? 'Lemon Squeezy webhook configured' : 'Manual (staff mark orders paid)',
        ]);
    }

    public function runSystem(Request $request): RedirectResponse
    {
        $action = $request->validate(['do' => ['required', 'in:migrate,clear']])['do'];

        if ($action === 'migrate') {
            Artisan::call('migrate', ['--force' => true]);
        } else {
            // Routes, config and views may be stale after a new upload.
            foreach (glob(base_path('bootstrap/cache/*.php')) ?: [] as $file) {
                @unlink($file);
            }
            Artisan::call('view:clear');
            Artisan::call('cache:clear');
        }
        AuditLog::record('system.'.$action);

        return back()->with('status', trim(Artisan::output()) ?: 'Done.');
    }
}
