<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Session;

class data_pelanggaranController extends Controller
{
    public function index(Request $request)
    {
        $token = Session::get('token');
        $base = env('API_BASE_URL');

        // 1. PROTEKSI AWAL: Jika token tidak ada di session, langsung tendang ke login
        if (!$token) {
            return redirect()->route('login')->with('error', 'Sesi habis, silakan login kembali.');
        }

        try {
            // 2. REQUEST TAHUN AJARAN: Kirim Bearer Token & Cookie sekaligus agar stabil
            $resTa = Http::withHeaders([
                'Authorization' => 'Bearer ' . $token,
                'Cookie'        => 'token=' . $token, 
                'Accept'        => 'application/json'
            ])->get($base . '/tahun_ajaran');

            // Jika API Tahun Ajaran membalas 401, artinya token di session sudah tidak valid
            if ($resTa->status() === 401) {
                Session::forget('token'); // Hapus token sampah
                return redirect()->route('login')->with('error', 'Sesi tidak valid, silakan login ulang.');
            }

            $taList = $resTa->json();
            $taAktif = collect($taList)->firstWhere('status', 'aktif') ?? collect($taList)->first();

            // 3. VALIDASI PARAMETER: Jangan panggil API Pelanggaran jika data periode tidak ada
            if (!$taAktif || !isset($taAktif['tahun_ajaran'])) {
                throw new \Exception("Data Tahun Ajaran Aktif tidak ditemukan di server.");
            }

            // 4. REQUEST DATA PELANGGARAN: Gunakan data yang sudah divalidasi
            $response = Http::withHeaders([
                'Authorization' => 'Bearer ' . $token,
                'Cookie'        => 'token=' . $token,
                'Accept'        => 'application/json',
            ])->get($base . '/pelanggaran', [
                'limit'        => 100,
                'tahun_ajaran' => $taAktif['tahun_ajaran'], // Pastikan tidak kosong
                'semester'     => $taAktif['semester'],     // Pastikan tidak kosong
            ]);

            $dataPelanggaran = [];
            if ($response->successful()) {
                $resJson = $response->json();
                $dataPelanggaran = $resJson['data'] ?? [];
            }

            return view('admin.data_pelanggaran', compact('dataPelanggaran', 'taAktif'));

        } catch (\Exception $e) {
            Log::error('Pelanggaran Error: ' . $e->getMessage());
            // Berikan fallback agar halaman tidak mati total (White Screen)
            return view('admin.data_pelanggaran', [
                'dataPelanggaran' => [],
                'taAktif' => null
            ])->with('error', 'Gagal memuat data: ' . $e->getMessage());
        }
    }
}