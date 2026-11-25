@props(['value'])
@php
$tone = match ((string) $value) {
    'paid', 'active', 'solved', 'published', 'open' => 'ok',
    'pending', 'waiting' => 'warn',
    'refunded', 'revoked', 'failed' => 'bad',
    'expired', 'answered' => 'info',
    default => 'neutral',
};
@endphp
<x-badge :tone="$tone">{{ $value }}</x-badge>
