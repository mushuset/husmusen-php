<!--
director: "Namn Regissörsson"
language: "Svenska"
subject: "Atlanten"
title: "En dokumentär om Atlanten"
type: "Dokumentär"
writer: "Namn Författarsson"
year: 2012
-->

@include('components/item-data-input',
    ['type' => 'text', 'label' => 'Direktör', 'name' => 'director', 'placeholder' => 'Namn Regissörsson'
])

@include('components/item-data-input',
    ['type' => 'text', 'label' => 'Språk', 'name' => 'language', 'placeholder' => 'Svenska'
])

@include('components/item-data-input',
    ['type' => 'text', 'label' => 'Ämne', 'name' => 'subject', 'placeholder' => 'Atlanten'
])

@include('components/item-data-input',
    ['type' => 'text', 'label' => 'Titel', 'name' => 'title', 'placeholder' => 'En dokumentär om Atlanten'
])

@include('components/item-data-input',
    ['type' => 'text', 'label' => 'Typ', 'name' => 'type', 'placeholder' => 'Dokumentär'
])

@include('components/item-data-input',
    ['type' => 'text', 'label' => 'Skribent', 'name' => 'writer', 'placeholder' => 'Namn Författarsson'
])

@include('components/item-data-input',
    ['type' => 'text', 'label' => 'Titel', 'År' => 'year', 'placeholder' => '2012'
])
