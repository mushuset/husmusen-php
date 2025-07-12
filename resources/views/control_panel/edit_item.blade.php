@extends('layouts.app')

@section('head')
<script src="{{ config('husmusen.mount_path') }}/resources/scripts/controlPanel.js" async defer type="module"></script>
@endsection

@section('body')
@if(isset($err))
{{ $err }}
@else
<form action="{{ config('husmusen.mount_path') }}/api/1.0.0/item/edit" method="post" class="auto-rig">
    <h1>Redigera föremål:</h1>

    <div class="text-inputs">
        <label for="newItemData.type">Typ:</label>
        <input type="text" name="newItemData.type" id="newItemData.type" value="{{ $item['type'] }}" readonly>
        <p class="hint">
            Du kan tyvärr inte redigera ett objekts typ.
        </p>

        <label for="itemID">ID:</label>
        <input type="text" name="itemID" id="itemID" value="{{ $item['itemID'] }}" readonly>
        <p class="hint">Du kan inte ändra ett objekts ID.</p>

        <label for="newItemData.name">Namn/titel:</label>
        <input type="text" name="newItemData.name" id="newItemData.name" placeholder="Gul vas i glas"
            value="{{ $item['name'] }}">
        <p class="hint">Namnet borde vara en lagom kombination av beskrivande och kort.</p>

        <label for="newItemData.keywords">Nyckelord:</label>
        <select name="newItemData.keywords" id="newItemData.keywords" multiple data-array-join=",">
            @foreach($keywords as $key => $keyword)
            <option value="{{ $keyword->word }}" @if (str_contains($item['keywords'], $keyword->word)) selected
                @endif>
                {{ $keyword->word }}</option>
            @endforeach
        </select>
        <p class="hint">
            Här kan du välja de nyckelord du vill använda. Längst ned på sidan finner du definitioner av när de olika
            nyckelorden ska användas. Använd Ctrl (eller Cmd på Mac) för att välja flera nyckelord. (OBS! Om det finns
            många alternativ, så borde du kunna scrolla i listan!)
        </p>
    </div>

    <label for="newItemData.description">Beskrivning av objektet:</label>
    <textarea name="newItemData.description" id="newItemData.description" rows="10"
        placeholder="Skriv här.">{{ $item['description'] }}</textarea>
    <p class="hint">Här kan du skriva in mer detaljerad information om objektet.</p>

    <h2>Beskrivande data för objektet:</h2>

    <div class="text-inputs item-data">
        @include('components/parts/ItemData_' . $item['type'])
    </div>

    <h2>Egna fält:</h2>
    <button id="add-custom-data">Lägg till fält</button>
    <p class="hint">Här kan du lägga till egna fält med valfritt namn och värde.</p>
    <div class="text-inputs" id="custom-data">
        @foreach($item['customData'] as $field => $value)
        <label for="newItemData.customData.{{ $field }}">{{ $field }}</label>
        <input type="{{ is_numeric($value) ? 'number' : 'text' }}" name="newItemData.customData.{{ $field }}"
            id="newItemData.customData.{{ $field }}" value="{{ $value }}">
        @endforeach
    </div>

    <input type="submit" value="Redigera föremål!">
</form>
<div class="keywords">
    <p style="grid-column: span 3">Nedan ser du alla giltiga nyckelord för dina valda objektstyp. Ogiltiga nyckelord
        kommer filtreras bort automatiskt av systemet.</p>
    <p style="grid-column: span 3">Tips! Du kan söka bland nyckelorden i de flesta webbläsare genom att klicka CTRL + F.
        <br>
    </p>
    <p><b>Typ:</b></p>
    <p><b>Ord:</b></p>
    <p><b>Beskrivning:</b></p>
    @foreach($keywords as $key => $keyword)
    <p class="type">{{ $keyword->type }}</p>
    <p class="word">{{ $keyword->word }}</p>
    <p class="description">{{ $keyword->description }}</p>
    @endforeach
</div>
@endif
@endsection
