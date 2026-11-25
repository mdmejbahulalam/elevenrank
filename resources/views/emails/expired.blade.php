@extends('emails.layout')
@section('subject', "Your ElevenRank term has ended")
@section('preview', "Pro still works on your sites.")
@section('heading', "Your term has ended")
@section('body')
@include('emails.note', ['tone' => 'info', 'lead' => 'Pro keeps working on your sites.', 'text' => 'You will not get updates or support until you renew.'])
<p style="margin:0 0 20px">Renew any time. We will pick up from the day you renew, with the latest version ready to download.</p>
@include('emails.button', ['url' => $url, 'label' => 'Renew licence'])
@endsection
