<!--
creator: "Namn Skaparsson"
type: "Vas"
material: "Trä"
style: "Modern"
weight: 450
year: 2020
-->

@include('components/item-data-input',
    ['type' => 'text', 'label' => 'Skapare', 'name' => 'creator', 'placeholder' => 'Namn Skaparsson'
])

@include('components/item-data-input',
    ['type' => 'text', 'label' => 'Typ', 'name' => 'type', 'placeholder' => 'Vas'
])

@include('components/item-data-input',
    ['type' => 'text', 'label' => 'Material', 'name' => 'material', 'placeholder' => 'Trä'
])

@include('components/item-data-input',
    ['type' => 'text', 'label' => 'Stil', 'name' => 'style', 'placeholder' => 'Modern'
])

@include('components/item-data-input',
    ['type' => 'text', 'label' => 'Vikt', 'name' => 'weight', 'placeholder' => '450'
])

@include('components/item-data-input',
    ['type' => 'text', 'label' => 'År', 'name' => 'year', 'placeholder' => '2020'
])
