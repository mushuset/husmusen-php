@extends('layouts.app')

@section('head')
<title>Husmusen setup!</title>
<script src="/resources/scripts/controlPanel.js" async defer type="module"></script>
@endsection

@section('body')
<form action="/api/auth/debug_admin_creation" method="post" class="auto-rig">
    <h1>Skapa en administratör</h1>
    <div class="text-inputs">
        <label for="username">Användarnamn:</label>
        <input type="text" name="username" id="username">
        <label for="password">Lösenord:</label>
        <input type="password" name="password" id="password">
    </div>
    <input type="submit" value="Skapa administratör!">
</form>
@endsection
