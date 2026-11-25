@props(['items'])
<nav class="crumbs" aria-label="Breadcrumb">@foreach ($items as $label => $href)@if (! $loop->last)<a href="{{ $href }}">{{ $label }}</a> / @else <span aria-current="page">{{ $label }}</span>@endif @endforeach</nav>
