<?php

namespace App\Services;

use App\Mail\TemplateMail;
use App\Models\Licence;
use App\Models\Order;
use App\Models\Ticket;
use App\Models\User;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Str;

/** Sends the service emails. A mail failure is reported but never undoes the thing that triggered it. */
class Notifier
{
    public function send(string $to, string $template, string $subject, array $vars = []): void
    {
        try {
            Mail::to($to)->send(new TemplateMail($template, $subject, $vars));
        } catch (\Throwable $e) {
            report($e);
        }
    }

    public function welcome(User $user): void
    {
        $this->send($user->email, 'welcome', 'Welcome to ElevenRank', ['name' => $user->name, 'url' => url('/account')]);
    }

    public function receipt(Order $order): void
    {
        $plan = $order->plan;
        $item = 'ElevenRank Pro, '.$plan->name.($plan->sites ? ' ('.$plan->sites.' '.($plan->sites === 1 ? 'site' : 'sites').')' : '').($plan->months ? ', '.$plan->months.' months' : '');
        $key = $order->licence?->license_key ?? '';
        $vars = [
            'number' => $order->number, 'plan' => $plan->name, 'item' => $item, 'summary' => $plan->name.($plan->sites ? ', '.$plan->sites.' '.($plan->sites === 1 ? 'site' : 'sites') : ''), 'amount' => $order->amount(), 'key' => $key,
            'date' => $order->licence?->expires?->format('j M Y'), 'url' => url('/account/downloads'), 'invoiceUrl' => url('/account/orders/'.$order->number),
        ];
        $this->send($order->user->email, 'receipt', 'Your ElevenRank receipt, order '.$order->number, $vars);

        if ($key !== '') {
            $this->send($order->user->email, 'licence', 'Your ElevenRank Pro licence key', $vars);
        }
    }

    /** The "confirm your email" message. Sent only when email confirmation is switched on. */
    public function verify(User $user, string $url): void
    {
        $this->send($user->email, 'verify', 'Confirm your email for ElevenRank', ['name' => $user->name, 'url' => $url]);
    }

    public function refund(Order $order): void
    {
        $this->send($order->user->email, 'refund', 'Your ElevenRank refund', ['number' => $order->number, 'amount' => $order->amount()]);
    }

    public function revoked(Licence $licence): void
    {
        if ($licence->email) {
            $this->send($licence->email, 'revoked', 'Your ElevenRank licence was revoked', ['key' => $licence->license_key]);
        }
    }

    public function ticketReply(Ticket $ticket, string $body): void
    {
        $this->send($ticket->user->email, 'ticket_reply', 'New reply on your ticket: '.$ticket->subject, [
            'subject' => $ticket->subject,
            'excerpt' => Str::limit($body, 300),
            'url' => url('/account/tickets/'.$ticket->id),
        ]);
    }

    /** @param  'renewal30'|'renewal7'|'expired'  $kind */
    public function term(Licence $licence, string $kind): void
    {
        $subjects = ['renewal30' => 'Your ElevenRank term ends in 30 days', 'renewal7' => 'Your ElevenRank term ends in 7 days', 'expired' => 'Your ElevenRank term has ended'];
        $this->send($licence->email, $kind, $subjects[$kind], ['date' => $licence->expires?->format('j M Y'), 'url' => url('/pricing'), 'name' => $licence->email, 'plan' => $licence->plan?->name ?? 'Pro', 'months' => $licence->plan?->months ?: 12]);
    }
}
