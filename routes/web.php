<?php

use App\Http\Controllers\Admin\AuthController as AdminAuthController;
use App\Http\Controllers\Admin\DashboardController;
use App\Http\Controllers\Admin\GoogleMapsImportController;
use App\Http\Controllers\Admin\KecamatanController;
use App\Http\Controllers\Admin\LaporanController;
use App\Http\Controllers\Admin\RestoranController;
use App\Http\Controllers\Admin\SawController;
use App\Http\Controllers\Admin\SentimenController;
use App\Http\Controllers\Admin\UlasanController;
use App\Http\Controllers\HomeController;
use App\Http\Controllers\PetaController;
use App\Http\Controllers\RestoranPublicController;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| Public Routes — No login required
|--------------------------------------------------------------------------
*/
Route::get('/', [HomeController::class, 'index'])->name('home');
Route::get('/restoran', [RestoranPublicController::class, 'index'])->name('restoran.index');
Route::get('/restoran/{slug}', [RestoranPublicController::class, 'show'])->name('restoran.show');
Route::get('/peta', [PetaController::class, 'index'])->name('peta');

/*
|--------------------------------------------------------------------------
| Admin Auth Routes
|--------------------------------------------------------------------------
*/
Route::prefix('admin')->name('admin.')->group(function () {
    // Guest (not logged in as admin)
    Route::middleware('admin.guest')->group(function () {
        Route::get('/login', [AdminAuthController::class, 'showLogin'])->name('login');
        Route::post('/login', [AdminAuthController::class, 'login'])->name('login.post');
    });

    Route::post('/logout', [AdminAuthController::class, 'logout'])->name('logout');

    /*
    |----------------------------------------------------------------------
    | Admin Protected Routes
    |----------------------------------------------------------------------
    */
    Route::middleware('admin.auth')->group(function () {
        // Dashboard
        Route::get('/', [DashboardController::class, 'index'])->name('dashboard');

        // Restoran CRUD + Import
        Route::resource('restoran', RestoranController::class);
        Route::get('/restoran-import-gmaps', [GoogleMapsImportController::class, 'index'])->name('restoran.import-gmaps');
        Route::post('/restoran-import-gmaps/search', [GoogleMapsImportController::class, 'search'])->name('restoran.import-gmaps.search');
        Route::post('/restoran-import-gmaps/import', [GoogleMapsImportController::class, 'import'])->name('restoran.import-gmaps.import');
        Route::get('/restoran-import-csv', [RestoranController::class, 'importCsvForm'])->name('restoran.import-csv');
        Route::post('/restoran-import-csv', [RestoranController::class, 'importCsv'])->name('restoran.import-csv.post');
        Route::get('/restoran-import-csv/template', [RestoranController::class, 'downloadTemplate'])->name('restoran.template');
        Route::post('/restoran/bulk-action', [RestoranController::class, 'bulkAction'])->name('restoran.bulk-action');

        // Ulasan
        Route::get('/ulasan', [UlasanController::class, 'index'])->name('ulasan.index');
        Route::get('/ulasan/create', [UlasanController::class, 'create'])->name('ulasan.create');
        Route::post('/ulasan', [UlasanController::class, 'store'])->name('ulasan.store');
        Route::get('/ulasan/{id}/edit', [UlasanController::class, 'edit'])->name('ulasan.edit');
        Route::put('/ulasan/{id}', [UlasanController::class, 'update'])->name('ulasan.update');
        Route::patch('/ulasan/{id}/toggle-visibility', [UlasanController::class, 'toggleVisibility'])->name('ulasan.toggle-visibility');
        Route::delete('/ulasan/{id}', [UlasanController::class, 'destroy'])->name('ulasan.destroy');
        Route::post('/ulasan/{id}/reanalyze', [UlasanController::class, 'reanalyze'])->name('ulasan.reanalyze');
        Route::post('/ulasan/bulk-reanalyze', [UlasanController::class, 'bulkReanalyze'])->name('ulasan.bulk-reanalyze');
        Route::get('/ulasan/import-csv', [UlasanController::class, 'showImportCsv'])->name('ulasan.import-csv');
        Route::post('/ulasan/import-csv', [UlasanController::class, 'importCsv'])->name('ulasan.import-csv-process');
        Route::get('/ulasan/csv-template', [UlasanController::class, 'csvTemplate'])->name('ulasan.csv-template');

        // Sentimen
        Route::get('/sentimen', [SentimenController::class, 'index'])->name('sentimen.index');
        Route::post('/sentimen/analyze-all', [SentimenController::class, 'analyzeAll'])->name('sentimen.analyze-all');

        // SAW
        Route::get('/saw', [SawController::class, 'index'])->name('saw.index');
        Route::post('/saw/update-weights', [SawController::class, 'updateWeights'])->name('saw.update-weights');
        Route::post('/saw/recalculate', [SawController::class, 'recalculate'])->name('saw.recalculate');
        Route::get('/saw/export-pdf', [SawController::class, 'exportPdf'])->name('saw.export-pdf');

        // Laporan
        Route::get('/laporan', [LaporanController::class, 'index'])->name('laporan.index');
        Route::get('/laporan/export-pdf', [LaporanController::class, 'exportPdf'])->name('laporan.export-pdf');
        Route::get('/laporan/export-excel', [LaporanController::class, 'exportExcel'])->name('laporan.export-excel');

        // Kecamatan
        Route::resource('kecamatan', KecamatanController::class)->except(['show']);

        // Profil Admin
        Route::get('/profil', [DashboardController::class, 'profil'])->name('profil');
        Route::put('/profil', [DashboardController::class, 'updateProfil'])->name('profil.update');
    });
});
