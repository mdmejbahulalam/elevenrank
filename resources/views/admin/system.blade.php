@extends('layouts.admin')
@section('title', 'System')
@section('content')
<h1>System</h1>
<div class="card">
	<table>
		<tr><th>PHP</th><td>{{ $php }}</td></tr>
		<tr><th>Laravel</th><td>{{ $laravel }}</td></tr>
		<tr><th>Environment</th><td>{{ $env }}</td></tr>
		<tr><th>Database</th><td>{{ $db }}</td></tr>
		<tr><th>Mail driver</th><td>{{ $mail }} @if($mail === 'log')<span class="muted">(messages are only written to the log)</span>@endif</td></tr>
		<tr><th>Payments</th><td>{{ $provider }}</td></tr>
	</table>
</div>
<div class="card">
	<h2>After uploading a new version</h2>
	<p class="muted">Run the database changes, then clear the caches so new pages show.</p>
	<div class="row">
		<form method="post" action="/admin/system">@csrf<input type="hidden" name="do" value="migrate"><button>Run migrations</button></form>
		<form method="post" action="/admin/system">@csrf<input type="hidden" name="do" value="clear"><button>Clear caches</button></form>
	</div>
</div>
@endsection
