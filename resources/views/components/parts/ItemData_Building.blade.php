<!--
architect: "Namn Arkitektsson"
coordinates: "0°0′0″ N, 25°0′0″ W"
location: "Atlanten"
material: "Cement"
name: "Namn"
purpose: "Fyr"
year: 1867
-->

@include('components/item-data-input',
    ['type' => 'text', 'label' => 'Arkitekt', 'name' => 'architect', 'placeholder' => 'Namn Arkitektsson'
])

@include('components/item-data-input',
    ['type' => 'text', 'label' => 'Koordinater', 'name' => 'coordinates', 'placeholder' => '0°0′0″ N, 25°0′0″ W'
])

@include('components/item-data-input',
    ['type' => 'text', 'label' => 'Plats', 'name' => 'location', 'placeholder' => 'Atlanten'
])

@include('components/item-data-input',
    ['type' => 'text', 'label' => 'Material', 'name' => 'material', 'placeholder' => 'Cement'
])

@include('components/item-data-input',
    ['type' => 'text', 'label' => 'Namn', 'name' => 'name', 'placeholder' => 'Namn'
])

@include('components/item-data-input',
    ['type' => 'text', 'label' => 'Syfte', 'name' => 'purpose', 'placeholder' => 'Fyr'
])

@include('components/item-data-input',
    ['type' => 'text', 'label' => 'År', 'name' => 'year', 'placeholder' => '1867'
])
