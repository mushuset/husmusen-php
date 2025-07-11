@extends('layouts.app')

@section('head')
<script src="{{ env('HUSMUSEN_MOUNT_PATH', '') }}/resources/scripts/controlPanel.js" async defer type="module"></script>
@endsection

@section('body')
<form action="{{ env('HUSMUSEN_MOUNT_PATH', '') }}/api/1.0.0/keyword" method="post" id="edit-keywords-form">
    <h1>Redigera nyckelord:</h1>
    <textarea name="newKeywordData" rows="20" class="full-width monospace">{{ $keywordsAsText }}</textarea>
    <input type="submit" value="Spara!">
</form>
@endsection
