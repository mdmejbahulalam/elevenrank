@php $c = ['info' => ['#e8f1fe', '#175cd3'], 'warn' => ['#fef0c7', '#93370d'], 'bad' => ['#fde9e7', '#b42318']][$tone ?? 'info']; @endphp
<div style="margin:0 0 20px;padding:14px 16px;border-radius:12px;background:{{ $c[0] }};border:1px solid {{ $c[1] }};font-size:15px;line-height:22px"><strong>{{ $lead }}</strong> {{ $text }}</div>
