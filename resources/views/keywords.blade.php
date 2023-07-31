@extends('layouts.app')

@section('head')
<title>Nyckelord</title>
@endsection

@section('body')
@if(isset($err))
<h1>Error!</h1>
<p>{{ $err }}</p>
@else
<div class="keywords">
    <p style="grid-column: span 3">Tips! Du kan söka bland nyckelorden i de flesta webbläsare genom att klicka CTRL + F. <br></p>
    <p><b>Typ:</b></p>
    <p><b>Ord:</b></p>
    <p><b>Beskrivning:</b></p>
    @foreach($keywords as $key => $keyword)
    <p class="type">{{ $keyword->type }}</p>
    <p class="word">{{ $keyword->word }}</p>
    <p class="description">{{ $keyword->description }}</p>
    @endforeach
</div>
@endif
@endsection
