@extends('emails.layout')
@section('subject', "Your ElevenRank term ends in 30 days")
@section('preview', "Pro keeps working. Renew to keep updates and support.")
@section('heading', "Your term ends in 30 days")
@section('body')
<p style="margin:0 0 20px">Your {{ $plan ?? 'Pro' }} term ends on {{ $date }}. Pro will keep working on your sites after that. Only updates and support stop.</p>
@include('emails.button', ['url' => $url, 'label' => 'Renew licence'])
@include('emails.small', ['text' => 'Renew early and the new '.($months ?? 12).' months start when this term ends.'])
@endsection
