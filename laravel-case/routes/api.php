<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\LocationController;

Route::controller(LocationController::class)
    ->middleware('throttle:55,1')
    ->group(function () {
        Route::post('/locations', 'store');        // konum ekle
        Route::get('/locations', 'index');         // konumları listele
        Route::get('/locations/{id}', 'show');     // konum detayı
        Route::put('/locations/{id}', 'update');   // konum güncelle
        Route::post('/locations/route', 'route');  // rotalama
    });

    