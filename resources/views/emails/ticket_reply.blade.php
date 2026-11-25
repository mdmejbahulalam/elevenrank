@extends('emails.layout')
@section('subject', "New reply on your ticket: ".$subject)
@section('preview', "Support replied to your ticket.")
@section('heading', "Support replied")
@section('body')
<div style="margin:0 0 20px;padding:14px 16px;border-radius:12px;background:#eef0ff">{{ $excerpt }}</div>
@include('emails.button', ['url' => $url, 'label' => 'Open the ticket'])
@include('emails.small', ['text' => 'Reply in your account so the whole history stays in one place.'])
@endsection
