@props(['id', 'title'])
<dialog id="{{ $id }}" class="modal" aria-labelledby="{{ $id }}-t"><h2 id="{{ $id }}-t" style="margin-top:0">{{ $title }}</h2>{{ $slot }}</dialog>
