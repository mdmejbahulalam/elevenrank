@extends('emails.layout')
@section('subject', "Your ElevenRank term ends in 7 days")
@section('preview', "Renew now to keep updates and support.")
@section('heading', "One week left")
@section('body')
<div style="margin:0 0 20px;padding:14px 16px;border-radius:12px;background:#fef0c7;border:1px solid #93370d;font-size:14px;line-height:20px"><strong>Ends {{ $date }}.</strong> Pro keeps working either way. Updates and support stop.</div><p style="margin:0 0 20px"><a href="{{ $url }}" style="display:inline-block;padding:12px 24px;border-radius:8px;background:#4338ca;color:#ffffff;text-decoration:none;font-weight:600">Renew licence</a></p>
@endsection
