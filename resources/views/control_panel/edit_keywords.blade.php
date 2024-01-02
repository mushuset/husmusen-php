@extends('layouts.app')

@section('head')
    <script src="/resources/scripts/controlPanel.js" async defer type="module"></script>
@endsection

@section('body')
    {% if err %}
        {{ err}}
    {% else %}
        <form action="/api/1.0.0/keyword" method="post" id="edit-keywords-form">
            <h1>Redigera nyckelord:</h1>
            <textarea name="newKeywordData" cols="120" rows="40" style="font-family: monospace">{{ keywordsAsText }}</textarea>
            <input type="submit" value="Spara!">
        </form>
    {% endif %}
@endsection