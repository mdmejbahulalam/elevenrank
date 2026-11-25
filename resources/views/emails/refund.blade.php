@extends('emails.layout')
@section('subject', "Your ElevenRank refund")
@section('preview', "We refunded order ".$number.".")
@section('heading', "Your refund is on its way")
@section('body')
<p style="margin:0 0 20px">We refunded {{ $amount }} for order {{ $number }}. It can take 5 to 10 days to reach your account, depending on your bank.</p>
@include('emails.note', ['tone' => 'bad', 'lead' => 'Licence revoked.', 'text' => 'The Pro licence for this order no longer works. The free plugin is unaffected.'])
@include('emails.small', ['text' => 'If this looks wrong, reply to this email.'])
@endsection
