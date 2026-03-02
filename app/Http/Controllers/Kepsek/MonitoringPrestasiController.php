<?php
namespace App\Http\Controllers\Kepsek;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Session;

class MonitoringPrestasiController extends Controller
{
    public function index(Request $request)
    {
        $user  = Session::get('user_data');
        $token = Session::get('token');

        if (! $user || ! $token) {
            return redirect()->route('login');
        }

        $role           = $user['role'] ?? 'kepala_sekolah';
        $filterKategori = $request->get('kategori');

        $prestasi = [];

        try {
            // Ambil semua halaman dari API /prestasi
            $allRows    = [];
            $page       = 1;
            $totalPages = 1;

            do {
                $response = Http::withHeaders([
                    'Cookie' => 'token=' . $token,
                ])->get(env('API_BASE_URL') . '/prestasi', [
                    'page'  => $page,
                    'limit' => 100,
                ]);

                if (! $response->successful()) {
                    break;
                }

                $json       = $response->json();
                $rows       = $json['data'] ?? [];
                $totalPages = $json['pagination']['totalPages'] ?? 1;

                $allRows = array_merge($allRows, $rows);
                $page++;

            } while ($page <= $totalPages);

            // Filter by kategori di PHP karena BE tidak support parameter ini
            if ($filterKategori) {
                $allRows = array_filter(
                    $allRows,
                    fn($item) => ($item['kategori'] ?? '') === $filterKategori
                );
            }

            $prestasi = array_values($allRows);

        } catch (\Exception $e) {
            \Log::error('MonitoringPrestasi error: ' . $e->getMessage());
            $prestasi = [];
        }

        return view('kepsek.monitoring_prestasi', compact('prestasi', 'role', 'filterKategori'));
    }
}
