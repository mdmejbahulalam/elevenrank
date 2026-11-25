@props(['tone' => 'neutral'])
<span {{ $attributes->class(['pill', $tone]) }}>{{ $slot }}</span>
