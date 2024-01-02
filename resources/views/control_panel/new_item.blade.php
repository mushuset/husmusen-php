@extends('layouts.app')

@section('head')
    <script src="/resources/scripts/controlPanel.js" async defer type="module"></script>
@endsection

@section('body')
    <form action="/api/1.0.0/item/new" method="post" class="YAML">
        <h1>Skapa nytt objekt:</h1>
<textarea name="YAML" cols="120" rows="20" style="font-family: monospace">
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
@endsection