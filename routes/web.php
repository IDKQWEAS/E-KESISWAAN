<?php

use App\Http\Controllers\Absensi\AbsensiController;
use App\Http\Controllers\Admin\DashboardController as AdminDash;

// --- Controllers: Dashboard ---
use App\Http\Controllers\Admin\data_kehadiranController;
use App\Http\Controllers\Admin\data_pelanggaranController;
use App\Http\Controllers\Admin\data_siswaController;

// --- Controllers: Admin ---
use App\Http\Controllers\admin\PengaturanController;
use App\Http\Controllers\Admin\UserController;
use App\Http\Controllers\Auth\AuthController;
use App\Http\Controllers\GuruBK\BiodataController as GuruBKBiodataController;
use App\Http\Controllers\GuruBK\DashboardController as GuruBKDashboard;
use App\Http\Controllers\GuruBK\EditProfilController as GuruBKEditProfilController;

// --- Controllers: Guru BK ---
use App\Http\Controllers\GuruBK\HomeVisitController as GuruBKHomeVisitController;
use App\Http\Controllers\GuruBK\InputPelanggaranController;
use App\Http\Controllers\GuruBK\PemantauanController;
use App\Http\Controllers\GuruBK\PerizinanController as GuruBKPerizinanController;
use App\Http\Controllers\GuruBK\RekapKehadiranController as GuruBKRekapKehadiranController;
use App\Http\Controllers\Kepsek\DashboardController as KepsekDash;
use App\Http\Controllers\Kepsek\EditProfilController;
use App\Http\Controllers\GuruBK\data_prestasiController;
use App\Http\Controllers\GuruBK\LaporanController;

// --- Controllers: Kepsek ---
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

// Default Redirect
Route::get('/', function () {
    return redirect()->route('login');
});

// === AUTHENTICATION ===
Route::get('/login', [AuthController::class, 'index'])->name('login');
Route::post('/login', [AuthController::class, 'login'])->name('login.post');
Route::post('/logout', [AuthController::class, 'logout'])->name('logout'); // Dikembalikan ke POST demi keamanan

// === PROTECTED ROUTES (Wajib Login) ===
Route::middleware(['web'])->group(function () {

    // --- ADMIN ROUTES ---
    Route::prefix('admin')->group(function () {
    Route::get('/dashboard', [AdminDash::class, 'index'])->name('admin.dashboard');
    
    // PERBAIKAN DI SINI: Gunakan AdminDash dan pastikan URI-nya benar
    Route::post('/absensi/tandai-alpha', [AbsensiController::class, 'tandaiAlpha'])->name('absensi.tandaiAlpha');
        // Modul Kehadiran & Siswa
        Route::get('/data-kehadiran', [data_kehadiranController::class, 'index'])->name('admin.kehadiran');
        Route::get('/data-siswa', [data_siswaController::class, 'index'])->name('admin.siswa');

        
        // Modul Pelanggaran
        Route::get('/data-pelanggaran', [data_pelanggaranController::class, 'index'])->name('admin.pelanggaran');

    
        // Manajemen User
        Route::get('/users', [UserController::class, 'index'])->name('admin.users');
        Route::post('/api-simpan-user', [UserController::class, 'store']);
        Route::post('/api-update-user/{id}', [UserController::class, 'update']); // Gunakan POST atau PUT sesuai kebutuhan
        Route::delete('/api-hapus-user/{id}', [UserController::class, 'destroy']);

        // Pengaturan Tahun Pelajaran
        Route::put('/tahun-pelajaran/activate/{id}', [AdminDash::class, 'activateYear'])->name('admin.tahun.activate');
        Route::post('/tahun-pelajaran', [AdminDash::class, 'saveNewYear'])->name('admin.tahun.store');
        Route::get('/tahun_ajaran', [AdminDash::class, 'listTahunAjaran'])->name('admin.tahun_ajaran.list');
        Route::post('/tahun_ajaran', [AdminDash::class, 'storeTahunAjaran'])->name('admin.tahun_ajaran.store');
        Route::patch('/tahun_ajaran/aktifkan/{id}', [AdminDash::class, 'activateTahunAjaran'])->name('admin.tahun_ajaran.activate');

        // Pengaturan Umum
        Route::get('/pengaturan', [PengaturanController::class, 'index'])->name('admin.pengaturan');
        Route::post('/pengaturan', [PengaturanController::class, 'update'])->name('admin.pengaturan.update');
    });

    // --- GURU BK ROUTES ---
    Route::prefix('bk')->group(function () {
        Route::get('/dashboard', [GuruBKDashboard::class, 'index'])->name('bk.dashboard');

        // Profil
        Route::get('/profile', [GuruBKEditProfilController::class, 'index'])->name('bk.profile');
        Route::post('/profile', [GuruBKEditProfilController::class, 'update'])->name('bk.profile.update');

        // Pelanggaran
        Route::get('/input-pelanggaran', [InputPelanggaranController::class, 'index'])->name('bk.pelanggaran');
        Route::post('/input-pelanggaran', [InputPelanggaranController::class, 'store'])->name('bk.pelanggaran.store');
        Route::post('/input-pelanggaran/{id}/update', [InputPelanggaranController::class, 'update'])->name('bk.pelanggaran.update');
        Route::post('/input-pelanggaran/{id}/delete', [InputPelanggaranController::class, 'destroy'])->name('bk.pelanggaran.destroy');

        Route::post('/jenis-pelanggaran', [InputPelanggaranController::class, 'storeJenis'])->name('bk.jenis.store');
        Route::post('/jenis-pelanggaran/{id}/update', [InputPelanggaranController::class, 'updateJenis'])->name('bk.jenis.update');
        Route::post('/jenis-pelanggaran/{id}/delete', [InputPelanggaranController::class, 'destroyJenis'])->name('bk.jenis.destroy');

        // Pemantauan
        Route::get('/pemantauan', [PemantauanController::class, 'index'])->name('bk.pemantauan');
        Route::get('/pemantauan/{id}/detail', [PemantauanController::class, 'detail'])->name('bk.pemantauan.detail');

        // Home Visit
        Route::get('/home-visit/{id}/detail', [GuruBKHomeVisitController::class, 'detail'])->name('bk.visit.detail');
        Route::get('/api/siswa-by-kelas', [GuruBKHomeVisitController::class, 'getSiswaByKelas'])->name('bk.visit.getSiswa');
        Route::get('/home-visit', [GuruBKHomeVisitController::class, 'index'])->name('bk.visit');
        Route::post('/home-visit', [GuruBKHomeVisitController::class, 'store'])->name('bk.visit.store');
        Route::patch('/home-visit/{id}', [GuruBKHomeVisitController::class, 'update'])->name('bk.visit.update');
        Route::delete('/home-visit/{id}', [GuruBKHomeVisitController::class, 'destroy'])->name('bk.visit.destroy');

        // Rekap Kehadiran
        Route::get('/rekap-kehadiran', [GuruBKRekapKehadiranController::class, 'index'])->name('bk.rekap_kehadiran');
        Route::get('/rekap-kehadiran/pdf', [GuruBKRekapKehadiranController::class, 'downloadPdf'])->name('bk.rekap_kehadiran.pdf');
        Route::get('/rekap-kehadiran/excel', [GuruBKRekapKehadiranController::class, 'downloadExcel'])->name('bk.rekap_kehadiran.excel');
        Route::post('/rekap-kehadiran/sync-alpha', [GuruBKRekapKehadiranController::class, 'syncAlpha'])->name('bk.absensi.sync_alpha');

        // Perizinan
        Route::get('/perizinan', [GuruBKPerizinanController::class, 'index'])->name('bk.perizinan');
        Route::post('/perizinan', [GuruBKPerizinanController::class, 'store'])->name('bk.perizinan.store');
        Route::post('/perizinan/{id}/update', [GuruBKPerizinanController::class, 'update'])->name('bk.perizinan.update');
        Route::post('/perizinan/{id}/delete', [GuruBKPerizinanController::class, 'destroy'])->name('bk.perizinan.destroy');

        // Biodata Siswa
        Route::get('/biodata', [GuruBKBiodataController::class, 'index'])->name('bk.biodata');
        Route::post('/biodata', [GuruBKBiodataController::class, 'store'])->name('bk.biodata.store');
        Route::post('/biodata/{id}/update', [GuruBKBiodataController::class, 'update'])->name('bk.biodata.update');
        Route::post('/biodata/{id}/delete', [GuruBKBiodataController::class, 'destroy'])->name('bk.biodata.destroy');
        Route::get('/biodata/template', [GuruBKBiodataController::class, 'downloadTemplate'])->name('bk.biodata.template');
        Route::get('/biodata/excel', [GuruBKBiodataController::class, 'downloadExcel'])->name('bk.biodata.excel');
        Route::post('/biodata/import', [GuruBKBiodataController::class, 'importExcel'])->name('bk.biodata.import');

        // --- PINDAHAN DARI ADMIN: DATA PRESTASI ---
    Route::get('/data-prestasi', [data_prestasiController::class, 'index'])->name('bk.prestasi');
    Route::get('/api-siswa-by-kelas', [data_prestasiController::class, 'getSiswaByKelas']);
    Route::post('/api-simpan-prestasi', [data_prestasiController::class, 'store']);
    Route::post('/api-update-prestasi/{id}', [data_prestasiController::class, 'update']);
    Route::delete('/api-hapus-prestasi/{id}', [data_prestasiController::class, 'destroy']);

    // --- PINDAHAN DARI ADMIN: CETAK LAPORAN ---
    Route::get('/laporan', [LaporanController::class, 'index'])->name('bk.laporan');
    Route::get('/laporan/download/{type}/{format}', [LaporanController::class, 'download'])->name('bk.laporan.download');
    });

    // --- KEPSEK ROUTES ---
    Route::prefix('kepsek')->group(function () {
        Route::get('/dashboard', [KepsekDash::class, 'index'])->name('kepsek.dashboard');
        Route::get('/disiplin', [PetaDisiplinController::class, 'index'])->name('kepsek.disiplin');
        Route::get('/prestasi', [MonitoringPrestasiController::class, 'index'])->name('kepsek.prestasi');
        Route::get('/absensi', [MonitoringAbsensiController::class, 'index'])->name('kepsek.absensi');
        Route::get('/visit', [MonitoringHomeVisitController::class, 'index'])->name('kepsek.visit');
        Route::get('/visit/{id}/detail', [MonitoringHomeVisitController::class, 'detail'])->name('kepsek.visit.detail');
        Route::get('/profile', [EditProfilController::class, 'index'])->name('kepsek.profile');
        Route::post('/profile', [EditProfilController::class, 'update'])->name('kepsek.profile.update');
    });

    // --- PETUGAS ABSENSI ROUTES ---
// --- PETUGAS ABSENSI ROUTES ---
Route::prefix('absensi')->group(function () {
    Route::get('/dashboard', [AbsensiController::class, 'index'])->name('absen.dashboard');
    Route::get('/list', [AbsensiController::class, 'index'])->name('absensi.index');
    Route::post('/scan', [AbsensiController::class, 'scan'])->name('absensi.scan');
    
    // PERBAIKAN: Hapus kata '/absensi' di depan, karena sudah ada di prefix group
    Route::delete('/batal/{nipd}/{tipe}', [AbsensiController::class, 'batal'])->name('absensi.batal');
    Route::post('/tandai-alpha', [AbsensiController::class, 'tandaiAlpha'])->name('absensi.tandaiAlpha');
});
});
