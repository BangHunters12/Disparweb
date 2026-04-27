<?php

use App\Http\Controllers\Api\ApiAuthController;
use App\Http\Controllers\Api\ApiTempatController;
use App\Http\Controllers\Api\ApiUlasanController;
use App\Http\Controllers\Api\ApiMiscController;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| Public API Routes
|--------------------------------------------------------------------------
*/
Route::prefix('auth')->group(function () {
    Route::post('/login', [ApiAuthController::class, 'login']);
    Route::post('/register', [ApiAuthController::class, 'register']);
});

Route::get('/tempat', [ApiTempatController::class, 'index']);
Route::get('/tempat/{id}', [ApiTempatController::class, 'show']);
Route::get('/rekomendasi', [ApiTempatController::class, 'rekomendasi']);
Route::get('/kecamatan', [ApiTempatController::class, 'kecamatan']);
Route::get('/kategori', [ApiTempatController::class, 'kategori']);

/*
|--------------------------------------------------------------------------
| Authenticated API Routes
|--------------------------------------------------------------------------
*/
Route::middleware('auth:sanctum')->group(function () {
    Route::post('/auth/logout', [ApiAuthController::class, 'logout']);

    // Ulasan
    Route::post('/ulasan', [ApiUlasanController::class, 'store']);
    Route::put('/ulasan/{id}', [ApiUlasanController::class, 'update']);
    Route::delete('/ulasan/{id}', [ApiUlasanController::class, 'destroy']);

    // Favorit
    Route::get('/favorit', [ApiMiscController::class, 'favoritIndex']);
    Route::post('/favorit/{tempatId}', [ApiMiscController::class, 'favoritStore']);
    Route::delete('/favorit/{tempatId}', [ApiMiscController::class, 'favoritDestroy']);

    // Profile
    Route::get('/user/profile', [ApiMiscController::class, 'profile']);
    Route::put('/user/profile', [ApiMiscController::class, 'updateProfile']);

    /*
    |--------------------------------------------------------------------------
    | Admin API Routes
    |--------------------------------------------------------------------------
    */
    Route::middleware('role:admin')->prefix('admin')->group(function () {
        Route::get('/sentimen/summary', [ApiMiscController::class, 'sentimenSummary']);
        Route::get('/sentimen/keywords', [ApiMiscController::class, 'sentimenKeywords']);
        Route::post('/saw/recalculate', [ApiMiscController::class, 'sawRecalculate']);
        Route::post('/tempat/import-csv', [ApiMiscController::class, 'tempatImportCsv']);
    });
});
