<!--
coordinates: "0°0′0″ N, 25°0′0″ W"
location: "Atlanten"
name: "Runstenen"
type: "Runsten"
-->

@include('components/item-data-input',
    ['type' => 'text', 'label' => 'Koordinater', 'name' => 'coordinates', 'placeholder' => '0°0′0″ N, 25°0′0″ W'
])

@include('components/item-data-input',
    ['type' => 'text', 'label' => 'Plats', 'name' => 'location', 'placeholder' => 'Atlanten'
])

@include('components/item-data-input',
    ['type' => 'text', 'label' => 'Namn', 'name' => 'name', 'placeholder' => 'Runstenen'
])

@include('components/item-data-input',
    ['type' => 'text', 'label' => 'Typ', 'name' => 'type', 'placeholder' => 'Runsten'
])
