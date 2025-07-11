<form action="{{ env('HUSMUSEN_MOUNT_PATH', '') }}/app/search" method="get">
    @csrf
    <div class="text-inputs">
        <label for="freetext">Fritextsökning:</label>
        <input type="search" name="freetext" id="freetext" value="{{ $queries['freetext'] ?? '' }}">

        <label for="keywords">Nyckelord:</label>
        <input type="search" name="keywords" id="keywords" value="{{ $queries['keywords'] ?? '' }}">
        <p class="hint">
            Komma-separera nyckelorden utan mellanrum runt kommatecknen.
            <a href="/app/keywords">Här kan du se alla nyckelord!</a>
        </p>

        <label for="keyword-mode">Nyckelordsläge:</label>
        <select name="keyword_mode" id="keyword-mode">
            @if(isset($queries['keyword_mode']))
            <option value="OR" {{ $queries['keyword_mode'] == 'OR' ? 'selected' : '' }}>Eller-läge. (Minst ett nyckelord
                finns med i resultatet.)</option>
            <option value="AND" {{ $queries['keyword_mode'] == 'AND' ? 'selected' : '' }}>Och-läge. (Alla nyckelord
                finns med i resultatet.)</option>
            @else
            <option value="OR">Eller-läge. (Minst ett nyckelord finns med i resultatet.)</option>
            <option value="AND">Och-läge. (Alla nyckelord finns med i resultatet.)</option>
            @endif
        </select>
    </div>

    <label for="types">
        Klicka på de typer av föremål du vill söka efter:
    </label>

    <div class="select-switch-container">
        <input class="select-switch" type="checkbox" name="types" id="book" value="Book"
            {{ preg_match('/Book/', $queries['types'] ?? '') ? 'checked' : '' }}>
        <label for="book">Bok</label>
        <input class="select-switch" type="checkbox" name="types" id="building" value="Building"
            {{ preg_match('/Building/', $queries['types'] ?? '') ? 'checked' : '' }}>
        <label for="building">Byggnad</label>
        <input class="select-switch" type="checkbox" name="types" id="document" value="Document"
            {{ preg_match('/Document/', $queries['types'] ?? '') ? 'checked' : '' }}>
        <label for="document">Dokument</label>
        <input class="select-switch" type="checkbox" name="types" id="film" value="Film"
            {{ preg_match('/Film/', $queries['types'] ?? '') ? 'checked' : '' }}>
        <label for="film">Film</label>
        <input class="select-switch" type="checkbox" name="types" id="photo" value="Photo"
            {{ preg_match('/Photo/', $queries['types'] ?? '') ? 'checked' : '' }}>
        <label for="photo">Foto</label>
        <input class="select-switch" type="checkbox" name="types" id="physicalitem" value="PhysicalItem"
            {{ preg_match('/PhysicalItem/', $queries['types'] ?? '') ? 'checked' : '' }}>
        <label for="physicalitem">Fysiskt ting</label>
        <input class="select-switch" type="checkbox" name="types" id="group" value="Group"
            {{ preg_match('/Group/', $queries['types'] ?? '') ? 'checked' : '' }}>
        <label for="group">Grupp</label>
        <input class="select-switch" type="checkbox" name="types" id="historicalevent" value="HistoricalEvent"
            {{ preg_match('/HistoricalEvent/', $queries['types'] ?? '') ? 'checked' : '' }}>
        <label for="historicalevent">Historisk händelse</label>
        <input class="select-switch" type="checkbox" name="types" id="interactiveresource" value="InteractiveResource"
            {{ preg_match('/InteractiveResource/', $queries['types'] ?? '') ? 'checked' : '' }}>
        <label for="interactiveresource">Interaktiv resurs</label>
        <input class="select-switch" type="checkbox" name="types" id="map" value="Map"
            {{ preg_match('/Map/', $queries['types'] ?? '') ? 'checked' : '' }}>
        <label for="map">Karta</label>
        <input class="select-switch" type="checkbox" name="types" id="concept" value="Concept"
            {{ preg_match('/Concept/', $queries['types'] ?? '') ? 'checked' : '' }}>
        <label for="concept">Koncept</label>
        <input class="select-switch" type="checkbox" name="types" id="artpiece" value="ArtPiece"
            {{ preg_match('/ArtPiece/', $queries['types'] ?? '') ? 'checked' : '' }}>
        <label for="artpiece">Konstverk</label>
        <input class="select-switch" type="checkbox" name="types" id="culturalenvironment" value="CulturalEnvironment"
            {{ preg_match('/CulturalEnvironment/', $queries['types'] ?? '') ? 'checked' : '' }}>
        <label for="culturalenvironment">Kulturell miljö</label>
        <input class="select-switch" type="checkbox" name="types" id="culturalheritage" value="CulturalHeritage"
            {{ preg_match('/CulturalHeritage/', $queries['types'] ?? '') ? 'checked' : '' }}>
        <label for="culturalheritage">Kulturminne</label>
        <input class="select-switch" type="checkbox" name="types" id="sound" value="Sound"
            {{ preg_match('/Sound/', $queries['types'] ?? '') ? 'checked' : '' }}>
        <label for="sound">Ljud</label>
        <input class="select-switch" type="checkbox" name="types" id="organisation" value="Organisation"
            {{ preg_match('/Organisation/', $queries['types'] ?? '') ? 'checked' : '' }}>
        <label for="organisation">Organisation</label>
        <input class="select-switch" type="checkbox" name="types" id="person" value="Person"
            {{ preg_match('/Person/', $queries['types'] ?? '') ? 'checked' : '' }}>
        <label for="person">Person</label>
        <input class="select-switch" type="checkbox" name="types" id="blueprint" value="Blueprint"
            {{ preg_match('/Blueprint/', $queries['types'] ?? '') ? 'checked' : '' }}>
        <label for="blueprint">Ritning</label>
        <input class="select-switch" type="checkbox" name="types" id="collection" value="Collection"
            {{ preg_match('/Collection/', $queries['types'] ?? '') ? 'checked' : '' }}>
        <label for="collection">Samling</label>
        <input class="select-switch" type="checkbox" name="types" id="sketch" value="Sketch"
            {{ preg_match('/Sketch/', $queries['types'] ?? '') ? 'checked' : '' }}>
        <label for="sketch">Skiss</label>
        <input class="select-switch" type="checkbox" name="types" id="exhibition" value="Exhibition"
            {{ preg_match('/Exhibition/', $queries['types'] ?? '') ? 'checked' : '' }}>
        <label for="exhibition">Utställning</label>
    </div>
    <p class="hint">Om du vill söka efter allt, så behöver du inte markera något.</p>

    <p>Vad vill du sortera resultaten utefter?</p>
    <div class="select-switch-container">
        @if(isset($queries['sort']))
        <input class="select-switch" type="radio" name="sort" id="relevance" value="relevance"
            {{ $queries['sort'] === "relevance" ? 'checked' : '' }}>
        <label for="relevance">Relevans</label>

        <input class="select-switch" type="radio" name="sort" id="name" value="name"
            {{ $queries['sort'] === "name" ? 'checked' : '' }}>
        <label for="name">Namn</label>

        <input class="select-switch" type="radio" name="sort" id="added-at" value="addedAt"
            {{ $queries['sort'] === "addedAt" ? 'checked' : '' }}>
        <label for="added-at">Senast tillagd</label>

        <input class="select-switch" type="radio" name="sort" id="updated-at" value="updatedAt"
            {{ $queries['sort'] === "updatedAt" ? 'checked' : '' }}>
        <label for="updated-at">Senast uppdaterad</label>

        <input class="select-switch" type="radio" name="sort" id="item-id" value="itemID"
            {{ $queries['sort'] === "itemID" ? 'checked' : '' }}>
        <label for="item-id">Inventarienummer</label>
        @else
        <input class="select-switch" type="radio" name="sort" id="relevance" value="relevance">
        <label for="relevance">Relevans</label>

        <input class="select-switch" type="radio" name="sort" id="name" value="name">
        <label for="name">Namn</label>

        <input class="select-switch" type="radio" name="sort" id="added-at" value="addedAt">
        <label for="added-at">Senast tillagd</label>

        <input class="select-switch" type="radio" name="sort" id="updated-at" value="updatedAt">
        <label for="updated-at">Senast uppdaterad</label>

        <input class="select-switch" type="radio" name="sort" id="item-id" value="itemID">
        <label for="item-id">Inventarienummer</label>
        @endif
    </div>

    <div>
        <input type="checkbox" name="reverse" id="reverse"
            {{ preg_match('/(1|on|true)/i', $queries['reverse'] ?? '') ?'checked' : '' }}>
        <label for="reverse">Jag vill sortera resultaten i omvänd ordning.</label>
    </div>

    <input type="submit" value="Sök!">
</form>
