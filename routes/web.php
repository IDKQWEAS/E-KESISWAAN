<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\AbsensiController;

// Login & Absensi
Route::get('/', [DashboardController::class, 'index'])->name('login');
Route::post('/login', [DashboardController::class, 'login'])->name('login.post');
Route::get('/absensi', [AbsensiController::class, 'index']);

// === ROUTE ADMIN ===
Route::prefix('admin')->group(function () {
    Route::get('/dashboard', [DashboardController::class, 'adminDashboard']);
    Route::get('/kehadiran', [DashboardController::class, 'adminKehadiran']);
    Route::get('/siswa',     [DashboardController::class, 'adminSiswa']);
    Route::get('/prestasi',  [DashboardController::class, 'adminPrestasi']);
    Route::get('/pelanggaran',[DashboardController::class, 'adminPelanggaran']);
    Route::get('/laporan',   [DashboardController::class, 'adminLaporan']);
    Route::get('/users',     [DashboardController::class, 'adminUsers']);
    Route::get('/pengaturan',[DashboardController::class, 'adminPengaturan']);
});

// === ROUTE BK ===
Route::prefix('bk')->group(function () {
    Route::get('/dashboard', [DashboardController::class, 'bkDashboard']);
    Route::get('/input-pelanggaran', [DashboardController::class, 'bkPelanggaran']);
    Route::get('/pemantauan', [DashboardController::class, 'bkPemantauan']);
    Route::get('/biodata', [DashboardController::class, 'bkBiodata']);
    Route::get('/home-visit', [DashboardController::class, 'bkHomeVisit']);
    Route::get('/perizinan', [DashboardController::class, 'bkPerizinan']);
    Route::get('/rekap-kehadiran', [DashboardController::class, 'bkRekap']);
});

// === ROUTE KEPSEK ===
Route::prefix('kepsek')->group(function () {
    Route::get('/dashboard', [DashboardController::class, 'kepsekDashboard']);
    Route::get('/disiplin', [DashboardController::class, 'kepsekDisiplin']);
    Route::get('/prestasi', [DashboardController::class, 'kepsekPrestasi']);
    Route::get('/absensi', [DashboardController::class, 'kepsekAbsensi']);
    Route::get('/visit', [DashboardController::class, 'kepsekVisit']);
});
Route::get('/absensi', [DashboardController::class, 'halamanAbsensi']);