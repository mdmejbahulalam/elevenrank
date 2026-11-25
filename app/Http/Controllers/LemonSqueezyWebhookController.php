<?php

namespace App\Http\Controllers;

use App\Models\Order;
use App\Models\Plan;
use App\Models\User;
use App\Services\Fulfilment;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Str;

/**
 * Lemon Squeezy webhook. Inactive until LEMONSQUEEZY_SECRET is set.
 * LEMONSQUEEZY_VARIANTS is a JSON map of variant id => plan slug, e.g. {"123":"personal"}.
 * The signature is an HMAC-SHA256 of the raw body in X-Signature.
 */
class LemonSqueezyWebhookController extends Controller
{
    public function __invoke(Request $request, Fulfilment $fulfilment): JsonResponse
    {
        $secret = (string) config('services.lemonsqueezy.secret');
        if ($secret === '') {
            return response()->json(['error' => 'not configured'], 404);
        }

        $expected = hash_hmac('sha256', $request->getContent(), $secret);
        if (! hash_equals($expected, (string) $request->header('X-Signature'))) {
            return response()->json(['error' => 'bad signature'], 401);
        }

        $event = (string) $request->input('meta.event_name');
        $data = (array) $request->input('data');
        $attrs = (array) ($data['attributes'] ?? []);
        $ref = 'ls-'.($data['id'] ?? '');

        if (in_array($event, ['subscription_created', 'order_created'], true)) {
            $plan = $this->plan((string) ($attrs['variant_id'] ?? $attrs['first_order_item']['variant_id'] ?? ''));
            $email = strtolower((string) ($attrs['user_email'] ?? ''));
            if (! $plan || $email === '') {
                return response()->json(['ok' => true, 'ignored' => true]);
            }

            $order = Order::where('provider', 'lemonsqueezy')->where('provider_ref', $ref)->first();
            if (! $order) {
                $user = User::firstOrCreate(['email' => $email], ['name' => (string) ($attrs['user_name'] ?? 'Customer'), 'password' => Str::random(40)]);
                $order = Order::create([
                    'number' => Order::nextNumber(), 'user_id' => $user->id, 'plan_id' => $plan->id,
                    'amount_cents' => (int) ($attrs['total'] ?? $plan->price_cents), 'currency' => strtoupper((string) ($attrs['currency'] ?? $plan->currency)),
                    'provider' => 'lemonsqueezy', 'provider_ref' => $ref,
                ]);
            }
            $fulfilment->markPaid($order, $ref);
        } elseif (in_array($event, ['subscription_payment_success', 'subscription_resumed'], true)) {
            // Renewal: push the expiry of the licence behind this subscription by one plan term.
            $sub = 'ls-'.($attrs['subscription_id'] ?? $data['id'] ?? '');
            $order = Order::where('provider', 'lemonsqueezy')->whereIn('provider_ref', [$sub, $ref])->first();
            if ($order?->licence && $order->paid_at && $order->paid_at->lt(now()->subDay())) {
                $base = $order->licence->expires && $order->licence->expires->isFuture() ? $order->licence->expires : now();
                $order->licence->update(['expires' => $base->copy()->addMonths($order->plan->months), 'status' => 'active']);
            }
        } elseif (in_array($event, ['order_refunded', 'subscription_payment_refunded', 'subscription_expired'], true)) {
            $order = Order::where('provider', 'lemonsqueezy')->whereIn('provider_ref', [$ref, 'ls-'.($attrs['subscription_id'] ?? '')])->first();
            if ($order && $event !== 'subscription_expired') {
                $fulfilment->refund($order);
            }
        }

        return response()->json(['ok' => true]);
    }

    private function plan(string $variant): ?Plan
    {
        $map = json_decode((string) config('services.lemonsqueezy.variants'), true) ?: [];

        return isset($map[$variant]) ? Plan::where('slug', $map[$variant])->first() : null;
    }
}
