<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="{{ env('HUSMUSEN_MOUNT_PATH', '') }}/resources/styles/main.css">
    <link rel="stylesheet" href="{{ env('HUSMUSEN_MOUNT_PATH', '') }}/resources/styles/fonts.css">
    @section('head')
    <title>Husmusen</title>
    @show
</head>

<body>
    <nav>
        <a href="{{ env('HUSMUSEN_MOUNT_PATH', '') }}/app">Sök</a>
        <a href="{{ env('HUSMUSEN_MOUNT_PATH', '') }}/app/db_info">Om museet</a>
        <a href="{{ env('HUSMUSEN_MOUNT_PATH', '') }}/app/about">Om Husmusen</a>
        <a href="{{ env('HUSMUSEN_MOUNT_PATH', '') }}/app/login">Kontrollpanel</a>
    </nav>
    <main>
        @section('body')
        <p>Hello World!</p>
        @show
    </main>
</body>
