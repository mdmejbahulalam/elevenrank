@extends('emails.layout')
@section('subject', "Your ElevenRank receipt, order ".$number)
@section('preview', "Thank you. Your receipt is inside.")
@section('heading', "Thank you for your order")
@section('body')
<p style="margin:0 0 20px">Here is your receipt for order {{ $number }}. A PDF invoice is in your account.</p>
<table role="presentation" width="100%" cellpadding="0" cellspacing="0" style="margin:0 0 24px;font-size:16px">
<tr><td style="padding:12px 0;border-bottom:1px solid #e4e7ec">{{ $item ?? 'ElevenRank Pro, '.$plan }}</td><td align="right" style="padding:12px 0;border-bottom:1px solid #e4e7ec">{{ $amount }}</td></tr>
<tr><td style="padding:12px 0;border-bottom:1px solid #e4e7ec;color:#475467">Tax</td><td align="right" style="padding:12px 0;border-bottom:1px solid #e4e7ec;color:#475467">[from provider]</td></tr>
<tr><td style="padding:12px 0;font-weight:700">Total</td><td align="right" style="padding:12px 0;font-weight:700">{{ $amount }}</td></tr>
</table>
@include('emails.button', ['url' => $invoiceUrl ?? $url, 'label' => 'View invoice'])
@include('emails.small', ['text' => '14-day refund. Reply to this email if you need one.'])
@endsection
