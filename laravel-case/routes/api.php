<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\LocationController;

route::controller(LocationController::class)->group(function () {
    route::post('/locations', 'store'); // konum ekle
    route::get('/locations', 'index'); // konumları listele
    route::get('/locations/{id}', 'show'); // konum detayı
    route::put('/locations/{id}', 'update'); // konum güncelle
    route::post('/locations/route', 'route'); // rotalama
});