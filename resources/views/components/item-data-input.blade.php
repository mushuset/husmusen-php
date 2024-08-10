<label for="itemData.{{ $name }}">{{ $label }}</label>
<input type="{{ $type }}" name="itemData.{{ $name }}" id="itemData.{{ $name }}" placeholder="{{ $placeholder }}"
    @if(isset($value)) value="{{ $value }}" @endif>
@if(isset($hint))
<p class="hint">{{ $hint }}</p>
@endif
