@extends('layouts.app')

@section('head')
<script src="/resources/scripts/controlPanel.js" async defer type="module"></script>
@endsection

@section('body')
<form action="/api/1.0.0/item/new" method="post" class="auto-rig">
    <h1>Skapa nytt objekt:</h1>

    <div class="text-inputs">
        <label for="type">Typ:</label>
        <input type="text" name="type" id="type" value="{{ request()->query('type') }}" disabled>
        <p class="hint">
            Du kan inte ändra det här värdet. Om du vill ändra vilken typ du skapar, gå tillbaka till förra sidan och
            välj en annan typ.
        </p>

        <label for="itemID">ID:</label>
        <input type="text" name="itemID" id="itemID" value="{{ $next_item_id }}" disabled>
        <p class="hint">
            Det här är bara en förhandsvisning av det inventarienummer som föremålet kommer få; det kan inte ändras
            manuellt.
        </p>

        <label for="name">Namn/titel:</label>
        <input type="text" name="namn" id="namn" placeholder="Gul vas i glas">
        <p class="hint">Namnet borde vara en lagom kombination av beskrivande och kort.</p>

        <label for="keywords">Nyckelord:</label>
        <input type="text" name="keywords" id="keywords" placeholder="Penna,Kulspetspenna,1900-tal">
        <p class="hint">Komma-separera nyckelorden utan mellanrum runt kommatecknen.</i></p>
    </div>

    <label for="description">Beskrivning av objektet:</label>
    <textarea name="description" id="description" rows="10" placeholder="Skriv här."></textarea>
    <p class="hint">Här kan du skriva in mer detaljerad information om objektet.</p>

    <div class="text-inputs item-data">
        @include('components/parts/ItemData_' . request()->query('type'))
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
