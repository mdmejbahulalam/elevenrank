@props(['highlight' => false, 'title' => null])
<section {{ $attributes->class(['card', 'hl' => $highlight]) }}>@if ($title)<h2 style="margin-top:0">{{ $title }}</h2>@endif{{ $slot }}</section>
