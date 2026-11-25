@extends('emails.layout')
@section('subject', "Your ElevenRank term ends in 7 days")
@section('preview', "Renew now to keep updates and support.")
@section('heading', "One week left")
@section('body')
@include('emails.note', ['tone' => 'warn', 'lead' => 'Ends '.$date.'.', 'text' => 'Pro keeps working either way. Updates and support stop.'])
<p style="margin:0 0 20px">If you plan to keep getting updates, renew now. It takes under a minute.</p>
@include('emails.button', ['url' => $url, 'label' => 'Renew licence'])
@endsection
