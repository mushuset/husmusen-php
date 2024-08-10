@extends('layouts.app')

@section('head')
<script src="/resources/scripts/controlPanel.js" async defer type="module"></script>
@endsection

@section('body')
@if(isset($err))
{{ $err }}
@else
<form action="/api/1.0.0/item/edit" method="post" id="edit-item-form">
    <h1>Redigera föremål:</h1>
    <input type="hidden" name="itemID" value="{{ $itemID }}">
    <textarea name="newItemData" rows="20" class="full-width monospace">{{ $itemAsYAML }}</textarea>
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
