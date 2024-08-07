@extends('layouts.app')

@section('head')
<script src="/resources/scripts/controlPanel.js" async defer type="module"></script>
@endsection

@section('body')
<form action="/api/1.0.0/item/new" method="post" class="YAML">
    <h1>Skapa nytt objekt:</h1>
    <textarea name="YAML" rows="20" class="full-width monospace">
@include('components.parts.Item-yaml')

itemData:
{{ file_get_contents(resource_path('views/components/parts/ItemData_' . request()->query('type') . '.yml')) }}

@if (request()->query('customData', 'off') === "on")
customData:
  color: "Red"
  field: value
@endif
</textarea>
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
