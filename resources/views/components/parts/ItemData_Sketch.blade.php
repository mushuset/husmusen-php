<!--
artist: "Namn Konstnärsson"
style: "Kubism"
subject: "Stol"
year: 2013
-->

@include('components/item-data-input',
    ['type' => 'text', 'label' => 'Artist', 'name' => 'artist', 'placeholder' => 'Namn Konstnärsso'
])

@include('components/item-data-input',
    ['type' => 'text', 'label' => 'Stil', 'name' => 'style', 'placeholder' => 'Kubism'
])

@include('components/item-data-input',
    ['type' => 'text', 'label' => 'Föremål', 'name' => 'subject', 'placeholder' => 'Stol'
])

@include('components/item-data-input',
    ['type' => 'text', 'label' => 'År', 'name' => 'year', 'placeholder' => '2013'
])
