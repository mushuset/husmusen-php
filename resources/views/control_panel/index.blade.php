@extends('layouts.app')

@section('head')
<title>Husmusen - Kontrollpanel</title>
<script src="{{ config('husmusen.mount_path') }}/resources/scripts/controlPanel.js" async defer type="module"></script>
@endsection

@section('body')
<h1>Välkommen till kontrollpanelen!</h1>
<section>
    <h1>Föremål</h1>
    <form action="{{ config('husmusen.mount_path') }}/app/control_panel/new_item" method="get">
        <h2>Skapa nytt föremål</h2>
        <p>Välj den typ av föremål du vill skapa:</p>
        <div class="select-switch-container">
            <input class="select-switch" type="radio" name="type" id="book" value="Book">
            <label for="book">Bok</label>
            <input class="select-switch" type="radio" name="type" id="building" value="Building">
            <label for="building">Byggnad</label>
            <input class="select-switch" type="radio" name="type" id="document" value="Document">
            <label for="document">Dokument</label>
            <input class="select-switch" type="radio" name="type" id="film" value="Film">
            <label for="film">Film</label>
            <input class="select-switch" type="radio" name="type" id="photo" value="Photo">
            <label for="photo">Foto</label>
            <input class="select-switch" type="radio" name="type" id="physicalitem" value="PhysicalItem">
            <label for="physicalitem">Fysiskt ting</label>
            <input class="select-switch" type="radio" name="type" id="group" value="Group">
            <label for="group">Grupp</label>
            <input class="select-switch" type="radio" name="type" id="historicalevent" value="HistoricalEvent">
            <label for="historicalevent">Historisk händelse</label>
            <input class="select-switch" type="radio" name="type" id="interactiveresource" value="InteractiveResource">
            <label for="interactiveresource">Interaktiv resurs</label>
            <input class="select-switch" type="radio" name="type" id="map" value="Map">
            <label for="map">Karta</label>
            <input class="select-switch" type="radio" name="type" id="concept" value="Concept">
            <label for="concept">Koncept</label>
            <input class="select-switch" type="radio" name="type" id="artpiece" value="ArtPiece">
            <label for="artpiece">Konstverk</label>
            <input class="select-switch" type="radio" name="type" id="culturalenvironment" value="CulturalEnvironment">
            <label for="culturalenvironment">Kulturell miljö</label>
            <input class="select-switch" type="radio" name="type" id="culturalheritage" value="CulturalHeritage">
            <label for="culturalheritage">Kulturminne</label>
            <input class="select-switch" type="radio" name="type" id="sound" value="Sound">
            <label for="sound">Ljud</label>
            <input class="select-switch" type="radio" name="type" id="organisation" value="Organisation">
            <label for="organisation">Organisation</label>
            <input class="select-switch" type="radio" name="type" id="person" value="Person">
            <label for="person">Person</label>
            <input class="select-switch" type="radio" name="type" id="blueprint" value="Blueprint">
            <label for="blueprint">Ritning</label>
            <input class="select-switch" type="radio" name="type" id="collection" value="Collection">
            <label for="collection">Samling</label>
            <input class="select-switch" type="radio" name="type" id="sketch" value="Sketch">
            <label for="sketch">Skiss</label>
            <input class="select-switch" type="radio" name="type" id="exhibition" value="Exhibition">
            <label for="exhibition">Utställning</label>
        </div>
        <input type="submit" value="Skapa!">
    </form>

    <form action="{{ config('husmusen.mount_path') }}/app/control_panel/edit_item" method="get">
        <h2>Redigera föremål</h2>
        <label for="item-id">Vilket ID har föremålet du vill redigera?</label>
        <input type="text" name="itemID" id="item-id">
        <input type="submit" value="Redigera!">
    </form>

    <form action="{{ config('husmusen.mount_path') }}/api/1.0.0/item/mark" method="post" class="auto-rig">
        <h2>Markera som utgånget</h2>
        <label for="item-id">Vilket ID har föremålet du markera som utgånget?</label>
        <input type="text" id="item-id" name="itemID">
        <label for="reason">Vad är anledningen? (Obligatoriskt!)</label>
        <textarea name="reason" id="reason" rows="2"></textarea>
        <input type="submit" value="Markera som utgånget!">
    </form>

    <form action="{{ config('husmusen.mount_path') }}/api/1.0.0/item/delete" method="post" class="auto-rig">
        <h2>Permanent borttagning</h2>
        <label for="item-id">Vilket ID har föremålet du vill ta bort?</label>
        <input type="text" id="item-id" name="itemID">
        <input type="submit" value="Ta bort!">
        <p>Endast en admin kan göra det här!</p>
    </form>

    <form>
        <h2>Redigera nyckelorden</h2>
        <a href="{{ config('husmusen.mount_path') }}/app/control_panel/edit_keywords">Klicka här för att redigera
            nyckelorden.</a>
        <p>Endast en admin kan göra det här!</p>
    </form>
</section>

<section>
    <h1>Filer</h1>

    <form action="{{ config('husmusen.mount_path') }}/api/1.0.0/file/new" method="post" id="file-creation-form">
        <h2>Skapa ny fil</h2>
        <div class="text-inputs">
            <label for="name">Namn:</label>
            <input type="text" name="name" id="name">

            <label for="license">Licens:</label>
            <input type="text" name="license" id="license">

            <label for="related-item">Tillhör föremål:</label>
            <input type="text" name="relatedItem" id="related-item">
        </div>

        <label for="description">Beskrivning:</label>
        <textarea rows="5" name="description" id="description"></textarea>

        <label for="file-data-buffer">Välj din fil här:</label>
        <input type="file" name="fileDataBuffer" id="file-data-buffer">

        <input type="submit" value="Skapa!">
    </form>

    <form action="{{ config('husmusen.mount_path') }}/app/control_panel/edit_file" method="get">
        <h2>Redigera fil</h2>
        <label for="file-id">Vilket ID har filen du vill redigera?</label>
        <input type="text" name="fileID" id="file-id">
        <input type="submit" value="Redigera!">
    </form>

    <form action="{{ config('husmusen.mount_path') }}/api/1.0.0/file/delete" method="post" class="auto-rig">
        <h2>Permanent ta bort fil</h2>
        <label for="file-id">Vilket ID har filen du vill ta bort?</label>
        <input type="text" name="fileID" id="file-id">
        <input type="submit" value="Ta bort!">
    </form>
</section>

<section>
    <h1>Min användare</h1>

    <form action="{{ config('husmusen.mount_path') }}/api/auth/change_password" method="post" class="auto-rig">
        <h2>Ändra lösenord</h2>
        <div class="text-inputs">
            <label for="current-password">Nuvarande lösenord:</label>
            <input type="password" name="currentPassword" id="current-password">
            <label for="new-password">Nytt lösenord:</label>
            <input type="password" name="newPassword" id="new-password">
        </div>
        <input type="submit" value="Byt lösenord!">
    </form>

    <form action="#" id="log-out-form">
        <h2>Logga ut</h2>
        <input type="submit" value="Logga ut!">
    </form>
</section>

<section>
    <h1>Administrativa funktioner</h1>

    <form action="{{ config('husmusen.mount_path') }}/api/auth/new" method="post" class="auto-rig">
        <h2>Skapa användare</h2>
        <div class="text-inputs">
            <label for="usename">Användarnamn:</label>
            <input type="text" name="username" id="usename">
            <label for="password">Lösenord:</label>
            <input type="password" name="password" id="password">
        </div>
        <div>
            <input type="checkbox" name="isAdmin" id="isAdmin">
            <label for="isAdmin">Denna användare ska vara en admin.</label>
        </div>
        <input type="submit" value="Skapa!">
        <p>Endast en admin kan göra det här!</p>
    </form>

    <form action="{{ config('husmusen.mount_path') }}/api/auth/delete" method="post" class="auto-rig">
        <h2>Ta bort användare</h2>
        <label for="username">Vilken användare vill du ta bort?</label>
        <input type="text" name="username" id="username">
        <input type="submit" value="Ta bort!">
        <p>Endast en admin kan göra det här!</p>
    </form>

    <form>
        <h2>Se server-loggen</h2>
        <a href="{{ config('husmusen.mount_path') }}/app/control_panel/log">Klicka här för att se loggen.</a>
        <p>Endast en admin kan göra det här!</p>
    </form>

    <form>
        <h2>Ändra databas- och museuminformation</h2>
        <a href="{{ config('husmusen.mount_path') }}/app/control_panel/edit_dbinfo">Klicka här för att ändra databas-
            och museuminformation.</a>
        <p>Endast en admin kan göra det här!</p>
    </form>
</section>
@endsection
