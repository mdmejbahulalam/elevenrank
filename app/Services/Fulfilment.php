<?php

namespace App\Services;

use App\Models\AuditLog;
use App\Models\Licence;
use App\Models\Order;
use Illuminate\Support\Facades\DB;

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
            app(Notifier::class)->receipt($order->load('licence'));

            return $order;
        });
    }

    public function refund(Order $order, ?string $reason = null): Order
    {
        if ($order->status === 'refunded') {
            return $order;
        }

        $order->licence?->update(['status' => 'revoked']);
        $order->update(['status' => 'refunded', 'refunded_at' => now()]);
        AuditLog::record('order.refunded', $order->number, $reason);
        app(Notifier::class)->refund($order);

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

}
