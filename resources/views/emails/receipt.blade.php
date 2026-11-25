@extends('emails.layout')
@section('subject', "Your ElevenRank receipt, order ".$number."")
@section('preview', "Thank you. Your receipt and licence key are inside.")
@section('heading', "Thank you for your order")
@section('body')
<p style="margin:0 0 16px">Order {{ $number }}: {{ $plan }}, {{ $amount }}. A PDF invoice is in your account.</p><p style="margin:0 0 6px;font-size:13px;color:#475467">Your licence key</p><div style="margin:0 0 20px;padding:12px 14px;border:1px solid #667085;border-radius:8px;background:#eef0f4;font-family:ui-monospace,Menlo,Consolas,monospace;font-size:15px;word-break:break-all">{{ $key }}</div><div style="margin:0 0 20px;padding:14px 16px;border-radius:12px;background:#e8f1fe;border:1px solid #175cd3;font-size:14px;line-height:20px"><strong>3 steps.</strong> Install the free plugin, upload the Pro zip, then paste the key under ElevenRank, Licence.</div><p style="margin:0 0 20px"><a href="{{ $url }}" style="display:inline-block;padding:12px 24px;border-radius:8px;background:#4338ca;color:#ffffff;text-decoration:none;font-weight:600">Download Pro</a></p><p style="margin:0 0 16px"><span style="font-size:14px;color:#475467">14-day refund. Reply to this email if you need one.</span></p>
@endsection
