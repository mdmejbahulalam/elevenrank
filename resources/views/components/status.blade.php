@props(['value', 'ends' => null])
@php
[$tone, $label] = match ((string) $value) {
    'paid' => ['ok', 'Paid'], 'active' => ['ok', 'Active'], 'solved', 'closed' => ['ok', 'Solved'], 'published' => ['ok', 'Published'], 'processed' => ['ok', 'Processed'],
    'pending' => ['warn', 'Pending'], 'open' => ['warn', 'Waiting for staff'],
    'refunded' => ['bad', 'Refunded'], 'revoked' => ['bad', 'Revoked'], 'failed' => ['bad', 'Failed'], 'cancelled' => ['bad', 'Cancelled'],
    'expired' => ['info', 'Term ended'], 'answered' => ['info', 'Replied'],
    default => ['', ucfirst((string) $value)],
};
@endphp
<span class="a-pill {{ $tone }}">{{ $label }}</span>
