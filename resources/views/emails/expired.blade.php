@extends('emails.layout')
@section('subject', "Your ElevenRank term has ended")
@section('preview', "Pro still works on your sites.")
@section('heading', "Your term has ended")
@section('body')
<div style="margin:0 0 20px;padding:14px 16px;border-radius:12px;background:#e8f1fe;border:1px solid #175cd3;font-size:14px;line-height:20px"><strong>Pro keeps working on your sites.</strong> You will not get updates or support until you renew.</div><p style="margin:0 0 16px">Renew any time and we will pick up from the day you renew.</p><p style="margin:0 0 20px"><a href="{{ $url }}" style="display:inline-block;padding:12px 24px;border-radius:8px;background:#4338ca;color:#ffffff;text-decoration:none;font-weight:600">Renew licence</a></p>
@endsection
