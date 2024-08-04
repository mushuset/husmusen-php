<?php

use Illuminate\Support\Facades\Route;

if (env('APP_DEBUG', false)) {
    Route::get('/setup', function () { return view('setup'); });
}
