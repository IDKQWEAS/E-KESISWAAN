<?php

namespace App\Http\Controllers\Absensi;

use App\Http\Controllers\Controller;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Session;

class AbsensiController extends Controller
{
    private function headers()
    {
        $token = Session::get('token');
        return [
            'Cookie'        => 'token=' . $token,
            'Authorization' => 'Bearer ' . $token,
            'Accept'        => 'application/json',
        ];
    }

    private function base() { return env('API_BASE_URL'); }

    public function index(Request $request)
    {
        if (!Session::has('token')) return redirect()->route('login');

        $tingkat = $request->query('tingkat');
        $riwayatAbsen = [];
        $hariIni = now()->timezone('Asia/Jakarta')->format('Y-m-d');

        try {
            // 1. Ambil Tahun Ajaran Aktif agar API tidak error 400
            $resTa = Http::withHeaders($this->headers())->get($this->base() . '/tahun_ajaran');
            $taList = $resTa->successful() ? $resTa->json() : [];
            $taAktif = collect($taList)->firstWhere('status', 'aktif') ?? collect($taList)->first();

            if ($taAktif) {
                // 2. Ambil data absensi hari ini (menggunakan parameter 'date' sesuai Node.js)
                $response = Http::withHeaders($this->headers())->get($this->base() . '/absensi', [
                    'tahun_ajaran' => $taAktif['tahun_ajaran'],
                    'semester'     => $taAktif['semester'],
                    'date'         => $hariIni,
                    'tingkat'      => $tingkat,
                    'limit'        => 100,
                ]);

                if ($response->successful()) {
                    $riwayatAbsen = $response->json()['data'] ?? [];
                }
            }
        } catch (\Exception $e) {
            \Log::error('Absensi Index Error: ' . $e->getMessage());
        }

        return view('absensi.index', compact('riwayatAbsen', 'tingkat'));
    }

    public function scan(Request $request)
    {
        try {
            $resCfg = Http::withHeaders($this->headers())->get($this->base() . '/config');
            $configs = $resCfg->successful() ? collect($resCfg->json())->pluck('config_value', 'config_key') : collect([]);
            
            $jamSekarang = now()->timezone('Asia/Jakarta')->format('H:i:s');
            $jamPulangMulai = $configs['jam_boleh_pulang'] ?? '14:00:00';

            // Penentuan tipe otomatis
            $tipe = ($jamSekarang >= $jamPulangMulai) ? 'pulang' : 'datang';

            $response = Http::withHeaders($this->headers())->post($this->base() . '/absensi', [
                'nipd'         => $request->nipd, // Menggunakan NIPD
                'tipe_absensi' => $tipe,
            ]);

            return response()->json($response->json(), $response->status());
        } catch (\Exception $e) {
            return response()->json(['message' => 'Gagal terhubung ke API'], 500);
        }
    }

    public function batal($nipd, $tipe)
    {
        try {
            $response = Http::withHeaders($this->headers())->delete($this->base() . "/absensi/nipd/{$nipd}/{$tipe}");
            return response()->json($response->json(), $response->status());
        } catch (\Exception $e) {
            return response()->json(['message' => 'Gagal terhubung ke API'], 500);
        }
    }

    public function tandaiAlpha()
    {
        try {
            $response = Http::withHeaders($this->headers())->post($this->base() . '/absensi/tandaiAlpha');
            return response()->json($response->json(), $response->status());
        } catch (\Exception $e) {
            return response()->json(['message' => 'Gagal terhubung ke API backend'], 500);
        }
    }
}