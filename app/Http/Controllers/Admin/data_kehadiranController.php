<?php
namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Session;

class data_kehadiranController extends Controller
{
    private function headers() {
        return ['Cookie' => 'token=' . Session::get('token'), 'Accept' => 'application/json'];
    }

    public function index(Request $request)
    {
        $token = Session::get('token');
        if (!$token) return redirect()->route('login');

        $base = env('API_BASE_URL');
        $hariIni = Carbon::now()->timezone('Asia/Jakarta')->format('Y-m-d');
        
        $dataKehadiran = [];
        $taList = [];
        $taAktif = null;

        try {
            // 1. Ambil List Tahun Ajaran
            $resTa = Http::withHeaders($this->headers())->get($base . '/tahun_ajaran');
            if ($resTa->successful()) {
                $taList = $resTa->json();
                $reqIdTa = $request->get('id_tahun_ajaran');
                $taAktif = $reqIdTa 
                    ? collect($taList)->firstWhere('id', (int)$reqIdTa) 
                    : (collect($taList)->firstWhere('status', 'aktif') ?? collect($taList)->first());
            }

            // 2. Susun Parameter API
            $params = [
                'tahun_ajaran' => $taAktif['tahun_ajaran'] ?? '',
                'semester'     => $taAktif['semester'] ?? '',
                'limit'        => 100,
            ];

            // 3. Panggil API Absensi
            $response = Http::withHeaders($this->headers())->get($base . '/absensi', $params);

            if ($response->successful()) {
                $dataKehadiran = $response->json()['data'] ?? [];
                
                
                
                // --- FILTER MANUAL DI LARAVEL (Agar Auto-Update Berfungsi) ---
                
                // ==========================
// FILTER KELAS
// ==========================

if ($request->filled('kelas')) {

    $kelasFilter = strtoupper(
        preg_replace('/[^A-Z0-9]/', '', $request->get('kelas'))
    );

    $dataKehadiran = array_filter($dataKehadiran, function ($item) use ($kelasFilter) {

        $kelasItem = strtoupper(
            preg_replace('/[^A-Z0-9]/', '', $item['kelas'] ?? '')
        );

        return $kelasItem === $kelasFilter;
    });
}

// ==========================
// FILTER STATUS
// ==========================

if ($request->filled('status')) {

    $statusFilter = strtolower(
        preg_replace('/[^a-z]/', '', $request->get('status'))
    );

    $dataKehadiran = array_filter($dataKehadiran, function ($item) use ($statusFilter) {

        $statusItem = strtolower(
            preg_replace('/[^a-z]/', '', $item['status'] ?? '')
        );

        return $statusItem === $statusFilter;
    });
}
                $dataKehadiran = array_values($dataKehadiran);
            }

        } catch (\Exception $e) {
            \Log::error('Kehadiran Error: ' . $e->getMessage());
        }

        // Tetap kirim -20% compact styling ke view
        return view('admin.data_kehadiran', compact('dataKehadiran', 'taList', 'taAktif'));
    }
}