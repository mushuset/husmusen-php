@extends('layouts.app')

@section('body')
<header>
    <img src="{{ env('HUSMUSEN_MOUNT_PATH', '') }}/resources/logo.svg" alt="">
    <h1>Vad är Husmusen?</h1>
</header>
<p>
    Husmusen är ett arkiverings-/inventariesystem för museum (och liknande instutitioner)
    som vill publicera de föremål/objekt/saker/allt möjligt som de har i sitt inventarie.
</p>

<p>
    Det är utvecklat för att vara så lätt som möjligt att implementera, men samtidigt fullständingt
    nog för att fungera bra. Husmusen har också öppen källkod som du kan hitta på
    <a href="https://github.com/mushuset/husmusen-php">GitHub (PHP-versionen; denna)</a>, eller
    <a href="https://github.com/mushuset/husmusen">GitHub (Nodeversionen; syskonversion)</a>.
</p>
@endsection
