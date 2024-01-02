name: "Namn"
description: "Beskrivande beskrvning"
keywords: "Nyckelord1,Nyckelord2,Nyckelord3,Etc."
type: "{{ request()->query('type') }}"
# Det här är bara en förhandsvisning av det inventarienummer
# som föremålet kommer få; det kan inte ändras.
itemID: {{ $next_item_id }}