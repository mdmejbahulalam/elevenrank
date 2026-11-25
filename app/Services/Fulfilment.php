<?php

namespace App\Services;

use App\Models\AuditLog;
use App\Models\Licence;
use App\Models\Order;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Mail;

/** Turns paid orders into licences, and refunds into revoked ones. Provider-neutral. */
class Fulfilment
{
    /** Idempotent: a second call for the same order changes nothing. */
    public function markPaid(Order $order, string $providerRef = ''): Order
    {
        return DB::transaction(function () use ($order, $providerRef) {
            $order = Order::lockForUpdate()->findOrFail($order->id);

            if ($order->status === 'paid' || $order->licence_id) {
                return $order;
            }

            $plan = $order->plan;
            $licence = Licence::issue(
                $order->user->email,
                $plan->sites,
                $plan->months > 0 ? now()->addMonths($plan->months) : null,
                $order->number,
            );
            $licence->update(['user_id' => $order->user_id, 'plan_id' => $plan->id]);

            $order->update([
                'status' => 'paid',
                'paid_at' => now(),
                'licence_id' => $licence->id,
                'provider_ref' => $providerRef !== '' ? $providerRef : $order->provider_ref,
            ]);

            AuditLog::record('order.paid', $order->number);
            $this->email($order->user->email, 'Your ElevenRank Pro licence',
                "Thank you for your order {$order->number}.\n\nYour licence key: {$licence->license_key}\nSites: {$plan->sitesLabel()}\nValid until: {$licence->expires?->format('Y-m-d')}\n\nSign in at ".url('/account')." to download ElevenRank Pro and manage your sites.");

            return $order;
        });
    }

    public function refund(Order $order): Order
    {
        if ($order->status === 'refunded') {
            return $order;
        }

        $order->licence?->update(['status' => 'revoked']);
        $order->update(['status' => 'refunded', 'refunded_at' => now()]);
        AuditLog::record('order.refunded', $order->number);

        return $order;
    }

    public function cancel(Order $order): Order
    {
        if ($order->status === 'pending') {
            $order->update(['status' => 'cancelled']);
            AuditLog::record('order.cancelled', $order->number);
        }

        return $order;
    }

    private function email(string $to, string $subject, string $body): void
    {
        try {
            Mail::raw($body, fn ($m) => $m->to($to)->subject($subject));
        } catch (\Throwable $e) {
            report($e); // A mail failure must never undo a paid order.
        }
    }
}
