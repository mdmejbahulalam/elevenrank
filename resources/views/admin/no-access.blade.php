@extends('layouts.admin')
@section('title', 'No access')
@section('content')
<div class="a-card" style="max-width:560px;margin:48px auto;text-align:center;padding:48px 32px">
	<span class="a-ico-box" style="display:inline-grid;place-items:center;width:48px;height:48px;border-radius:12px;background:var(--accent-soft);color:var(--accent)"><x-icon name="lock" /></span>
	<h1 style="font-size:20px;margin:16px 0 8px">Your role cannot open {{ $area }}</h1>
	<p class="a-muted" style="margin:0 0 24px">{{ $who }} Ask a super admin if you need it. The attempt is recorded.</p>
	<a class="a-btn secondary" href="/admin">Back to overview</a>
</div>
@endsection
