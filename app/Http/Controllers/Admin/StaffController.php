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
use Illuminate\Support\Facades\Gate;
use Symfony\Component\HttpFoundation\StreamedResponse;
use Illuminate\View\View;

/** Overview, customers, orders, plans, tickets, audit log and system tools for staff. */
class StaffController extends Controller
{
    public function overview(): View
    {
        $weeks = collect(range(7, 0))->map(function ($i) {
            $from = now()->startOfWeek()->subWeeks($i);

            return Order::where('created_at', '>=', $from)->where('created_at', '<', $from->copy()->addWeek())->count();
        });
        $oldest = Ticket::where('status', 'open')->oldest('updated_at')->first();

        return view('admin.overview', [
            'licences' => Licence::where('status', 'active')->count(),
            'revenue' => Order::where('status', 'paid')->where('paid_at', '>=', now()->subDays(30))->sum('amount_cents') / 100,
            'openTickets' => Ticket::where('status', '!=', 'closed')->count(),
            'pending' => Order::where('status', 'pending')->count(),
            'weeks' => $weeks,
            'refunds' => Ticket::where('status', '!=', 'closed')->where('subject', 'like', '%refund%')->count(),
            'waiting' => Ticket::where('status', 'open')->count(),
            'oldestDays' => $oldest ? (int) $oldest->updated_at->diffInDays(now()) : 0,
            'expiring' => Licence::where('status', 'active')->whereBetween('expires', [now(), now()->addDays(30)])->count(),
            'activity' => AuditLog::with('user')->latest('id')->limit(5)->get(),
        ]);
    }

    public function customers(Request $request): View
    {
        $q = trim((string) $request->query('q'));
        $plan = $request->query('plan');
        $users = User::where('is_admin', false)->withCount(['licences', 'orders'])->with(['licences' => fn ($l) => $l->with('plan')->latest('id')])
            ->when($q !== '', fn ($query) => $query->where(fn ($w) => $w->where('email', 'like', "%{$q}%")->orWhere('name', 'like', "%{$q}%")))
            ->when($plan, fn ($query) => $query->whereHas('licences', fn ($l) => $l->where('plan_id', $plan)))
            ->latest('id')->limit(200)->get();

        return view('admin.customers', ['users' => $users, 'q' => $q, 'plan' => $plan, 'plans' => Plan::orderBy('sort')->get()]);
    }

    public function customer(User $user): View
    {
        return view('admin.customer', [
            'user' => $user,
            'licences' => $user->licences()->with('plan')->withCount('activations')->get(),
            'orders' => $user->orders()->with('plan')->latest('id')->get(),
            'tickets' => $user->tickets()->latest('updated_at')->get(),
        ]);
    }

    public function saveNote(Request $request, User $user): RedirectResponse
    {
        $user->forceFill(['staff_note' => $request->validate(['staff_note' => ['nullable', 'string', 'max:1000']])['staff_note']])->save();
        AuditLog::record('customer.note', $user->email);

        return back()->with('status', 'Note saved.');
    }

    public function orders(Request $request): View
    {
        $status = (string) $request->query('status', '');
        $q = trim((string) $request->query('q'));
        $range = (string) $request->query('range', '');
        $orders = Order::with(['user', 'plan'])
            ->when($status !== '', fn ($query) => $query->where('status', $status))
            ->when($q !== '', fn ($query) => $query->where(fn ($w) => $w->where('number', 'like', "%{$q}%")->orWhereHas('user', fn ($u) => $u->where('email', 'like', "%{$q}%")->orWhere('name', 'like', "%{$q}%"))))
            ->when(ctype_digit($range) && $range !== '', fn ($query) => $query->where('created_at', '>=', now()->subDays((int) $range)))
            ->latest('id')->limit(200)->get();

        return view('admin.orders', ['orders' => $orders, 'status' => $status, 'q' => $q, 'range' => $range]);
    }

    public function order(Order $order): View
    {
        $refundAsked = Ticket::where('user_id', $order->user_id)->where('status', '!=', 'closed')->where('subject', 'like', '%refund%')->latest('id')->first();

        return view('admin.order', ['order' => $order->load(['user', 'plan', 'licence']), 'refundAsked' => $refundAsked]);
    }

    public function resendReceipt(Order $order, \App\Services\Notifier $notifier): RedirectResponse
    {
        abort_unless($order->status === 'paid', 422);
        $notifier->receipt($order);
        AuditLog::record('order.receipt_resent', $order->number);

        return back()->with('status', 'Receipt sent again.');
    }

    public function orderAction(Request $request, Order $order, Fulfilment $fulfilment): RedirectResponse
    {
        $do = $request->validate(['do' => ['required', 'in:paid,refund,cancel']])['do'];
        Gate::authorize($do === 'refund' ? 'staff.orders.refund' : 'staff.orders.settle');

        if ($do === 'refund') {
            $reason = $request->validate(['reason' => ['required', 'string', 'min:3', 'max:255']])['reason'];
            $fulfilment->refund($order, $reason);
        } elseif ($do === 'paid') {
            $fulfilment->markPaid($order, (string) $request->input('reference'));
        } else {
            $fulfilment->cancel($order);
        }

        return back()->with('status', 'Order '.$order->number.' updated.');
    }

    /** One box that finds customers, licence keys, sites, orders and tickets. */
    public function search(Request $request): View
    {
        $q = trim((string) $request->query('q'));
        $r = ['customers' => collect(), 'licences' => collect(), 'orders' => collect(), 'tickets' => collect()];

        if (strlen($q) >= 2) {
            $like = '%'.str_replace(['%', '_'], ['\\%', '\\_'], $q).'%';
            if (Gate::allows('staff.customers')) {
                $r['customers'] = User::where('is_admin', false)->where(fn ($w) => $w->where('email', 'like', $like)->orWhere('name', 'like', $like))->limit(20)->get();
            }
            if (Gate::allows('staff.licences')) {
                $r['licences'] = Licence::where('license_key', 'like', $like)->orWhere('email', 'like', $like)->orWhereHas('activations', fn ($a) => $a->where('site', 'like', $like))->limit(20)->get();
            }
            if (Gate::allows('staff.orders.view')) {
                $r['orders'] = Order::with('user')->where('number', 'like', $like)->limit(20)->get();
            }
            if (Gate::allows('staff.tickets')) {
                $r['tickets'] = Ticket::with('user')->where('subject', 'like', $like)->limit(20)->get();
            }
        }

        return view('admin.search', ['q' => $q] + $r);
    }

    public function ordersCsv(): StreamedResponse
    {
        AuditLog::record('orders.exported');

        return response()->streamDownload(function () {
            $out = fopen('php://output', 'w');
            fputcsv($out, ['number', 'customer', 'plan', 'amount', 'status', 'placed']);
            foreach (Order::with(['user', 'plan'])->latest('id')->cursor() as $o) {
                // Prefix cells that a spreadsheet could read as a formula.
                $safe = fn (string $v) => preg_match('/^[=+\-@]/', $v) ? "'".$v : $v;
                fputcsv($out, [$o->number, $safe($o->user->email), $o->plan->name, $o->amount(), $o->status, $o->created_at->format('Y-m-d')]);
            }
            fclose($out);
        }, 'orders.csv', ['Content-Type' => 'text/csv']);
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
        app(\App\Services\Notifier::class)->ticketReply($ticket->load('user'), $data['body']);

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
