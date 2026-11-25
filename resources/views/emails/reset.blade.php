@extends('emails.layout')
@section('subject', "Reset your ElevenRank password")
@section('preview', "This link works for 60 minutes.")
@section('heading', "Reset your password")
@section('body')
<p style="margin:0 0 20px">We got a request to reset the password for this account. Use the button to choose a new one.</p>
@include('emails.button', ['url' => $url, 'label' => 'Choose a new password'])
@include('emails.small', ['text' => 'The link works for '.($minutes ?? 60).' minutes. If you did not ask for this, you can ignore it. Your password stays the same.'])
@endsection
