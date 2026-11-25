@extends('layouts.admin')
@section('title', 'Pro releases')
@section('content')
<div class="a-between" style="align-items:flex-start;margin-bottom:24px"><div><p class="a-eyebrow">Releases</p><h1 style="margin:0">Pro releases</h1></div><a class="a-btn primary" href="/admin/releases/new">Upload release</a></div>
@error('version')<div class="a-alert bad" style="margin-bottom:16px"><div>{{ $message }}</div></div>@enderror
@if (empty($rows))<x-card><x-empty icon="key" title="Nothing published yet.">Upload the first Pro zip to start serving updates.</x-empty></x-card>
@else
<x-table :headings="['Version', 'Released', 'Channel', 'Downloads', 'Status']">
@foreach ($rows as $r)
	<tr><td><strong>{{ $r['version'] }}</strong></td><td>{{ $r['released'] }}</td><td>Stable</td><td>{{ $r['downloads'] }}</td>
		<td>@if ($r['current'])<span class="a-pill ok">Published</span>@else<form method="post" action="/admin/releases/restore" style="margin:0;display:flex;gap:8px;align-items:center"><span class="a-pill">Older</span>@csrf<input type="hidden" name="version" value="{{ $r['version'] }}"><button class="a-btn secondary sm">Make current</button></form>@endif</td></tr>
@endforeach
</x-table>
@endif
<p class="a-muted" style="margin-top:8px">Customers inside their term receive the published version. Downloads are counted from the Account page.</p>
@endsection
