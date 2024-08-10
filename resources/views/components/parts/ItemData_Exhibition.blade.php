<!--
coordinates: "0°0′0″ N, 25°0′0″ W"
exhibit: "Atlantiska snäckor"
location: "Atlanten"
name: "Utställning om havet Atlanten"
organiser: "Namn Utställarsson"
-->

@include('components/item-data-input',
    ['type' => 'text', 'label' => 'Koordinater', 'name' => 'coordinates', 'placeholder' => '0°0′0″ N, 25°0′0″ W'
])

@include('components/item-data-input',
    ['type' => 'text', 'label' => 'Utställning', 'name' => 'exhibit', 'placeholder' => 'Atlantiska snäckor'
])

@include('components/item-data-input',
    ['type' => 'text', 'label' => 'Plats', 'name' => 'location', 'placeholder' => 'Atlanten'
])

@include('components/item-data-input',
    ['type' => 'text', 'label' => 'Namn', 'name' => 'name', 'placeholder' => 'Utställning om havet Atlanten'
])

@include('components/item-data-input',
    ['type' => 'text', 'label' => 'Arrangör', 'name' => 'organiser', 'placeholder' => 'Namn Utställarsson'
])
