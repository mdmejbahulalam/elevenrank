@extends('emails.layout')
@section('subject', "Welcome to ElevenRank")
@section('preview', "Your account is ready. Here is where to start.")
@section('heading', "Welcome, ".$name."")
@section('body')
<p style="margin:0 0 16px">Your account is ready. The free plugin works on its own. Pro adds more features on top of it.</p><p style="margin:0 0 20px"><a href="{{ $url }}" style="display:inline-block;padding:12px 24px;border-radius:8px;background:#4338ca;color:#ffffff;text-decoration:none;font-weight:600">Open your account</a></p>
@endsection
