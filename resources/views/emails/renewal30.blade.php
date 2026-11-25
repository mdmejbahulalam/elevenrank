@extends('emails.layout')
@section('subject', "Your ElevenRank term ends in 30 days")
@section('preview', "Pro keeps working. Renew to keep updates and support.")
@section('heading', "Your term ends in 30 days")
@section('body')
<p style="margin:0 0 16px">Your term ends on {{ $date }}. Pro will keep working on your sites after that. Only updates and support stop.</p><p style="margin:0 0 20px"><a href="{{ $url }}" style="display:inline-block;padding:12px 24px;border-radius:8px;background:#4338ca;color:#ffffff;text-decoration:none;font-weight:600">Renew licence</a></p>
@endsection
