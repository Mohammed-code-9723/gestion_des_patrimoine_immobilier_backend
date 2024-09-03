<?php

use Illuminate\Support\Facades\Route;

Route::get('/', function () {
    return view('welcome');
});

Route::get('/{path?}', function () {
    return file_get_contents(public_path('react-build/index.html'));
})->where('path', '.*');
