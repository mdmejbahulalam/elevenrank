@props(['headings' => [], 'right' => []])
<div class="a-table-wrap"><table {{ $attributes->class(['a-table']) }}>
<thead><tr>@foreach ($headings as $i => $h)<th scope="col" @if (in_array($i, $right, true)) style="text-align:right" @endif>{{ $h }}</th>@endforeach</tr></thead>
<tbody>{{ $slot }}</tbody>
</table></div>
