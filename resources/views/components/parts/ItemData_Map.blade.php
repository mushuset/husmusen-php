<!--
area: "Atlanten"
chartographer: "Namn Kartritarsson"
year: 2014
scale: "1:100000"
width: 600
height: 400
-->

@include('components/item-data-input',
    ['type' => 'text', 'label' => 'Area', 'name' => 'area', 'placeholder' => 'Atlanten'
])

@include('components/item-data-input',
    ['type' => 'text', 'label' => 'Kartograf', 'name' => 'chartographer', 'placeholder' => 'Namn Kartritarsson'
])

@include('components/item-data-input',
    ['type' => 'text', 'label' => 'År', 'name' => 'year', 'placeholder' => '2014'
])

@include('components/item-data-input',
    ['type' => 'text', 'label' => 'Skala', 'name' => 'scale', 'placeholder' => '1:100000'
])

@include('components/item-data-input',
    ['type' => 'text', 'label' => 'Bredd', 'name' => 'width', 'placeholder' => '600'
])

@include('components/item-data-input',
    ['type' => 'text', 'label' => 'Höjd', 'name' => 'height', 'placeholder' => '400'
])
