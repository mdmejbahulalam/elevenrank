@extends('emails.layout')
@section('subject', "Your ElevenRank refund")
@section('preview', "We refunded order ".$number.".")
@section('heading', "Your refund is on its way")
@section('body')
<p style="margin:0 0 16px">We refunded {{ $amount }} for order {{ $number }}. It can take 5 to 10 days to reach your account.</p><div style="margin:0 0 20px;padding:14px 16px;border-radius:12px;background:#fde9e7;border:1px solid #b42318;font-size:14px;line-height:20px"><strong>Licence revoked.</strong> The Pro licence for this order no longer works. The free plugin is unaffected.</div>
@endsection
