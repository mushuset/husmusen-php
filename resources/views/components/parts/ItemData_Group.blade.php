<!--
goal: "Rädda Atlanten från havsmonster."
memberCount: 23
members: "Namn Medlemsson, John Doe"
name: "Atlantens Försvarsallians"
-->

@include('components/item-data-input',
    ['type' => 'text', 'label' => 'Mål', 'name' => 'goal', 'placeholder' => 'Rädda Atlanten från havsmonster.'
])

@include('components/item-data-input',
    ['type' => 'text', 'label' => 'Antal medlemmar', 'name' => 'memberCount', 'placeholder' => '23'
])

@include('components/item-data-input',
    ['type' => 'text', 'label' => 'Medlemmar', 'name' => 'members', 'placeholder' => 'Namn Medlemsson, John Doe'
])

@include('components/item-data-input',
    ['type' => 'text', 'label' => 'Namn', 'name' => 'namn', 'placeholder' => 'Atlantens Försvarsallian'
])
