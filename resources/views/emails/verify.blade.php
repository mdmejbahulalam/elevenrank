@extends('emails.layout')
@section('subject', "Confirm your email for ElevenRank")
@section('preview', "One click and your account is ready.")
@section('heading', "Confirm your email")
@section('body')
<p style="margin:0 0 20px">Hi {{ $name }}, tap the button to confirm this address and finish setting up your account.</p>
@include('emails.button', ['url' => $url, 'label' => 'Confirm email'])
@include('emails.small', ['text' => 'The link works for 60 minutes. If you did not sign up, ignore this email.'])
@endsection
