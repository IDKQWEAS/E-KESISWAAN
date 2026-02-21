<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class DashboardController extends Controller
{
    // === AUTH ===
    public function index() { return view('auth.login'); }
    
    public function login(Request $request) {
        $role = $request->role;
        if($role == 'admin') return redirect('/admin/dashboard');
        if($role == 'bk') return redirect('/bk/dashboard');
        if($role == 'kepsek') return redirect('/kepsek/dashboard');
        if($role === 'absen') return redirect('/absensi');
        return redirect('/');
    }

    // ADMIN PAGES ===
    public function adminDashboard()   { return view('admin.dashboard'); }
    public function adminKehadiran()   { return view('admin.data_kehadiran'); }
    public function adminSiswa()       { return view('admin.data_siswa'); }
    public function adminPrestasi()    { return view('admin.data_prestasi'); }
    public function adminPelanggaran() { return view('admin.data_pelanggaran'); }
    public function adminLaporan()     { return view('admin.laporan'); }
    public function adminUsers()       { return view('admin.users'); }
    public function adminPengaturan()  { return view('admin.pengaturan'); }

    // BK PAGES ===
    public function bkDashboard()      { return view('bk.dashboard'); }
    public function bkPelanggaran()    { return view('bk.input_pelanggaran'); }
    public function bkPemantauan()     { return view('bk.pemantauan'); }
    public function bkBiodata()        { return view('bk.biodata'); }
    public function bkHomeVisit()      { return view('bk.home_visit'); }
    public function bkPerizinan()      { return view('bk.perizinan'); }
    public function bkRekap()          { return view('bk.rekap_kehadiran'); }

    // KEPSEK PAGES ===
    public function kepsekDashboard()  { return view('kepsek.dashboard'); }
    public function kepsekDisiplin()   { return view('kepsek.peta_disiplin'); }
    public function kepsekPrestasi()   { return view('kepsek.monitoring_prestasi'); }
    public function kepsekAbsensi()    { return view('kepsek.monitoring_absensi'); }
    public function kepsekVisit()      { return view('kepsek.monitoring_visit'); }

    // absensi
    public function halamanAbsensi() { return view('absensi.index');}
}