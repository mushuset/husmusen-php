@extends('layouts.app')

@section('head')
<title>Husmusen - Logga in</title>
<script src="/resources/scripts/login.js" async defer type="module"></script>
@endsection

@section('body')
<form id="login-form" action="/app/control_panel" method="post">
    <h1>Logga in</h1>
    <p>För att komma åt kontrollpanelen behöver du logga in:</p>

    <div class="text-inputs">
        <label for="username">Användarnamn:</label>
        <input type="text" name="username" id="username">
        <label for="password">Lösenord:</label>
        <input type="password" name="password" id="password">
    </div>

    <input type="submit" value="Logga in!">
</form>
@endsection
