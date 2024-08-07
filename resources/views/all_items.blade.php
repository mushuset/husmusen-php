@extends('layouts.app')

@section('body')
<h1>Alla föremål</h1>
<p>
    Nedan följer en lista på alla föremål som finns i den här databasen. Denna sida är mest tänkt för att sökmotorer ska
    kunna läsa igenom alla föremål och göra dem sökbara på nätet.
</p>
<div class="all-items">
    <p><b>ID:</b></p>
    <p><b>Typ:</b></p>
    <p><b>Namn:</b></p>
    <p><b>Beskrivning:</b></p>
    @foreach($items as $key => $item)
    <p class="id">{{ $item->itemID }}</p>
    <p class="type">{{ $item->type }}</p>
    <p class="name">{{ $item->name }} <a href="/app/item/{{ $item->itemID }}">(länk)</a></p>
    <p class="description">{{ $item->description }}</p>
    @endforeach
</div>
@endsection
