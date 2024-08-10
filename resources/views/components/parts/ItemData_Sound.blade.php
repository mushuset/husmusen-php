<!--
type: "Radioprogram"
voices: "Namn Röstsson"
instruments: "Cello, Tuba, Munspel"
duration: 2315
-->

@include('components/item-data-input',
    ['type' => 'text', 'label' => 'Typ', 'name' => 'type', 'placeholder' => 'Radioprogram'
])

@include('components/item-data-input',
    ['type' => 'text', 'label' => 'Röster', 'name' => 'voices', 'placeholder' => 'Namn Röstsson'
])

@include('components/item-data-input',
    ['type' => 'text', 'label' => 'Instrument', 'name' => 'instruments', 'placeholder' => 'Cello, Tuba, Munspel'
])

@include('components/item-data-input',
    ['type' => 'text', 'label' => 'Längd', 'name' => 'duration', 'placeholder' => '2315'
])
