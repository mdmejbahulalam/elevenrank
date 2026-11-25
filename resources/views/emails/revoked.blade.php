@extends('emails.layout')
@section('subject', "Your ElevenRank licence was revoked")
@section('preview', "Your Pro licence no longer works.")
@section('heading', "Your Pro licence was revoked")
@section('body')
<p style="margin:0 0 20px">The Pro licence {{ $key }} no longer works on your sites. The free plugin is unaffected.</p>
@include('emails.note', ['tone' => 'bad', 'lead' => 'Think this is a mistake?', 'text' => 'Reply to this email or open a ticket in your account and we will look at it.'])
@endsection
