@extends('layouts.app')

@section('head')
<script src="/resources/scripts/controlPanel.js" async defer type="module"></script>
@endsection

@section('body')
@if(isset($err))
{{ $err }}
@else
<form action="/api/1.0.0/file/edit" method="post" id="edit-file-form">
    <h1>Redigera fil:</h1>
    <input type="hidden" name="fileID" value="{{ $fileID }}">
    <textarea name="newFileData" rows="20" class="full-width monospace">{{ $fileAsYAML}}</textarea>
    <input type="submit" value="Redigera fil!">
</form>
@endif
@endsection
