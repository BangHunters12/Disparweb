<?php

use App\Http\Controllers\Admin\AdminDashboardController;
use App\Http\Controllers\Admin\SawController;
use App\Http\Controllers\Admin\SentimenController;
use App\Http\Controllers\Admin\TempatController;
use App\Http\Controllers\Auth\AuthController;
use App\Http\Controllers\Auth\GoogleController;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\ExploreController;
use App\Http\Controllers\MapController;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| Public Routes
|--------------------------------------------------------------------------
*/
Route::view('/', 'landing')->name('home');
Route::get('/explore', [ExploreController::class, 'index'])->name('explore');
Route::get('/tempat/{id}', [ExploreController::class, 'show'])->name('tempat.show');
Route::get('/peta', [MapController::class, 'index'])->name('map');

/*
|--------------------------------------------------------------------------
| Auth Routes
|--------------------------------------------------------------------------
*/
Route::middleware('guest')->group(function () {
    Route::get('/login', [AuthController::class, 'showLogin'])->name('login');
    Route::post('/login', [AuthController::class, 'login']);
    Route::get('/register', [AuthController::class, 'showRegister'])->name('register');
    Route::post('/register', [AuthController::class, 'register']);
    Route::get('/auth/google', [GoogleController::class, 'redirect'])->name('google.redirect');
    Route::get('/auth/google/callback', [GoogleController::class, 'callback'])->name('google.callback');
});

Route::post('/logout', [AuthController::class, 'logout'])->name('logout')->middleware('auth');

/*
|--------------------------------------------------------------------------
| User Dashboard Routes
|--------------------------------------------------------------------------
*/
Route::middleware(['auth', 'web_user_disabled'])->prefix('dashboard')->name('dashboard.')->group(function () {
    Route::get('/', [DashboardController::class, 'index'])->name('index');
    Route::get('/profile', [DashboardController::class, 'profile'])->name('profile');
    Route::put('/profile', [DashboardController::class, 'updateProfile'])->name('profile.update');
    Route::get('/ulasan', [DashboardController::class, 'ulasan'])->name('ulasan');
    Route::post('/ulasan', [DashboardController::class, 'storeUlasan'])->name('ulasan.store');
    Route::put('/ulasan/{id}', [DashboardController::class, 'updateUlasan'])->name('ulasan.update');
    Route::delete('/ulasan/{id}', [DashboardController::class, 'deleteUlasan'])->name('ulasan.destroy');
    Route::get('/favorit', [DashboardController::class, 'favoritList'])->name('favorit');
    Route::post('/favorit/{tempatId}', [DashboardController::class, 'toggleFavorit'])->name('favorit.toggle');
});

/*
|--------------------------------------------------------------------------
| Admin Routes
|--------------------------------------------------------------------------
*/
Route::middleware(['auth', 'role:admin'])->prefix('admin')->name('admin.')->group(function () {
    Route::get('/', [AdminDashboardController::class, 'index'])->name('dashboard');

    // Tempat CRUD
    Route::resource('tempat', TempatController::class);
    Route::post('tempat/import-csv', [TempatController::class, 'importCsv'])->name('tempat.import-csv');
    Route::put('tempat/{tempat}/ulasan/{ulasan}', [TempatController::class, 'updateUlasan'])->name('tempat.ulasan.update');
    Route::delete('tempat/{tempat}/ulasan/{ulasan}', [TempatController::class, 'destroyUlasan'])->name('tempat.ulasan.destroy');

    // Sentimen
    Route::get('/sentimen', [SentimenController::class, 'index'])->name('sentimen.index');
    Route::post('/sentimen/{ulasanId}/reanalyze', [SentimenController::class, 'reanalyze'])->name('sentimen.reanalyze');
    Route::post('/sentimen/reanalyze-all', [SentimenController::class, 'reanalyzeAll'])->name('sentimen.reanalyze-all');

    // SAW
    Route::get('/saw', [SawController::class, 'index'])->name('saw.index');
    Route::post('/saw/recalculate', [SawController::class, 'recalculate'])->name('saw.recalculate');
});
