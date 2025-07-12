@extends('layouts.app')

@section('head')
<title>Husmusen - Sök</title>
<script src="{{ config('husmusen.mount_path') }}/resources/scripts/search.js" async defer type="module"></script>
@endsection

@section('body')
<h2>Vad vill du söka efter idag?</h2>
<h1>Sökning:</h1>
<x-search_box :queries=$queries />

<h1>Resultat:</h1>
<div id="search-results">
    <p style="margin-bottom: 50vh">Söker efter resultat...</p>
</div>
@endsection
