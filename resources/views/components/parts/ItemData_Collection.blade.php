<!--  
collectible: "Muggar och koppar"
collector: "Namn Samlarsson"
size: 15
-->

@include('components/item-data-input',
    ['type' => 'text', 'label' => 'Samlarobjekt', 'name' => 'collectible', 'placeholder' => 'Muggar och koppar'
])

@include('components/item-data-input',
    ['type' => 'text', 'label' => 'Samlare', 'name' => 'collector', 'placeholder' => 'Namn Samlarsson'
])

@include('components/item-data-input',
    ['type' => 'text', 'label' => 'Storlek', 'name' => 'size', 'placeholder' => '15'
])
