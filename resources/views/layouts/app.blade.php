<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="{{ config('husmusen.mount_path') }}/resources/styles/main.css">
    <link rel="stylesheet" href="{{ config('husmusen.mount_path') }}/resources/styles/fonts.css">
    @section('head')
    <title>Husmusen</title>
    @show
</head>

<body>
    <nav>
        <a href="{{ config('husmusen.mount_path') }}/app">Sök</a>
        <a href="{{ config('husmusen.mount_path') }}/app/db_info">Om museet</a>
        <a href="{{ config('husmusen.mount_path') }}/app/about">Om Husmusen</a>
        <a href="{{ config('husmusen.mount_path') }}/app/login">Kontrollpanel</a>
    </nav>
    <main>
        @section('body')
        <p>Hello World!</p>
        @show
    </main>
</body>
