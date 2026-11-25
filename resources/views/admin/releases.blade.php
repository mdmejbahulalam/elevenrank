@extends('layouts.admin')
@section('title', 'Pro releases')
@section('content')
<h1>Pro releases</h1>
@if (session('status')) <x-alert tone="ok">{{ session('status') }}</x-alert> @endif

<x-card title="Serving" style="margin-bottom:16px">
	<p>@if ($current) Customers receive version <strong>{{ $current }}</strong>. @else <span class="muted">Nothing published yet.</span> @endif</p>
</x-card>

<x-card title="Publish a release" style="margin-bottom:16px">
	<form method="post" action="/admin/releases" enctype="multipart/form-data">
		@csrf
		<label for="f-zip">elevenrank-pro.zip</label>
		<input id="f-zip" type="file" name="zip" accept=".zip" required @error('zip') aria-invalid="true" aria-describedby="e-zip" @enderror>
		@error('zip') <div class="field-error" id="e-zip" role="alert">{{ $message }}</div> @enderror
		<div class="row" style="margin-top:16px">
			<label style="margin:0;font-weight:400"><input type="checkbox" name="allow_older" value="1"> Allow an older version</label>
			<x-button>Publish release</x-button>
		</div>
	</form>
	<p class="muted">The zip must contain <code>elevenrank-pro/elevenrank-pro.php</code>, and its Version header must match <code>ELEVENRANK_PRO_VERSION</code>.</p>
</x-card>

@if ($history)
<x-card title="Roll back">
	<form method="post" action="/admin/releases/restore">
		@csrf
		<label for="f-version">Version</label>
		<select id="f-version" name="version">@foreach ($history as $v) <option value="{{ $v }}">{{ $v }}</option> @endforeach</select>
		@error('version') <div class="field-error" role="alert">{{ $message }}</div> @enderror
		<div class="row" style="margin-top:16px"><x-button>Make current</x-button></div>
	</form>
</x-card>
@endif
@endsection
