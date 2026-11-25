@props(['tone' => 'info', 'title' => null])
<div {{ $attributes->class(['alert', $tone]) }} @if ($tone === 'bad') role="alert" @else role="status" @endif>
	<div>@if ($title)<strong>{{ $title }}</strong>@endif{{ $slot }}</div>
</div>
