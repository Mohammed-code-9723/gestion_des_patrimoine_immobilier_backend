<?php

use Illuminate\Support\Facades\Route;

Route::get('/', function () {
    return view('welcome');
});

Route::get('/{path?}', function () {
    $indexPath = public_path('react-build/index.html');

    if (file_exists($indexPath)) {
        return response()->file($indexPath, [
            'Content-Type' => 'text/html',
        ]);
    } else {
        abort(404, 'File not found');
    }
})->where('path', '.*');