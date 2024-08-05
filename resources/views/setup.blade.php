@extends('layouts.app')

@section('head')
<title>Husmusen setup!</title>
@endsection

@section('body')
<h1>Välkommen till Husmusens setup-sida!</h1>
<p>
    Den här sidan guidar er medan ni sätter upp er instans av <i>Husmusen</i>. <br>
    <b>Om du inte är administratör</b> på museet, kontakta museet omedelbart och berätta att denna sida ligger uppe!
</p>
<hr>

<h2>Steg 1: Information om er instans</h2>
<p>Fyll i formuläret nedan och klicka sedan på knappen för att spara.</p>
<form action="/setup/instance-info" method="post">
    <div class="text-inputs">
        <label for="APP_NAME">Instansens namn:</label>
        <input type="text" name="APP_NAME" id="APP_NAME" placeholder="Husmusen på Museet">

        <label for="APP_URL">Instansens URL:</label>
        <input type="url" name="APP_URL" id="APP_URL" placeholder="https://exempel-doman.se">
    </div>

    <input type="submit" value="Spara!">
</form>
<hr>

<h2>Steg 2: Information om ert museum</h2>
<p>
    Ändra informationen nedan så den passar ert museum och klicka sedan på knappen för att spara. Se till att bara ändra
    det som står mellan citat-tecknena!
</p>
<form action="/setup/museum-info" method="post">
    <textarea name="db-info" id="db-info" cols="80" rows="15" style="font-family: monospace">
# Ändra inget från hit ...
protocolVersion: "1.0.0"
protocolVersions: [ "1.0.0" ]
supportedInputFormats: [ "application/yaml", "application/json" ]
supportedOutputFormats: [ "application/yaml", "application/json" ]
# ... till hit!
# Här nedan kan ni ändra!
instanceName: "Husmusen på Museum"
museumDetails:
  name: "Museum"
  description: "Ett helt vanligt museum."
  address: "Gatanvägen 4"
  location: "Kungshamn"
  coordinates: "0°0′0″ N, 25°0′0″ W"
  website: "https://example.com"</textarea>

    <input type="submit" value="Spara!">
</form>
<hr>


<h2>Steg 3: Information om databasen</h2>
<p>
    Fyll i formuläret nedan och klicka sedan på knappen för att spara. Du bör kunna få den här informationen från ditt
    webbhotell eller IT-ansvarig (eller dylikt) på ditt museum.
</p>
<form action="/setup/db-info" method="post">
    <div class="text-inputs">
        <label for="DB_CONNECTION">Vilken databasmjukvara använder ni?</label>
        <select name="DB_CONNECTION" id="DB_CONNECTION">
            <option value="mysql">MySQL / MariaDB</option>
            <option value="--" disabled>Mer i framtiden...</option>
            <!-- TODO: Make it possible to use other databases... -->
        </select>

        <label for="DB_HOST">Databasens host (värd):</label>
        <input type="text" name="DB_HOST" id="DB_HOST" placeholder="127.0.0.1">

        <label for="DB_PORT">Databasens port:</label>
        <input type="number" min="1" max="65536" name="DB_PORT" id="DB_PORT" placeholder="3306">

        <label for="DB_DATABASE">Databasens namn:</label>
        <input type="text" name="DB_DATABASE" id="DB_DATABASE" placeholder="husmusen">

        <label for="DB_USERNAME">Användarnamn till databasen:</label>
        <input type="text" name="DB_USERNAME" id="DB_USERNAME" placeholder="husmusen">

        <label for="DB_PASSWORD">Lösenord till databasen:</label>
        <input type="password" name="DB_PASSWORD" id="DB_PASSWORD" placeholder="SuperHemligt,BraLösenord!78">
    </div>

    <input type="submit" value="Spara!">
</form>
<hr>

<h2>Steg 4: Skapa tabeller</h2>
<p>Klicka på knappen för att skapa de tabeller i databasen som Husmusen behöver.</p>
<form>
    <input type="submit" value="Skapa tabeller!" id="create-tables">
</form>
<hr>

<h2>Steg 5: Skapa en administratör</h2>
<form action="/api/auth/debug_admin_creation" method="post" class="auto-rig">
    <div class="text-inputs">
        <label for="username">Användarnamn:</label>
        <input type="text" name="username" id="username">
        <label for="password">Lösenord:</label>
        <input type="password" name="password" id="password">
    </div>
    <input type="submit" value="Skapa administratör!">
</form>
<hr>

<h2>Steg 6: Nu är du snart klar...</h2>
<p>
    Klicka på knappen nedan för att stänga av setup-funktionen (den här sidan). Detta är nödvändigt för att ingen annan
    ska kunna använda denna sida för att skapa en egen administratör!
</p>
<p>
    Efter ni har klickat på knappen borde ni inte längre kunna komma åt den här sidan! Om ni gör det har något gått fel,
    och ni bör testa att klicka på knappen igen. Funkar inte det, så börja om er installationsprocess helt från början.
</p>
<form action="/setup/done" method="post">
    <input type="submit" value="Jag är klar!">
</form>
<hr style="margin-bottom: 30vmin">

@endsection
