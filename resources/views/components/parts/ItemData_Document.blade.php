<!--
author: "Namn Författarsson"
documentType: "Pamflett"
language: "Svenska"
originalLanguage: "Svenska"
originalTitle: "Titel"
publisher: "Förlaget"
title: "Titel"
translator: ""
year: 2022
-->

@include('components/item-data-input',
    ['type' => 'text', 'label' => 'Skribent', 'name' => 'author', 'placeholder' => 'Namn Författarsson'
])

@include('components/item-data-input',
    ['type' => 'text', 'label' => 'Dokumenttyp', 'name' => 'documentType', 'placeholder' => 'Pamflett'
])

@include('components/item-data-input',
    ['type' => 'text', 'label' => 'Språk', 'name' => 'language', 'placeholder' => 'Svenska'
])

@include('components/item-data-input',
    ['type' => 'text', 'label' => 'Originalspråk', 'name' => 'originalLanguage', 'placeholder' => 'Svenska'
])

@include('components/item-data-input',
    ['type' => 'text', 'label' => 'Originaltitel', 'name' => 'originalTitle', 'placeholder' => 'Titel'
])

@include('components/item-data-input',
    ['type' => 'text', 'label' => 'Bokförlag', 'name' => 'publisher', 'placeholder' => 'Förlaget'
])

@include('components/item-data-input',
    ['type' => 'text', 'label' => 'Översättare', 'name' => 'translator', 'placeholder' => ''
])

@include('components/item-data-input',
    ['type' => 'text', 'label' => 'År', 'name' => 'year', 'placeholder' => '2022'
])
