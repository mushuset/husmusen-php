@extends('layouts.app')

@section('head')
<script src="{{ env('HUSMUSEN_MOUNT_PATH', '') }}/resources/scripts/controlPanel.js" async defer type="module"></script>
@endsection

@section('body')
<form action="{{ env('HUSMUSEN_MOUNT_PATH', '') }}/api/1.0.0/item/new" method="post" class="auto-rig">
    <h1>Skapa nytt objekt:</h1>

    <div class="text-inputs">
        <label for="type">Typ:</label>
        <input type="text" name="type" id="type" value="{{ request()->query('type') }}" readonly>
        <p class="hint">
            Du kan inte ändra det här värdet. Om du vill ändra vilken typ du skapar, gå tillbaka till förra sidan och
            välj en annan typ.
        </p>

        <label for="itemID">ID:</label>
        <input type="text" name="itemID" id="itemID" value="{{ $next_item_id }}" readonly>
        <p class="hint">
            Det här är bara en förhandsvisning av det inventarienummer som föremålet kommer få; det kan inte ändras
            manuellt.
        </p>

        <label for="name">Namn/titel:</label>
        <input type="text" name="name" id="name" placeholder="Gul vas i glas">
        <p class="hint">Namnet borde vara en lagom kombination av beskrivande och kort.</p>

        <label for="keywords">Nyckelord:</label>
        <select name="keywords" id="keywords" multiple data-array-join=",">
            @foreach($keywords as $key => $keyword)
            <option value="{{ $keyword->word }}">{{ $keyword->word }}</option>
            @endforeach
        </select>
        <p class="hint">
            Här kan du välja de nyckelord du vill använda. Längst ned på sidan finner du definitioner av när de olika
            nyckelorden ska användas. Använd Ctrl (eller Cmd på Mac) för att välja flera nyckelord. (OBS! Om det finns
            många alternativ, så borde du kunna scrolla i listan!)
        </p>
    </div>

    <label for="description">Beskrivning av objektet:</label>
    <textarea name="description" id="description" rows="10" placeholder="Skriv här."></textarea>
    <p class="hint">Här kan du skriva in mer detaljerad information om objektet.</p>

    <h2>Beskrivande data för objektet:</h2>

    <div class="text-inputs item-data">
        @include('components/parts/ItemData_' . request()->query('type'))
    </div>

    <h2>Egna fält:</h2>
    <button id="add-custom-data">Lägg till fält</button>
    <p class="hint">Här kan du lägga till egna fält med valfritt namn och värde.</p>
    <div class="text-inputs" id="custom-data">
    </div>

    <input type="submit" value="Skapa!">
</form>
<div class="keywords">
    <p style="grid-column: span 3">Nedan ser du alla giltiga nyckelord för dina valda objektstyp. Ogilitiga nyckelord
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
@endsection
