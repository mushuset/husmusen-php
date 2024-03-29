
@extends('layouts.app')

@section('head')
    <script src="/resources/scripts/controlPanel.js" async defer type="module"></script>
@endsection

@section('body')
    @if(isset($err))
        {{ $err }}
    @else
        <form action="/api/1.0.0/item/edit" method="post" id="edit-item-form">
            <h1>Redigera föremål:</h1>
            <input type="hidden" name="itemID" value="{{ $itemID }}">
            <textarea name="newItemData" cols="120" rows="20" style="font-family: monospace">{{ $itemAsYAML }}</textarea>
            <input type="submit" value="Redigera föremål!">
        </form>
    @endif
@endsection