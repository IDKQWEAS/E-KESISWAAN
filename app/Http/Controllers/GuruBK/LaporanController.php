<?php

namespace App\Http\Controllers\GuruBK;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Session;

class LaporanController extends Controller
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

    private function base()
    {
        return env('API_BASE_URL');
    }
   public function index() {
    $user = Session::get('user_data') ?? Session::get('user');
    $role = $user['role'] ?? 'guru_bk';

        $token = Session::get('token');
        if (!$token) return redirect()->route('login');

        $taList = [];
        try {
            $response = Http::withHeaders($this->headers())->get($this->base() . '/tahun_ajaran');
            if ($response->successful()) {
                $taList = $response->json();
            }
        } catch (\Exception $e) {
            Log::error('Laporan Index Error: ' . $e->getMessage());
        }

        return view('guru_bk.laporan', compact('taList', 'role'));    
        }

    public function download(Request $request, $type, $format)
    {
        $token = Session::get('token');
        if (!$token) return redirect()->route('login');

        try {
            // 1. Ambil ID TA dari request dropdown
            $id_ta = $request->query('id_tahun_ajaran');

            // 2. Ambil data lengkap TA dari API untuk mendapatkan String 'tahun_ajaran' & 'semester'
            $resTa = Http::withHeaders($this->headers())->get($this->base() . '/tahun_ajaran');
            $taData = collect($resTa->json())->firstWhere('id', (int)$id_ta);

            if (!$taData) {
                return back()->with('error', 'Tahun ajaran tidak ditemukan.');
            }

            // 3. Susun parameter sesuai keinginan API Node.js (Gunakan String, bukan ID)
            $params = [
                'kelas'        => $request->query('kelas'),
                'tahun_ajaran' => $taData['tahun_ajaran'], // Contoh: "2025/2026"
                'semester'     => $taData['semester'],     // Contoh: "Ganjil"
            ];

            $apiUrl = $this->base() . "/export/{$type}/{$format}";
            
            // 4. Tembak API Export
            $response = Http::withHeaders($this->headers())->get($apiUrl, $params);

            if ($response->successful()) {
                $ext = ($format === 'pdf') ? 'pdf' : 'xlsx';
                $filename = "Laporan_" . ucfirst($type) . "_" . str_replace('/', '-', $taData['tahun_ajaran']) . "_" . date('His') . "." . $ext;

                return response($response->body(), 200, [
                    'Content-Type'        => $response->header('Content-Type') ?? 'application/octet-stream',
                    'Content-Disposition' => 'attachment; filename="' . $filename . '"',
                ]);
            }

            // Jika 400, tampilkan pesan error dari Node.js
            $errorMsg = $response->json()['message'] ?? 'Gagal mendownload laporan (Error 400)';
            return back()->with('error', $errorMsg);

        } catch (\Exception $e) {
            Log::error('Download Error: ' . $e->getMessage());
            return back()->with('error', 'Terjadi kesalahan sistem.');
        }
    }
}