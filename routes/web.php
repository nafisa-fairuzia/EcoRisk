<?php

use App\Http\Controllers\AdminController;
use App\Http\Controllers\Auth\LoginController;
use App\Http\Controllers\Auth\RegisterController;
use App\Http\Controllers\OfficerPickupController;
use App\Http\Controllers\PetugasController;
use App\Http\Controllers\PublicDashboardController;
use App\Http\Controllers\WargaController;
use App\Http\Controllers\WasteReportController;
use App\Http\Controllers\WasteSortingController;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| Web Routes - SiklusSampah
|--------------------------------------------------------------------------
*/

// Halaman Depan / Landing Page Publik
Route::get('/', function () {
    return view('landing');
})->name('home');
Route::get('/dampak', [PublicDashboardController::class, 'index'])->name('public.dashboard');

// Autentikasi Pengguna
Route::get('/login', [LoginController::class, 'showLoginForm'])->name('login');
Route::post('/login', [LoginController::class, 'login']);
Route::post('/logout', [LoginController::class, 'logout'])->name('logout');

// Registrasi Khusus Warga
Route::get('/register', [RegisterController::class, 'showRegistrationForm'])->name('register');
Route::post('/register', [RegisterController::class, 'register']);

// Grup Terproteksi Autentikasi
Route::middleware(['auth'])->group(function () {
    Route::get('/reports', [WasteReportController::class, 'index'])
        ->middleware('role:admin,petugas')
        ->name('reports.index');

    Route::post('/reports', [WasteReportController::class, 'store'])
        ->middleware('role:warga')
        ->name('reports.store');

    // 1. AREA ADMINISTRATOR
    Route::middleware(['role:admin'])->prefix('admin')->name('admin.')->group(function () {
        Route::get('/dashboard', [AdminController::class, 'index'])->name('dashboard');
        Route::get('/heatmap', [WasteReportController::class, 'index'])->name('heatmap');
        Route::get('/impact', [AdminController::class, 'impact'])->name('impact');
        Route::get('/users', [AdminController::class, 'users'])->name('users.index');
        Route::patch('/users/{id}/toggle-status', [AdminController::class, 'toggleUserStatus'])->name('users.toggle');
    });

    // 2. AREA PETUGAS LAPANGAN
    Route::middleware(['role:petugas'])->prefix('petugas')->name('petugas.')->group(function () {
        Route::get('/dashboard', [PetugasController::class, 'index'])->name('dashboard');
        Route::get('/pickups', [OfficerPickupController::class, 'index'])->name('pickups.index');
        Route::get('/routes', [OfficerPickupController::class, 'routes'])->name('routes.index');
        Route::patch('/pickups/{id}/status', [OfficerPickupController::class, 'updateStatus'])
            ->name('pickups.update-status');
        Route::get('/sortings', [WasteSortingController::class, 'index'])->name('sortings.index');
        Route::get('/reports/{report_id}/sortings/create', [WasteSortingController::class, 'create'])
            ->name('sortings.create');
        Route::post('/reports/{report_id}/sortings', [WasteSortingController::class, 'store'])
            ->name('sortings.store');
        Route::patch('/laporan/{id}/status', [PetugasController::class, 'updateReportStatus'])->name('laporan.update-status');
        Route::post('/pemilahan', [PetugasController::class, 'storeSorting'])->name('pemilahan.store');
        Route::post('/penyaluran', [PetugasController::class, 'storeDistribution'])->name('penyaluran.store');
    });

    // 3. AREA WARGA MASYARAKAT (akun hasil registrasi warga)
    Route::middleware(['role:warga'])->prefix('warga')->name('warga.')->group(function () {
        Route::get('/dashboard', [WargaController::class, 'index'])->name('dashboard');
        Route::post('/laporan', [WargaController::class, 'storeReport'])->name('laporan.store');
    });
});
