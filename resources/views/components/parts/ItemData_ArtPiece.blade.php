<!--
artist: "Namn Konstnärsson"
material: "Oljepastel"
style: "Dadism"
weight: 8203
year: 1956
-->

@include('components/item-data-input', 
    ['type' => 'text', 'label' => 'Namn på artist', 'name' => 'artist',
    'placeholder' => 'Bengt Svensson', 'hint' => 'Vad är det för namn på artisten?', 'value' => ''
])
