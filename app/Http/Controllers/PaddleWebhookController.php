<?php

namespace App\Http\Controllers;

use App\Models\Order;
use App\Models\Plan;
use App\Models\User;
use App\Models\WebhookEvent;
use App\Services\Fulfilment;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Str;

/**
 * Paddle Billing webhook. Inactive until PADDLE_WEBHOOK_SECRET is set.
 * PADDLE_PRICES is a JSON map of Paddle price id => plan slug, e.g. {"pri_01abc":"personal"}.
 * The checkout should pass custom_data {"order":"ER-1042","email":"buyer@example.com"} so a payment can be matched
 * to our order or customer. The Paddle-Signature header is "ts=...;h1=..." where h1 is HMAC-SHA256 of "ts:body".
 */
class PaddleWebhookController extends Controller
{
    public function __invoke(Request $request, Fulfilment $fulfilment): JsonResponse
    {
        $secret = (string) config('services.paddle.secret');
        if ($secret === '') {
            return response()->json(['error' => 'not configured'], 404);
        }

        if (! $this->validSignature((string) $request->header('Paddle-Signature'), $request->getContent(), $secret)) {
            return response()->json(['error' => 'bad signature'], 401);
        }

        $payload = (array) $request->json()->all();
        $record = WebhookEvent::create([
            'provider' => 'paddle', 'event' => (string) ($payload['event_type'] ?? 'unknown'),
            'payload' => json_encode($payload), 'attempts' => 0,
        ]);

        return $this->run($record, $fulfilment);
    }

    /** Runs (or re-runs) a stored event. A failure is kept so staff can retry it. */
    public function run(WebhookEvent $record, Fulfilment $fulfilment): JsonResponse
    {
        $record->attempts++;
        try {
            $result = $this->process((array) json_decode($record->payload, true), $fulfilment);
            $record->fill(['status' => 'processed', 'error' => null])->save();

            return $result;
        } catch (\Throwable $e) {
            $record->fill(['status' => 'failed', 'error' => mb_substr($e->getMessage(), 0, 500)])->save();

            return response()->json(['error' => 'failed'], 500);
        }
    }

    private function validSignature(string $header, string $body, string $secret): bool
    {
        $parts = [];
        foreach (explode(';', $header) as $pair) {
            [$k, $v] = array_pad(explode('=', $pair, 2), 2, '');
            $parts[trim($k)] = trim($v);
        }
        if (empty($parts['ts']) || empty($parts['h1']) || abs(time() - (int) $parts['ts']) > 300) {
            return false;
        }

        return hash_equals(hash_hmac('sha256', $parts['ts'].':'.$body, $secret), $parts['h1']);
    }

    private function process(array $payload, Fulfilment $fulfilment): JsonResponse
    {
        $event = (string) ($payload['event_type'] ?? '');
        $data = (array) ($payload['data'] ?? []);
        $custom = (array) ($data['custom_data'] ?? []);
        $txn = 'paddle-'.($data['id'] ?? '');
        $sub = ! empty($data['subscription_id']) ? 'paddle-'.$data['subscription_id'] : null;

        if ($event === 'transaction.completed') {
            $existing = Order::where('provider', 'paddle')->whereIn('provider_ref', array_filter([$txn, $sub]))->first();

            // A renewal: push the licence behind this subscription out by one plan term.
            if ($existing && $existing->paid_at && $existing->paid_at->lt(now()->subDay()) && ($data['origin'] ?? '') === 'subscription_recurring') {
                if ($existing->licence) {
                    $base = $existing->licence->expires && $existing->licence->expires->isFuture() ? $existing->licence->expires : now();
                    $existing->licence->update(['expires' => $base->copy()->addMonths($existing->plan->months), 'status' => 'active']);
                }

                return response()->json(['ok' => true]);
            }

            $order = $existing ?: (! empty($custom['order']) ? Order::where('number', $custom['order'])->first() : null);
            if (! $order) {
                $plan = $this->plan((string) data_get($data, 'items.0.price.id', ''));
                $email = strtolower((string) ($custom['email'] ?? ''));
                if (! $plan || $email === '') {
                    return response()->json(['ok' => true, 'ignored' => true]);
                }
                $user = User::firstOrCreate(['email' => $email], ['name' => (string) ($custom['name'] ?? 'Customer'), 'password' => Str::random(40)]);
                $order = Order::create([
                    'number' => Order::nextNumber(), 'user_id' => $user->id, 'plan_id' => $plan->id,
                    'amount_cents' => (int) data_get($data, 'details.totals.grand_total', $plan->price_cents),
                    'currency' => strtoupper((string) ($data['currency_code'] ?? $plan->currency)),
                ]);
            }
            $order->update(['provider' => 'paddle']);
            $fulfilment->markPaid($order, $sub ?? $txn);
        } elseif ($event === 'adjustment.updated' || $event === 'adjustment.created') {
            if (($data['action'] ?? '') === 'refund' && ($data['status'] ?? '') === 'approved') {
                $order = Order::where('provider', 'paddle')->whereIn('provider_ref', array_filter(['paddle-'.($data['transaction_id'] ?? ''), $sub]))->first();
                if ($order) {
                    $fulfilment->refund($order);
                }
            }
        }

        return response()->json(['ok' => true]);
    }

    private function plan(string $price): ?Plan
    {
        $map = json_decode((string) config('services.paddle.prices'), true) ?: [];

        return isset($map[$price]) ? Plan::where('slug', $map[$price])->first() : null;
    }
}
