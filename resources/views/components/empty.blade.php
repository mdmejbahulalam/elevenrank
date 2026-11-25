@props(['icon' => 'info', 'title'])
<div class="empty"><div class="ic"><x-icon :name="$icon" /></div><p style="margin:0 0 4px;font-weight:600;color:var(--ink)">{{ $title }}</p><p style="margin:0">{{ $slot }}</p></div>
