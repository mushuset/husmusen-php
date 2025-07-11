@extends('layouts.app')

{{--
    This gets the file extension for a file:
    preg_replace('/(?<=^\w+\/)\w+(?=(\+\S*)?$)/gm', '', $file->type)
--}}

@section('head')
<title>{{ $file->name ?? "Error!" }}</title>
@endsection

@section('body')
@if(isset($err))
<h1>Error!</h1>
<p>{{ $err }}</p>
@else
<div class="item single-view">
    <h1>{{ $file->name }}</h1>
    <p>{{ $file->description }}</p>
    <p>Licens: {{ $file->license }}</p>
    <p>Typ av fil: {{ $file->type }}</p>
    @if(preg_match('/^image\/.*$/', $file->type))
    <p>Förhandsvisning:</p>
    <img src="{{ env('HUSMUSEN_MOUNT_PATH', '') }}/api/1.0.0/file/get/{{ $file->fileID }}"
        alt="{{ $file->description }}">
    @endif
    <h2>Länkar</h2>
    <p>
        <a href="{{ env('HUSMUSEN_MOUNT_PATH', '') }}/api/1.0.0/file/get/{{ $file->fileID }}" download>Ladda ned!</a>
        <a href="{{ env('HUSMUSEN_MOUNT_PATH', '') }}/app/file/{{ $file->fileID }}">Perma-länk.</a>
        <a href="{{ env('HUSMUSEN_MOUNT_PATH', '') }}/app/item/{{ $file->relatedItem }}">Tillhör föremål
            {{ $file->relatedItem }}.</a>
    </p>
</div>
@endif
@endsection
