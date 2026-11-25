@props(['href' => '/', 'suffix' => null])
<a class="brand" href="{{ $href }}"><img src="/assets/mark.svg" alt="" width="28" height="28"><span class="wm">Eleven<span>Rank</span></span>@if ($suffix)<span class="hq-tag">{{ $suffix }}</span>@endif</a>
