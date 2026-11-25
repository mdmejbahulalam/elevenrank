@extends('emails.layout')
@section('subject', "Welcome to ElevenRank")
@section('preview', "Your account is ready. Here is where to start.")
@section('heading', "Welcome, ".$name)
@section('body')
<p style="margin:0 0 20px">Your account is ready. The free plugin works on its own. Pro adds more features on top of it.</p>
@include('emails.button', ['url' => $url, 'label' => 'Open your account'])
@include('emails.small', ['text' => 'Questions? Reply to this email or open a ticket in your account.'])
@endsection
