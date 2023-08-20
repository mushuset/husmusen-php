@extends('layouts.app')

@section('head')
<title>{{ $item->name ?? "Error!" }}</title>
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
        @foreach($item->itemData as $field => $value)
        <tr>
            <td>{{ $field }}</td>
            <td>{{ $value }}</td>
        </tr>
        @endforeach
    </table>
    <table class="custom-data">
        @foreach($item->customData as $field => $value)
        <tr>
            <td>{{ field }}</td>
            <td>{{ value }}</td>
        </tr>
        @endforeach
    </table>
    @if($item->isExpired)
    <p>Objektet har utgått!</p>
    <p>$item->expireReason</p>
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
        <img src="/api/1.0.0/file/get/{{ $file->url->data }}.{{ preg_replace('/(?<=^\w+\/)\w+(?=(\+\S*)?$)/gm', '', $file->type) }}" alt="{{ $file->description }}">
        @endif
        <p>
            Länkar:
            <a href="/api/1.0.0/file/get/{{ $file->fileID }}.{{ preg_replace('/(?<=^\w+\/)\w+(?=(\+\S*)?$)/gm', '', $file->type) }}" download>Ladda ned!</a>
            <a href="/app/file/{{ $file->fileID }}">Perma-länk.</a>
        </p>
    </div>
    @endforeach
    @else
    <p>Det finns inga filer för det här föremålet!</p>
    @endif
</div>
@endif
@endsection
