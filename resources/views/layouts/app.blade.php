<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="/resources/styles/main.css">
    <link rel="stylesheet" href="/resources/static/fonts.css">
    @section('head')
    <title>Husmusen</title>
    @show
</head>
<body>
    <nav>
        <a href="/app">Sök</a>
        <a href="/app/db_info">Om museet</a>
        <a href="/app/about">Om Husmusen</a>
        <a href="/app/login">Kontrollpanel</a>
    </nav>
    <main>
        @section('body')
        <p>Hello World!</p>
        @show
    </main>
</body>
