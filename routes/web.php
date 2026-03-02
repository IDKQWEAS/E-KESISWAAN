<?php

use App\Http\Controllers\Auth\AuthController;
use App\Http\Controllers\Kepsek\DashboardController as KepsekDashboard;
use App\Http\Controllers\Kepsek\EditProfilController;
use App\Http\Controllers\Kepsek\MonitoringAbsensiController;
use App\Http\Controllers\Kepsek\MonitoringHomeVisitController;
use App\Http\Controllers\Kepsek\MonitoringPrestasiController;
use App\Http\Controllers\Kepsek\PetaDisiplinController;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
*/

// === AUTHENTICATION ===
Route::get('/', [AuthController::class, 'index'])->name('login');
Route::post('/login', [AuthController::class, 'login'])->name('login.post');
Route::post('/logout', [AuthController::class, 'logout'])->name('logout');

// === KEPALA SEKOLAH ROUTES ===
Route::prefix('kepsek')->group(function () {
    // Memanggil DashboardController di folder Kepsek
    Route::get('/dashboard', [KepsekDashboard::class, 'index'])->name('kepsek.dashboard');

    // Memanggil PetaDisiplinController di folder Kepsek
    Route::get('/disiplin', [PetaDisiplinController::class, 'index'])->name('kepsek.disiplin');

    // Memanggil MonitoringPrestasiController di folder Kepsek
    Route::get('/prestasi', [MonitoringPrestasiController::class, 'index'])->name('kepsek.prestasi');

    // Memanggil MonitoringAbsensiController di folder Kepsek
    Route::get('/absensi', [MonitoringAbsensiController::class, 'index'])->name('kepsek.absensi');

    // Memanggil MonitoringHomeVisitController di folder Kepsek
    Route::get('/visit', [MonitoringHomeVisitController::class, 'index'])->name('kepsek.visit');
    Route::get('/visit/{id}/detail', [MonitoringHomeVisitController::class, 'detail'])->name('kepsek.visit.detail');

    // Memanggil EditProfilController di folder Kepsek
    Route::get('/profile', [EditProfilController::class, 'index'])->name('kepsek.profile');
    Route::post('/profile', [EditProfilController::class, 'update'])->name('kepsek.profile.update');

});
