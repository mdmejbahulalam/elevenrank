@extends('layouts.admin')
@section('title', 'Pro releases')
@section('content')
<h1>Pro releases</h1>
@if (session('status')) <p class="ok" role="status">{{ session('status') }}</p> @endif

<div class="card">
	<h2>Serving</h2>
	<p>@if ($current) Customers receive version <strong>{{ $current }}</strong>. @else <span class="muted">Nothing published yet.</span> @endif</p>
</div>

<div class="card">
	<h2>Publish a release</h2>
	<form method="post" action="/admin/releases" enctype="multipart/form-data">
		@csrf
		<label for="zip">elevenrank-pro.zip</label>
		<input id="zip" type="file" name="zip" accept=".zip" required>
		@error('zip') <p class="err" role="alert">{{ $message }}</p> @enderror
		<div class="row">
			<label style="margin:0;font-weight:400"><input type="checkbox" name="allow_older" value="1"> Allow an older version</label>
			<button>Publish release</button>
		</div>
	</form>
	<p class="muted">The zip must contain <code>elevenrank-pro/elevenrank-pro.php</code>, and its Version header must match <code>ELEVENRANK_PRO_VERSION</code>.</p>
</div>

@if ($history)
<div class="card">
	<h2>Roll back</h2>
	<form method="post" action="/admin/releases/restore">
		@csrf
		<label for="version">Version</label>
		<select id="version" name="version">@foreach ($history as $v) <option value="{{ $v }}">{{ $v }}</option> @endforeach</select>
		@error('version') <p class="err" role="alert">{{ $message }}</p> @enderror
		<div class="row"><button>Make current</button></div>
	</form>
</div>
@endif
@endsection
