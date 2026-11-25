@extends('layouts.site')
@section('title', $title)
@section('content')
<section class="s-sec" style="padding:56px 0">
	<div class="s-wrap s-narrow s-prose">
		<p class="s-meta">{{ $title }} · Updated {{ $updated }}</p>
		<h1>{{ $title }}</h1>
		@foreach ($sections as $heading => $text)
			<h2>{{ $heading }}</h2>
			<p>{{ $text }}</p>
		@endforeach
	</div>
</section>
@endsection
