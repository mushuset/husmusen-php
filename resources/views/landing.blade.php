@extends('layouts.app')

@section('head')
<title>Husmusen</title>
<script src="/resources/scripts/getItemById.js" async defer type="module"></script>
@endsection

@section('body')
<h1>Välkommen till Husmusen!</h1>
<h2>Vad vill du söka efter idag?</h2>
@include('components.search_box')

<form action="#" id="get-item-by-id">
    <h2>Hitta föremål efter inventarie nummer</h2>
    <div class="text-inputs">
        <label for="item-id">Vilket inventarienummer har föremålet?</label>
        <input type="text" name="itemID" id="item-id">
    </div>
    <input type="submit" value="Hämta föremål!">
</form>
@endsection
