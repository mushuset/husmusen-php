<!--
date: "2012-07-27"
name: "Det stora slaget"
type: "Slagsmål"
-->

@include('components/item-data-input',
    ['type' => 'text', 'label' => 'Datum', 'name' => 'date', 'placeholder' => '2012-07-27'
])

@include('components/item-data-input',
    ['type' => 'text', 'label' => 'Namn', 'name' => 'name', 'placeholder' => 'Det stora slaget'
])

@include('components/item-data-input',
    ['type' => 'text', 'label' => 'Typ', 'name' => 'type', 'placeholder' => 'Slagsmål'
])
