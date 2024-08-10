@extends('layouts.app')

@section('head')
<title>Om Databasen</title>
{{-- TODO: Load locally --}}
<link rel="stylesheet" href="https://unpkg.com/leaflet@1.9.4/dist/leaflet.css"
     integrity="sha256-p4NxAoJBhIIN+hmNHrzRCf9tD/miZyoHS5obTRR9BMY="
     crossorigin=""/>
<script src="https://unpkg.com/leaflet@1.9.4/dist/leaflet.js"
    integrity="sha256-20nQCchB9co0qIjJZRGuk2/Z9VM+kNiyxNV1lvTlZBo="
    crossorigin=""></script>
@endsection

@section('body')
@if(isset($err))
<p>{{ $err }}</p>
@else

<h1>Om {{ $db_info->museumDetails->name }}</h1>
<p>{{ $db_info->museumDetails->description }}</p>
<p>Mer information om museet finner du på <a href="{{ $db_info->museumDetails->website }}">vår hemsida</a>.</p>

<h2>Du finner oss här:</h2>
<p>
    <b>Adress:</b> {{ $db_info->museumDetails->address }} <br>
    <b>Plats:</b> {{ $db_info->museumDetails->location }} <br>
    <b>Koordinater:</b> {{ $db_info->museumDetails->coordinates }} <br>
    <div style="height: 400px; width: 100%" id="map"></div>
</p>

<h2>Adcancerad infromation om databasen:</h2>
<table>
    @foreach($db_info as $field => $value)
    @unless($field == 'museumDetails')
    <tr>
        <td>{{ $field }}</td>
        <td>{{ is_array($value) ? join(',', $value) : $value }}</td>
    </tr>
    @endunless
    @endforeach
</table>

<script>
// TODO: Extract to avoid duplicate code
let coordinates = "{{ $db_info->museumDetails->coordinates }}".replace(/[^\d\,.-]/g, '').split(/, |,/)
console.log(coordinates)
let map = L.map('map').setView(coordinates, 15);

L.tileLayer('https://tile.openstreetmap.org/{z}/{x}/{y}.png', {
    maxZoom: 19,
    attribution: '&copy; <a href="http://www.openstreetmap.org/copyright">OpenStreetMap</a>'
}).addTo(map);

let marker = L.marker(coordinates).addTo(map);
</script>

@endif
@endsection
