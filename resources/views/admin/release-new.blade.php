@extends('layouts.admin')
@section('title', 'Upload a release')
@section('content')
<p class="a-eyebrow"><a href="/admin/releases">Releases</a> / New</p><h1 style="margin:0 0 24px">Upload a release</h1>
<form method="post" action="/admin/releases" enctype="multipart/form-data" class="a-card" style="max-width:720px">@csrf
	<p class="a-muted" style="margin:0 0 4px">Current stable release: <strong>{{ $current ?: 'none yet' }}</strong>. The version is read from the zip, so it cannot be mistyped.</p>
	<label class="a-label" for="zip">Zip file</label><input id="zip" type="file" name="zip" accept=".zip" required @error('zip') aria-invalid="true" @enderror>
	@error('zip')<div class="field-error" role="alert">{{ $message }}</div>@enderror
	<p class="a-muted" style="font-size:13px;margin:4px 0 0">Must contain elevenrank-pro/elevenrank-pro.php, and be higher than the current stable release.</p>
	<label class="a-label" for="notes">Changelog</label><textarea id="notes" name="notes" rows="5" placeholder="What changed, in plain words">{{ old('notes') }}</textarea>
	<label class="a-label" for="requires">Requires</label><input id="requires" type="text" name="requires" value="{{ old('requires', 'WordPress 6.0, PHP 7.4') }}">
	<label style="display:flex;gap:8px;align-items:center;margin-top:16px"><input type="checkbox" name="allow_older" value="1" style="width:auto"> Allow an older version</label>
	<div class="a-alert info" style="margin-top:16px"><div><strong>Update check.</strong><br>Customers inside their term see this version in WordPress. Others keep the version they have.</div></div>
	<p class="a-row" style="margin:20px 0 0"><button class="a-btn primary">Publish release</button><a class="a-btn secondary" href="/admin/releases">Cancel</a></p>
</form>
@endsection
