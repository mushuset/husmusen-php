<!--
uri: "https://example.com"
location: "Museet"
coordinates: "0°0′0″ N, 25°0′0″ W"
-->

@include('components/item-data-input',
    ['type' => 'text', 'label' => 'Länk', 'name' => 'uri', 'placeholder' => 'https://example.com'
])

@include('components/item-data-input',
    ['type' => 'text', 'label' => 'Plats', 'name' => 'location', 'placeholder' => 'Museet'
])

@include('components/item-data-input',
    ['type' => 'text', 'label' => 'Koordinater', 'name' => 'coordinates', 'placeholder' => '0°0′0″ N, 25°0′0″ W'
])
