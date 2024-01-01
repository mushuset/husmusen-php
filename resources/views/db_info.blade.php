@extends('layouts.app')

@section('head')
<title>Om Databasen</title>
@endsection

@section('body')
@if(isset($err))
<p>{{ $err }}</p>
@else
<h1>Om museet:</h1>
<table>
    @foreach($db_info->museumDetails as $field => $value)
    <tr>
        <td>{{ $field }}</td>
        <td>{{ $value }}</td>
    </tr>
    @endforeach
</table>
<h1>Om databasen:</h1>
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
