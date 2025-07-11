@extends('layouts.app')

@section('head')
<script src="{{ env('HUSMUSEN_MOUNT_PATH', '') }}/resources/scripts/controlPanel.js" async defer type="module"></script>
@endsection

@section('body')
@if(isset($err))
{{ $err }}
@else
<form action="{{ env('HUSMUSEN_MOUNT_PATH', '') }}/api/1.0.0/file/edit" method="post" class="auto-rig">
    <h1>Redigera fil:</h1>
    <div class="text-inputs">
        <label for="fileID">ID:</label>
        <input type="text" name="fileID" id="fileID" value="{{ $file['fileID'] }}" readonly>
        <p class="hint">ID:t kan inte ändras manuellt.</p>

        <label for="newFileData.name">Namn:</label>
        <input type="text" name="newFileData.name" id="newFileData.name" placeholder="En vild på vasen"
            value="{{ $file['name'] }}">
        <p class="hint">Namnet borde vara en lagom kombination av beskrivande och kort.</p>

        <label for="newFileData.license">Rättigheter:</label>
        <input type="text" name="newFileData.license" id="newFileData.license" placeholder="All rights reserved"
            value="{{ $file['license'] }}">
        <p class="hint">En beteckning på rättigheter till filen. Ex: <i>CC0</i> eller <i>All rights reserved</i></p>

        <label for="newFileData.relatedItem">Tillhör:</label>
        <input type="text" name="newFileData.relatedItem" id="newFileData.relatedItem" placeholder="All rights reserved"
            value="{{ $file['relatedItem'] }}">
        <p class="hint">Fyll i det inventarienummer på det objekt som filen tillhör.</p>

    </div>

    <label for="newFileData.description">Beskrivning av filen:</label>
    <textarea name="newFileData.description" id="newFileData.description" rows="10"
        placeholder="Skriv här.">{{ $file['description'] }}</textarea>
    <p class="hint">Här kan du skriva in mer detaljerad information om filen.</p>


    <input type="submit" value="Redigera fil!">
</form>
@endif
@endsection
