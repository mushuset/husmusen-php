<!--
goal: "Se till att Atlanten dricks upp av havsmonster."
memberCount: 14
members: "Namn Medlemsson, John Doe"
name: "Atlantens Motståndsrörelse"
-->

@include('components/item-data-input',
    ['type' => 'text', 'label' => 'Mål', 'name' => 'goal', 'placeholder' => 'Se till att Atlanten dricks upp av havsmonster.'
])

@include('components/item-data-input',
    ['type' => 'text', 'label' => 'Antal medlemmar', 'name' => 'memberCount', 'placeholder' => '14'
])

@include('components/item-data-input',
    ['type' => 'text', 'label' => 'Medlemmar', 'name' => 'members', 'placeholder' => 'Namn Medlemsson, John Doe'
])

@include('components/item-data-input',
    ['type' => 'text', 'label' => 'Namn', 'name' => 'namn', 'placeholder' => 'Atlantens Motståndsrörelse'
])
