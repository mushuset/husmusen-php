@extends('layouts.app')

@section('head')
<script src="/resources/scripts/controlPanel.js" async defer type="module"></script>
@endsection

@section('body')
<form action="/api/1.0.0/keyword" method="post" id="edit-keywords-form">
    <h1>Redigera nyckelord:</h1>
    <textarea name="newKeywordData" rows="20" class="full-width monospace">{{ $keywordsAsText }}</textarea>
    <input type="submit" value="Spara!">
</form>
@endsection
