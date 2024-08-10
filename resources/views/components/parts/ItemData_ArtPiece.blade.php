<!--
artist: "Namn Konstnärsson"
material: "Oljepastel"
style: "Dadism"
weight: 8203
year: 1956
-->

@include('components/item-data-input',
    ['type' => 'text', 'label' => 'Artist', 'name' => 'artist', 'placeholder' => 'Namn Konstnärsson'
])

@include('components/item-data-input',
    ['type' => 'text', 'label' => 'Material', 'name' => 'material', 'placeholder' => 'Oljepastel'
])

@include('components/item-data-input',
    ['type' => 'text', 'label' => 'Stil', 'name' => 'style', 'placeholder' => 'Dadism'
])

@include('components/item-data-input',
    ['type' => 'text', 'label' => 'Vikt', 'name' => 'weight', 'placeholder' => '8203'
])

@include('components/item-data-input',
    ['type' => 'text', 'label' => 'År', 'name' => 'year', 'placeholder' => '1956'
])
