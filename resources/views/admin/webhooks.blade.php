@extends('layouts.admin')
@section('title', 'Webhooks')
@section('content')
<p class="a-eyebrow">System</p><h1 style="margin:0 0 24px">Webhook deliveries</h1>
@if ($events->isEmpty())<x-card><x-empty icon="key" title="No deliveries yet.">Events from the payment provider appear here.</x-empty></x-card>
@else
<x-table :headings="['Event', 'Received', 'Attempts', 'Status', 'Action']">
@foreach ($events as $e)
	<tr><td>{{ $e->event }}</td><td>{{ $e->created_at->format('j M Y, H:i') }}</td><td>{{ $e->attempts }}</td>
		<td>@if ($e->status === 'failed')<span class="a-pill bad" title="{{ $e->error }}">Failed</span>@else<span class="a-pill ok">Processed</span>@endif</td>
		<td>@if ($e->status === 'failed')<form method="post" action="/admin/webhooks/{{ $e->id }}/retry" style="margin:0">@csrf<button class="a-btn secondary sm">Retry now</button></form>@else—@endif</td></tr>
@endforeach
</x-table>
@endif
<p class="a-muted" style="margin-top:8px">Failed events can be retried here. Event names come from the payment provider.</p>
@endsection
