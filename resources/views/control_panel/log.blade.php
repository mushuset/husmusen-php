@extends('layouts.app')

@section('head')
    <title>Husmusen - Log</title>
    <script src="/resources/scripts/getServerLog.js" async defer type="module"></script>
@endsection

@section('body')
    <div class="log">
        <p class="info">Läser in loggen...</p>
    </div>
@endsection