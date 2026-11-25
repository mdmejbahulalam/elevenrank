@extends('emails.layout')
@section('subject', "Your ElevenRank Pro licence key")
@section('preview', "Download Pro and paste this key to activate it.")
@section('heading', "Your licence key is ready")
@section('body')
<p style="margin:0 0 20px">{{ $summary ?? $item ?? 'ElevenRank Pro' }}. Updates and support run until {{ $date ?? 'the end of your term' }}.</p>
<div style="margin:0 0 20px;padding:12px 14px;border:1px solid #667085;border-radius:8px;background:#eef0f4;font-family:ui-monospace,Menlo,Consolas,monospace;font-size:15px;word-break:break-all">{{ $key }}</div>
@include('emails.note', ['tone' => 'info', 'lead' => '3 steps.', 'text' => 'Install the free plugin, upload the Pro zip, then paste the key under ElevenRank, Licence.'])
@include('emails.button', ['url' => $url, 'label' => 'Download Pro'])
@include('emails.small', ['text' => 'Keep this key private. You can always find it in your account.'])
@endsection
