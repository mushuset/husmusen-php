@extends('layouts.app')

@section('head')
<script src="{{ config('husmusen.mount_path') }}/resources/scripts/controlPanel.js" async defer type="module"></script>
@endsection

@section('body')
<form action="{{ config('husmusen.mount_path') }}/api/1.0.0/keyword" method="post" id="edit-keywords-form">
    <h1>Redigera nyckelord:</h1>
    <textarea name="newKeywordData" rows="20" class="full-width monospace">{{ $keywordsAsText }}</textarea>
    <p style="margin-top: .5rem;">
        Nyckelorden ska formateras på följande vis:
        <code>typ: ord: beskrivning</code>
        <br>
        <b>Typ</b> är något av följande (OBS, skiftläge-känsligt!):
        <i>ArtPiece</i>, <i>Blueprint</i>, <i>Book</i>, <i>Building</i>,
        <i>Collection</i>, <i>Concept</i>, <i>CulturalEnvironment</i>, <i>CulturalHeritage</i>,
        <i>Document</i>, <i>Exhibition</i>, <i>Film</i>, <i>Group</i>,
        <i>HistoricalEvent</i>, <i>InteractiveResource</i>, <i>Map</i>, <i>Organisation</i>,
        <i>Person</i>, <i>Photo</i>, <i>PhysicalItem</i>, <i>Sketch</i>, <i>Sound</i>
        <br>
        <b>Ord</b> är själva nyckelordet.
        <br>
        <b>Beskrivning</b> är en kort beskrivning av nyckelordet.
    </p>
    <input type="submit" value="Spara!">
</form>
@endsection
