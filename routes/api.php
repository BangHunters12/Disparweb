<?php

use App\Http\Controllers\Api\ApiRestoranController;
use App\Http\Controllers\Api\ApiUlasanController;
use App\Http\Controllers\Api\ApiRekomendasiController;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| API Routes for Flutter Mobile App
|--------------------------------------------------------------------------
*/
Route::prefix('v1')->group(function () {

    // Restoran
    Route::get('/restoran',           [ApiRestoranController::class, 'index']);
    Route::get('/restoran/{slug}',    [ApiRestoranController::class, 'show']);
    Route::get('/kecamatan',          [ApiRestoranController::class, 'kecamatan']);

    // Ulasan
    Route::post('/restoran/{id}/ulasan',   [ApiUlasanController::class, 'store']);
    Route::get('/restoran/{id}/ulasan',    [ApiUlasanController::class, 'index']);

    // Rekomendasi SAW
    Route::get('/rekomendasi',             [ApiRekomendasiController::class, 'index']);
    Route::get('/rekomendasi/{slug}',      [ApiRekomendasiController::class, 'show']);

    // Favorit (device_id based, no auth)
    Route::post('/favorit/toggle',         [ApiUlasanController::class, 'toggleFavorit']);
    Route::get('/favorit/{device_id}',     [ApiUlasanController::class, 'favorit']);
});
