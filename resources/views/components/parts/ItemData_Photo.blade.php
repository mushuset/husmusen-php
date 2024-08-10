<!--
photographer: "Namn Fotografsson"
subject: "Blomma"
type: "Naturbild"
date: "2018-06-24"
-->

@include('components/item-data-input',
    ['type' => 'text', 'label' => 'Fotograf', 'name' => 'photographer', 'placeholder' => 'Namn Fotografsson'
])

@include('components/item-data-input',
    ['type' => 'text', 'label' => 'Föremål', 'name' => 'subject', 'placeholder' => 'Blomma'
])

@include('components/item-data-input',
    ['type' => 'text', 'label' => 'Typ', 'name' => 'type', 'placeholder' => 'Naturbild'
])

@include('components/item-data-input',
    ['type' => 'text', 'label' => 'Datum', 'name' => 'date', 'placeholder' => '2018-06-24'
])
