@extends('layouts.app')

@section('head')
<script src="/resources/scripts/controlPanel.js" async defer type="module"></script>
@endsection

@section('body')
@if(isset($err))
{{ $err }}
@else
<form action="/api/db_info" method="post" class="auto-rig">
    <h1>Redigera databas information:</h1>

    <div class="text-inputs">
        <label for="instanceName">Namn på instansen:</label>
        <input type="text" name="instanceName" id="instanceName" value="{{ $db_info['instanceName'] }}">

        <label for="museumDetails.name">Museets namn:</label>
        <input type="text" name="museumDetails.name" id="museumDetails-name"
            value="{{ $db_info['museumDetails']['name'] }}">

        <label for="museumDetails.address">Adress (gata):</label>
        <input type="text" name="museumDetails.address" id="museumDetails-address"
            value="{{ $db_info['museumDetails']['address'] }}">

        <label for="museumDetails.location">Plats (ort):</label>
        <input type="text" name="museumDetails.location" id="museumDetails-location"
            value="{{ $db_info['museumDetails']['location'] }}">

        <label for="museumDetails.coordinates">Koordinater:</label>
        <input type="text" name="museumDetails.coordinates" id="museumDetails-coordinates"
            value="{{ $db_info['museumDetails']['coordinates'] }}">

        <label for="museumDetails.website">Hemsida:</label>
        <input type="text" name="museumDetails.website" id="museumDetails-website"
            value="{{ $db_info['museumDetails']['website'] }}">
    </div>

    <label for="museumDetails.description">Beskrivning av museet:</label>
    <textarea rows="10" name="museumDetails.description"
        id="museumDetails-description">{{ $db_info['museumDetails']['description'] }}</textarea>

    <input type="submit" value="Spara!">
</form>
@endif
@endsection
