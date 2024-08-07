@extends('layouts.app')

@section('head')
<script src="/resources/scripts/controlPanel.js" async defer type="module"></script>
@endsection

@section('body')
@if(isset($err))
{{ $err }}
@else
<form action="/api/db_info" method="post" class="YAML">
    <h1>Redigera databas information:</h1>
    <textarea name="YAML" rows="20" class="full-width monospace">{{ $dbInfoAsYAML }}</textarea>
    <input type="submit" value="Spara!">
</form>
@endif
@endsection
