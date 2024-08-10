<!--
date: "2003-03-13"
designer: "Namn Designersson"
height: 800
scale: "1:100"
width: 500
-->

@include('components/item-data-input',
    ['type' => 'text', 'label' => 'Datum', 'name' => 'date', 'placeholder' => '2003-03-13'
])

@include('components/item-data-input',
    ['type' => 'text', 'label' => 'Designer', 'name' => 'designer', 'placeholder' => 'Namn Designersson'
])

@include('components/item-data-input',
    ['type' => 'text', 'label' => 'Höjd', 'name' => 'height', 'placeholder' => '800'
])

@include('components/item-data-input',
    ['type' => 'text', 'label' => 'Skala', 'name' => 'scale', 'placeholder' => '1:100'
])

@include('components/item-data-input',
    ['type' => 'text', 'label' => 'Bredd', 'name' => 'width', 'placeholder' => '500'
])
