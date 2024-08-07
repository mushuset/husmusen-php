@extends('layouts.app')

@section('head')
<title>Om Databasen</title>
@endsection

@section('body')
@if(isset($err))
<p>{{ $err }}</p>
@else

<h1>Om {{ $db_info->museumDetails->name }}</h1>
<p>{{ $db_info->museumDetails->description }}</p>
<p>Mer information om museet finner du på <a href="{{ $db_info->museumDetails->website }}">Vår hemsida</a></p>

<h2>Du finner oss här:</h2>
<p>
    Adress: {{ $db_info->museumDetails->address }} <br>
    Plats: {{ $db_info->museumDetails->location }} <br>
    Koordinater: {{ $db_info->museumDetails->coordinates }} <br>
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
@endif
@endsection
