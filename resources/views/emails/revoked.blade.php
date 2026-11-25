@extends('emails.layout')
@section('subject', "Your ElevenRank licence was revoked")
@section('preview', "Your Pro licence no longer works.")
@section('heading', "Your Pro licence was revoked")
@section('body')
<p style="margin:0 0 16px">The Pro licence {{ $key }} no longer works on your sites. The free plugin is unaffected.</p><div style="margin:0 0 20px;padding:14px 16px;border-radius:12px;background:#fde9e7;border:1px solid #b42318;font-size:14px;line-height:20px"><strong>Think this is a mistake?</strong> Reply to this email or open a ticket in your account and we will look at it.</div>
@endsection
