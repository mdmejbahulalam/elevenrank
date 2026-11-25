@props(['value', 'label' => 'Copy'])
<div class="copyfield"><span>{{ $value }}</span><button type="button" class="btn secondary small" data-copy="{{ $value }}" data-copied="Copied to clipboard">{{ $label }}</button></div>
