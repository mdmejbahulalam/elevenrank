@extends('emails.layout')
@section('subject', "New reply on your ticket: ".$subject."")
@section('preview', "Support replied to your ticket.")
@section('heading', "Support replied")
@section('body')
<div style="margin:0 0 20px;padding:14px 16px;border-radius:12px;background:#eef0ff">{{ $excerpt }}</div><p style="margin:0 0 20px"><a href="{{ $url }}" style="display:inline-block;padding:12px 24px;border-radius:8px;background:#4338ca;color:#ffffff;text-decoration:none;font-weight:600">Open the ticket</a></p>
@endsection
