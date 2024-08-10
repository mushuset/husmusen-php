<label for="{{ $is_editing ? 'newItemData.' : '' }}itemData.{{ $name }}">{{ $label }}:</label>
<input type="{{ $type }}" name="{{ $is_editing ? 'newItemData.' : '' }}itemData.{{ $name }}"
    id="{{ $is_editing ? 'newItemData.' : '' }}itemData.{{ $name }}" placeholder="{{ $placeholder }}"
    value="{{ $item['itemData'][$name] ?? '' }}">
@if(isset($hint))
<p class="hint">{{ $hint }}</p>
@endif
