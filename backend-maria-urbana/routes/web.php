<?php

use Illuminate\Support\Facades\Route;

Route::middleware('throttle:30,1')->group(function () {
    Route::get('/{any}', function () {
        return file_get_contents(public_path('index.html'));
    })->where('any', '.*');
});
