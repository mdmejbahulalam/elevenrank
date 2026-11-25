@props(['href' => null, 'variant' => 'primary', 'type' => 'submit'])
@if ($href)
<a href="{{ $href }}" {{ $attributes->class(['btn', $variant === 'primary' ? '' : $variant]) }}>{{ $slot }}</a>
@else
<button type="{{ $type }}" {{ $attributes->class(['btn', $variant === 'primary' ? '' : $variant]) }}>{{ $slot }}</button>
@endif
