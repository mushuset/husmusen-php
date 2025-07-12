@extends('layouts.app')

@section('head')
<title>Husmusen - Log</title>
<script src="{{ config('husmusen.mount_path') }}/resources/scripts/getServerLog.js" async defer type="module"></script>
@endsection

@section('body')
<div class="log">
    <p class="info">Läser in loggen...</p>
</div>
@endsection
