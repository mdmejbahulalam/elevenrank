@props(['name', 'label' => null])
@php
$paths = [
  'home' => '<path d="M3 9l7-6 7 6v8H3z"/>',
  'key' => '<circle cx="7" cy="10" r="3.5"/><path d="M10.5 10H17M14 10v3"/>',
  'download' => '<path d="M10 3v10M6 9l4 4 4-4M4 17h12"/>',
  'receipt' => '<path d="M5 3h10v14l-2-1.5-3 1.5-3-1.5L5 17zM8 7h4M8 10h4"/>',
  'chat' => '<path d="M3 5h14v9H8l-4 3v-3H3z"/>',
  'user' => '<circle cx="10" cy="7" r="3.5"/><path d="M3.5 17c.8-3.2 3.3-4.5 6.5-4.5s5.7 1.3 6.5 4.5"/>',
  'users' => '<circle cx="7.5" cy="7" r="3"/><circle cx="14" cy="8" r="2.3"/><path d="M2 16c.6-2.700 2.700-3.800 5.500-3.800S12.400 13.300 13 16M13.500 12.500c2.200 0 3.800 1 4.500 3.500"/>',
  'package' => '<path d="M3 6l7-3 7 3v8l-7 3-7-3zM3 6l7 3 7-3M10 9v8"/>',
  'tag' => '<path d="M3 3h7l7 7-7 7-7-7zM7 7h.01"/>',
  'list' => '<path d="M6 5h11M6 10h11M6 15h11M3 5h.01M3 10h.01M3 15h.01"/>',
  'cog' => '<circle cx="10" cy="10" r="2.500"/><path d="M10 2v2.500M10 15.500V18M2 10h2.500M15.500 10H18M4.300 4.300l1.800 1.800M13.900 13.900l1.800 1.800M4.300 15.700l1.800-1.800M13.900 6.100l1.800-1.800"/>',
  'check' => '<path d="M4 10.500l4 4 8-9"/>',
  'x' => '<path d="M5 5l10 10M15 5L5 15"/>',
  'alert' => '<path d="M10 3l8 14H2zM10 8v4M10 14.500h.01"/>',
  'info' => '<circle cx="10" cy="10" r="7.500"/><path d="M10 9v5M10 6.500h.01"/>',
  'copy' => '<rect x="7" y="7" width="10" height="10" rx="2"/><path d="M13 7V5a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2"/>',
  'search' => '<circle cx="9" cy="9" r="5.500"/><path d="M13.500 13.500L17 17"/>',
  'external' => '<path d="M8 4H4v12h12v-4M11 3h6v6M17 3l-8 8"/>',
  'lock' => '<rect x="4" y="9" width="12" height="8" rx="2"/><path d="M7 9V6a3 3 0 016 0v3"/>',
  'menu' => '<path d="M3 5h14M3 10h14M3 15h14"/>',
  'sun' => '<circle cx="10" cy="10" r="3.500"/><path d="M10 2v2M10 16v2M2 10h2M16 10h2M4.400 4.400l1.400 1.400M14.200 14.200l1.400 1.400M4.400 15.600l1.400-1.400M14.200 5.800l1.400-1.400"/>',
];
@endphp
<svg class="icon" viewBox="0 0 20 20" @if ($label) role="img" aria-label="{{ $label }}" @else aria-hidden="true" @endif>{!! $paths[$name] ?? '' !!}</svg>
