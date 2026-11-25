<!doctype html>
<html lang="en"><head>@include('layouts.head')<title>Invoice {{ $order->number }} · ElevenRank</title>
<style>body{font:16px/1.5 system-ui,sans-serif;max-width:720px;margin:32px auto;padding:0 20px;color:#101828}table{width:100%;border-collapse:collapse;margin:24px 0}th,td{text-align:left;padding:10px;border-bottom:1px solid #e4e7ec}.r{text-align:right}.muted{color:#475467}@media print{.noprint{display:none}}</style></head>
<body>
<p class="noprint"><a href="/account/orders/{{ $order->number }}">Back to order</a> · <a href="#" onclick="window.print();return false">Print</a></p>
<h1>Invoice {{ $order->number }}</h1>
<p class="muted">[Seller legal name and address]<br>Date: {{ ($order->paid_at ?? $order->created_at)->format('j M Y') }}</p>
<p><strong>Billed to</strong><br>{{ $order->user->name }}<br>{{ $order->user->email }}</p>
<table><thead><tr><th>Item</th><th class="r">Amount</th></tr></thead>
<tbody><tr><td>ElevenRank Pro, {{ $order->plan->name }} ({{ $order->plan->sitesLabel() }}, {{ $order->plan->months }} months)</td><td class="r">{{ $order->amount() }}</td></tr></tbody>
<tfoot><tr><th>Total</th><th class="r">{{ $order->amount() }}</th></tr></tfoot></table>
<p class="muted">Status: {{ $order->status }}. [Tax or VAT line to be added when the payment provider is chosen.]</p>
</body></html>
