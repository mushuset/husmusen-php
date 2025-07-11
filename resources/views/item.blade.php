@extends('layouts.app')

@section('head')
<title>{{ $item->name ?? "Error!" }}</title>
{{-- TODO: Load locally --}}
<link rel="stylesheet" href="https://unpkg.com/leaflet@1.9.4/dist/leaflet.css"
    integrity="sha256-p4NxAoJBhIIN+hmNHrzRCf9tD/miZyoHS5obTRR9BMY=" crossorigin="" />
<script src="https://unpkg.com/leaflet@1.9.4/dist/leaflet.js"
    integrity="sha256-20nQCchB9co0qIjJZRGuk2/Z9VM+kNiyxNV1lvTlZBo=" crossorigin=""></script>
@endsection

@section('body')
@if(isset($err))
<h1>Error!</h1>
<p>{{ $err }}</p>
@else
<div class="item single-view">
    <h1>{{ $item->name }}</h1>
    <p>{{ $item->description }}</p>
    <p>Inventarienummer: {{ $item->itemID }}</p>
    <p>Typ: {{ $item->type }}</p>
    <p>
        Nyckelord:
        @foreach(preg_split('/,/', $item->keywords) as $key => $keyword)
        <span class="keyword">{{ $keyword }}</span>
        @endforeach
    </p>
    <p>Tillagd: {{ $item->addedAt }}</p>
    <p>Uppdaterad: {{ $item->updatedAt }}</p>
    <table class="item-data">
        <h2>Gemensam data för objekttyp:</h2>
        @foreach($item->itemData as $field => $value)
        <tr>
            <td>{{ $field }}</td>
            <td>{{ $value }}</td>
        </tr>
        @endforeach
    </table>
    <table class="custom-data">
        <h2>Egen data:</h2>
        @foreach($item->customData as $field => $value)
        <tr>
            <td>{{ $field }}</td>
            <td>{{ $value }}</td>
        </tr>
        @endforeach
    </table>
    @if($item->isExpired)
    <p>Objektet har utgått!</p>
    <p>{{ $item->expireReason }}</p>
    @endif

    @if(isset($item->itemData['coordinates']))
    <h2>Karta</h2>
    <div style="height: 400px; width: 100%" id="map"></div>
    @endif

    <h2>Filer</h2>
    @if(isset($item->files[0]))
    @foreach($item->files as $key => $file)
    <div class="file">
        <h3>{{ $file->name }}</h3>
        <p>{{ $file->description }}</p>
        <p>Licens: {{ $file->license }}</p>
        <p>Typ av fil: {{ $file->type }}</p>
        @if(preg_match('/^image\/.*$/', $file->type))
        <p>Förhandsvisning:</p>
        <img src="{{ env('HUSMUSEN_MOUNT_PATH', '') }}/api/1.0.0/file/get/{{ $file->fileID }}"
            alt="{{ $file->description }}">
        @endif
        <p>
            Länkar:
            <a href="{{ env('HUSMUSEN_MOUNT_PATH', '') }}/api/1.0.0/file/get/{{ $file->fileID }}"
                download="{{ $file->name }}">Ladda ned!</a>
            <a href="{{ env('HUSMUSEN_MOUNT_PATH', '') }}/app/file/{{ $file->fileID }}">Perma-länk.</a>
        </p>
    </div>
    @endforeach
    @else
    <p>Det finns inga filer för det här föremålet!</p>
    @endif
</div>

@if(isset($item->itemData['coordinates']))
<script>
    // TODO: Extract to avoid duplicate code
    let coordinates = "{{ $item->itemData['coordinates'] }}".replace(/[^\d\,.-]/g, '').split(/, |,/)
    console.log(coordinates)
    let map = L.map('map').setView(coordinates, 15);

    L.tileLayer('https://tile.openstreetmap.org/{z}/{x}/{y}.png', {
        maxZoom: 19,
        attribution: '&copy; <a href="http://www.openstreetmap.org/copyright">OpenStreetMap</a>'
    }).addTo(map);

    let marker = L.marker(coordinates).addTo(map);
</script>
@endif

@endif
@endsection
