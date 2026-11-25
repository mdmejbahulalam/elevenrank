@props(['name', 'label', 'type' => 'text', 'value' => null, 'help' => null, 'required' => false, 'autocomplete' => null, 'rows' => null, 'min' => null, 'max' => null, 'step' => null, 'maxlength' => null, 'placeholder' => null])
@php($err = $errors->first($name))
<label for="f-{{ $name }}">{{ $label }}</label>
@if ($rows)
<textarea id="f-{{ $name }}" name="{{ $name }}" rows="{{ $rows }}" @if ($maxlength) maxlength="{{ $maxlength }}" @endif @if ($required) required @endif @if ($err) aria-invalid="true" aria-describedby="e-{{ $name }}" @elseif ($help) aria-describedby="h-{{ $name }}" @endif>{{ old($name, $value) }}</textarea>
@else
<input id="f-{{ $name }}" name="{{ $name }}" type="{{ $type }}" @if ($type !== 'password') value="{{ old($name, $value) }}" @endif @if ($maxlength) maxlength="{{ $maxlength }}" @endif @if ($placeholder) placeholder="{{ $placeholder }}" @endif @if ($min !== null) min="{{ $min }}" @endif @if ($max !== null) max="{{ $max }}" @endif @if ($step) step="{{ $step }}" @endif @if ($required) required @endif @if ($autocomplete) autocomplete="{{ $autocomplete }}" @endif @if ($err) aria-invalid="true" aria-describedby="e-{{ $name }}" @elseif ($help) aria-describedby="h-{{ $name }}" @endif>
@endif
@if ($help && ! $err)<div class="help" id="h-{{ $name }}">{{ $help }}</div>@endif
@if ($err)<div class="field-error" id="e-{{ $name }}">{{ $err }}</div>@endif
