@extends('layouts.admin')
@section('title', 'System')
@section('content')
<h1>System</h1>
<x-card style="margin-bottom:16px">
	<table>
		<tr><th scope="row">PHP</th><td>{{ $php }}</td></tr>
		<tr><th scope="row">Laravel</th><td>{{ $laravel }}</td></tr>
		<tr><th scope="row">Environment</th><td>{{ $env }}</td></tr>
		<tr><th scope="row">Database</th><td>{{ $db }}</td></tr>
		<tr><th scope="row">Mail driver</th><td>{{ $mail }} @if($mail === 'log')<span class="muted">(messages are only written to the log)</span>@endif</td></tr>
		<tr><th scope="row">Payments</th><td>{{ $provider }}</td></tr>
	</table>
</x-card>
<x-card title="After uploading a new version">
	<p class="muted">Run the database changes, then clear the caches so new pages show.</p>
	<div class="row" style="margin-top:12px">
		<form method="post" action="/admin/system">@csrf<input type="hidden" name="do" value="migrate"><x-button>Run migrations</x-button></form>
		<form method="post" action="/admin/system">@csrf<input type="hidden" name="do" value="clear"><x-button variant="secondary">Clear caches</x-button></form>
	</div>
</x-card>
@endsection
