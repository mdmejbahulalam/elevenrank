@props(['name', 'label', 'type' => 'text', 'value' => null, 'help' => null, 'required' => false, 'autocomplete' => null])
@php($err = $errors->first($name))
<label for="f-{{ $name }}">{{ $label }}</label>
<input id="f-{{ $name }}" name="{{ $name }}" type="{{ $type }}" @if ($type !== 'password') value="{{ old($name, $value) }}" @endif @if ($required) required @endif @if ($autocomplete) autocomplete="{{ $autocomplete }}" @endif @if ($err) aria-invalid="true" aria-describedby="e-{{ $name }}" @elseif ($help) aria-describedby="h-{{ $name }}" @endif>
@if ($help && ! $err)<div class="help" id="h-{{ $name }}">{{ $help }}</div>@endif
@if ($err)<div class="field-error" id="e-{{ $name }}">{{ $err }}</div>@endif
