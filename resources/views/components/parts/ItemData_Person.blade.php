<!--
firstName: "Namn"
middleNames: "Mellannamn"
lastName: "Efternamnsson"
alias: "Aliaset"
occupation: "Yrke/uppdrag/ämbete/etc."
-->

@include('components/item-data-input',
    ['type' => 'text', 'label' => 'Förnamn', 'name' => 'firstName', 'placeholder' => 'Namn'
])

@include('components/item-data-input',
    ['type' => 'text', 'label' => 'Mellannamn', 'name' => 'middleNames', 'placeholder' => 'Mellannamn'
])

@include('components/item-data-input',
    ['type' => 'text', 'label' => 'Efternamn', 'name' => 'lastName', 'placeholder' => 'Efternamnsson'
])

@include('components/item-data-input',
    ['type' => 'text', 'label' => 'Alias', 'name' => 'alias', 'placeholder' => 'Aliaset'
])

@include('components/item-data-input',
    ['type' => 'text', 'label' => 'Sysselsättning', 'name' => 'occupation', 'placeholder' => 'Yrke/uppdrag/ämbete/etc.'
])
