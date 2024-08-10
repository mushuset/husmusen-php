@extends('layouts.app')

@section('head')
<title>Husmusen</title>
<script src="/resources/scripts/getItemById.js" async defer type="module"></script>
@endsection

@section('body')
<h1>Välkommen till Husmusen!</h1>
<h2>Vad vill du söka efter idag?</h2>
<x-search_box />

<form action="#" id="get-item-by-id">
    <h2>Hitta föremål efter inventarienummer</h2>
    <div class="text-inputs">
        <label for="item-id">Vilket inventarienummer har föremålet?</label>
        <input type="text" name="itemID" id="item-id">
    </div>
    <input type="submit" value="Hämta föremål!">
</form>

<h2>Se en lista över alla föremål</h2>
<p>
    Det finns en lista på alla föremål som finns i den här databasen. Den är mest tänkt för att sökmotorer ska
    kunna läsa igenom alla föremål och göra dem sökbara på nätet, men om du också vill se den, så finns den <a
        href="/app/items">här</a>.
</p>
@endsection
