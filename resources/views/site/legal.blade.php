@extends('layouts.site')
@section('title', $title)
@section('content')
<div class="wrap page narrow">
	<h1>{{ $title }}</h1>
	<p class="sub">Last updated {{ $updated }}</p>
	@foreach ($sections as $heading => $text)
		<h2>{{ $heading }}</h2>
		<p>{{ $text }}</p>
	@endforeach
</div>
@endsection
