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
            'failedHooks' => \App\Models\WebhookEvent::where('status', 'failed')->count(),
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

    public function storePlan(Request $request): RedirectResponse
    {
        $data = $request->validate([
            'name' => ['required', 'string', 'max:80'], 'sites' => ['required', 'integer', 'min:0', 'max:1000'],
            'price' => ['required', 'numeric', 'min:0', 'max:100000'], 'months' => ['required', 'integer', 'min:0', 'max:120'],
        ]);
        $slug = \Illuminate\Support\Str::slug($data['name']) ?: 'plan';
        $slug = Plan::where('slug', $slug)->exists() ? $slug.'-'.\Illuminate\Support\Str::lower(\Illuminate\Support\Str::random(4)) : $slug;
        Plan::create(['slug' => $slug, 'name' => $data['name'], 'sites' => (int) $data['sites'], 'months' => (int) $data['months'],
            'price_cents' => (int) round($data['price'] * 100), 'currency' => 'USD', 'active' => $request->boolean('active'), 'sort' => (int) Plan::max('sort') + 1]);
        AuditLog::record('plan.created', $slug);

        return back()->with('status', $data['name'].' added.');
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
        $q = trim((string) $request->query('q'));
        $status = (string) $request->query('status', '');
        $assigned = (string) $request->query('assigned', '');
        $tickets = Ticket::with(['user', 'assignee'])
            ->when($q !== '', fn ($query) => $query->where(fn ($w) => $w->where('subject', 'like', "%{$q}%")->orWhereHas('user', fn ($u) => $u->where('email', 'like', "%{$q}%")->orWhere('name', 'like', "%{$q}%"))))
            ->when(in_array($status, ['open', 'answered', 'closed'], true), fn ($query) => $query->where('status', $status))
            ->when($assigned === 'me', fn ($query) => $query->where('assigned_to', $request->user()->id))
            ->when($assigned === 'none', fn ($query) => $query->whereNull('assigned_to'))
            ->latest('updated_at')->limit(200)->get();

        return view('admin.tickets', ['tickets' => $tickets, 'q' => $q, 'status' => $status, 'assigned' => $assigned]);
    }

    public function ticket(Ticket $ticket): View
    {
        $ticket->load(['user', 'assignee', 'replies.user']);
        $licence = $ticket->user?->licences()->with('plan')->latest('id')->first();

        return view('admin.ticket', ['ticket' => $ticket, 'licence' => $licence]);
    }

    public function replyTicket(Request $request, Ticket $ticket): RedirectResponse
    {
        $data = $request->validate(['body' => ['nullable', 'string', 'max:5000'], 'do' => ['nullable', 'in:reply,note,solve']]);
        $do = $data['do'] ?? ($request->boolean('close') ? 'solve' : 'reply');
        $body = trim((string) ($data['body'] ?? ''));
        if ($do !== 'solve' && $body === '') {
            return back()->withErrors(['body' => 'Write something first.']);
        }

        if ($do === 'solve' && $body === '') {
            $ticket->update(['status' => 'closed']);
            AuditLog::record('ticket.solved', '#'.$ticket->id);

            return back()->with('status', 'Ticket marked solved.');
        }

        $ticket->replies()->create(['user_id' => $request->user()->id, 'is_staff' => true, 'is_note' => $do === 'note', 'body' => $body]);
        if ($do === 'note') {
            AuditLog::record('ticket.noted', '#'.$ticket->id);

            return back()->with('status', 'Internal note added.');
        }

        $ticket->update(['status' => $do === 'solve' ? 'closed' : 'answered', 'assigned_to' => $ticket->assigned_to ?: $request->user()->id]);
        AuditLog::record('ticket.replied', '#'.$ticket->id);
        app(\App\Services\Notifier::class)->ticketReply($ticket->load('user'), $body);

        return back()->with('status', 'Reply sent.');
    }

    private function auditQuery(Request $request)
    {
        $q = trim((string) $request->query('q'));
        $who = (string) $request->query('who', '');
        $range = (string) $request->query('range', '30');

        return AuditLog::with('user')
            ->when($q !== '', fn ($query) => $query->where(fn ($w) => $w->where('action', 'like', "%{$q}%")->orWhere('subject', 'like', "%{$q}%")->orWhereHas('user', fn ($u) => $u->where('name', 'like', "%{$q}%")->orWhere('email', 'like', "%{$q}%"))))
            ->when(ctype_digit($who) && $who !== '', fn ($query) => $query->where('user_id', (int) $who))
            ->when(ctype_digit($range) && $range !== '0', fn ($query) => $query->where('created_at', '>=', now()->subDays((int) $range)))
            ->latest('id');
    }

    public function audit(Request $request): View
    {
        return view('admin.audit', [
            'logs' => $this->auditQuery($request)->limit(200)->get(), 'staff' => User::where('is_admin', true)->orderBy('name')->get(),
            'q' => trim((string) $request->query('q')), 'who' => (string) $request->query('who', ''), 'range' => (string) $request->query('range', '30'),
        ]);
    }

    public function auditCsv(Request $request): StreamedResponse
    {
        $rows = $this->auditQuery($request)->limit(5000)->get();
        AuditLog::record('audit.exported', (string) $rows->count().' rows');

        return response()->streamDownload(function () use ($rows) {
            $out = fopen('php://output', 'w');
            fputcsv($out, ['When', 'Who', 'Action', 'Subject', 'Reason', 'IP']);
            foreach ($rows as $l) {
                $cell = fn ($v) => preg_match('/^[=+\-@]/', (string) $v) ? "'".$v : (string) $v;
                fputcsv($out, [$l->created_at->format('Y-m-d H:i:s'), $l->user?->email ?? 'system', $cell($l->action), $cell($l->subject), $cell($l->reason), $l->ip]);
            }
            fclose($out);
        }, 'audit-log.csv', ['Content-Type' => 'text/csv']);
    }

    public function staff(): View
    {
        $roles = ['super_admin' => 'Super admin', 'support' => 'Support', 'finance' => 'Finance'];
        $areas = [
            'Customers and licences' => ['staff.customers'],
            'Orders and refunds' => ['staff.orders.settle', 'staff.orders.view'],
            'Tickets' => ['staff.tickets'],
            'Releases and plans' => ['staff.releases', 'staff.plans'],
            'Staff, webhooks, settings' => ['staff.staff'],
        ];
        $matrix = [];
        foreach ($areas as $label => $abilities) {
            foreach (array_keys($roles) as $role) {
                $can = fn ($a) => $role === 'super_admin' || in_array($role, \App\Providers\AppServiceProvider::ABILITIES[$a], true);
                $matrix[$label][$role] = match (true) {
                    $label === 'Orders and refunds' => $can('staff.orders.settle') ? 'Edit' : ($can('staff.orders.view') ? 'View' : 'None'),
                    $label === 'Releases and plans' => $can('staff.releases') ? 'Edit' : ($can('staff.plans') ? 'Plans only' : 'None'),
                    default => $can($abilities[0]) ? 'Edit' : 'None',
                };
            }
        }

        return view('admin.staff', ['staff' => User::where('is_admin', true)->orderBy('id')->get(), 'roles' => $roles, 'matrix' => $matrix]);
    }

    public function settings(): View
    {
        return view('admin.settings', [
            'seller' => \App\Models\Setting::get('seller_name', ''), 'support' => \App\Models\Setting::get('support_email', ''),
            'refund' => \App\Models\Setting::get('refund_days', '14'), 'provider' => config('services.paddle.secret') ? 'Paddle' : null,
        ]);
    }

    public function saveSettings(Request $request): RedirectResponse
    {
        $d = $request->validate(['seller_name' => ['nullable', 'string', 'max:120'], 'support_email' => ['nullable', 'email', 'max:190'], 'refund_days' => ['required', 'integer', 'min:0', 'max:365']]);
        foreach ($d as $k => $v) {
            \App\Models\Setting::put($k, $v === null ? '' : (string) $v);
        }
        AuditLog::record('settings.saved');

        return back()->with('status', 'Settings saved.');
    }

    public function webhooks(): View
    {
        return view('admin.webhooks', ['events' => \App\Models\WebhookEvent::latest('id')->limit(100)->get()]);
    }

    public function retryWebhook(\App\Models\WebhookEvent $event, Fulfilment $fulfilment): RedirectResponse
    {
        abort_unless($event->status === 'failed', 422);
        app(\App\Http\Controllers\PaddleWebhookController::class)->run($event, $fulfilment);
        AuditLog::record('webhook.retried', $event->event);

        return back()->with('status', $event->fresh()->status === 'processed' ? 'Event processed.' : 'Still failing: '.$event->fresh()->error);
    }

    public function system(): View
    {
        return view('admin.system', [
            'php' => PHP_VERSION, 'laravel' => app()->version(), 'env' => app()->environment(),
            'mail' => config('mail.default'), 'db' => config('database.default'),
            'provider' => config('services.paddle.secret') ? 'Paddle webhook configured' : 'Manual (staff mark orders paid)',
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
