<?php
namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Http;

// Wajib untuk koneksi ke API Node.js

class AdminController extends Controller
{
    /**
     * 1. DATA KEHADIRAN
     * Menampilkan rekap absensi harian siswa dari API.
     */
    public function kehadiran(Request $request)
    {
        try {
            $tanggal = $request->get('tanggal', date('Y-m-d'));
            $kelas   = $request->get('kelas');
            $status  = $request->get('status');

            $response = Http::get(env('API_BASE_URL') . '/kehadiran', [
                'tanggal' => $tanggal,
                'kelas'   => $kelas,
                'status'  => $status,
            ]);

            $dataKehadiran = $response->json('data') ?? [];
            return view('admin.data_kehadiran', compact('dataKehadiran'));

        } catch (\Exception $e) {
            return view('admin.data_kehadiran')->with('dataKehadiran', []);
        }
    }

    /**
     * 2. DATA SISWA
     * Menampilkan database siswa aktif dengan filter pencarian.
     */
    public function siswa(Request $request)
    {
        try {
            $search = $request->get('q');
            $kelas  = $request->get('kelas');
            $tahun  = $request->get('tahun');

            $response = Http::get(env('API_BASE_URL') . '/siswa', [
                'q'     => $search,
                'kelas' => $kelas,
                'tahun' => $tahun,
            ]);

            $dataSiswa = $response->json('data') ?? [];
            return view('admin.data_siswa', compact('dataSiswa'));

        } catch (\Exception $e) {
            return view('admin.data_siswa')->with('dataSiswa', []);
        }
    }

    /**
     * 3. DATA PRESTASI (BARU)
     * Menampilkan daftar pencapaian siswa dari API.
     */
    public function prestasi(Request $request)
    {
        try {
            // Ambil filter pencarian jika ada
            $search = $request->get('q');

            // Panggil API Prestasi di Node.js kawan
            $response = Http::get(env('API_BASE_URL') . '/prestasi', [
                'q' => $search,
            ]);

            $dataPrestasi = $response->json('data') ?? [];

            // Kirim data ke view admin/data_prestasi.blade.php
            return view('admin.data_prestasi', compact('dataPrestasi'));

        } catch (\Exception $e) {
            // Fallback jika API bermasalah agar halaman tidak blank
            return view('admin.data_prestasi')->with('dataPrestasi', []);
        }
    }

    /**
     * FUNGSI LAINNYA
     * Sementara masih menggunakan tampilan statis.
     */
    public function pelanggaran()
    {return view('admin.data_pelanggaran');}
    public function laporan()
    {return view('admin.laporan');}
    public function users()
    {return view('admin.users');}
    public function pengaturan()
    {return view('admin.pengaturan');}
}
