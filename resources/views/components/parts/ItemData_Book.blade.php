<!--
ISBN: "XXX-X-XX-XXXXXX-X"
author: "Namn Författarsson"
language: "Svenska"
originalLanguage: "Svenska"
originalTitle: "Titel"
pageCount: 223
publisher: "Förlaget"
title: "Titel"
translator: ""
year: 2022
-->

@include('components/item-data-input',
    ['type' => 'text', 'label' => 'ISBN', 'name' => 'ISBN', 'placeholder' => 'XXX-X-XX-XXXXXX-X'
])

@include('components/item-data-input',
    ['type' => 'text', 'label' => 'Skribent', 'name' => 'author', 'placeholder' => 'Namn Författarsson'
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
    ['type' => 'text', 'label' => 'Sidantal', 'name' => 'pageCount', 'placeholder' => '223'
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
