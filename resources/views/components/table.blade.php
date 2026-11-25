@props(['headings' => []])
<div class="table-wrap"><table {{ $attributes }}>
<thead><tr>@foreach ($headings as $h)<th scope="col">{{ $h }}</th>@endforeach</tr></thead>
<tbody>{{ $slot }}</tbody>
</table></div>
