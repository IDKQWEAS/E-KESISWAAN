<?php
namespace App\Http\Controllers\Kepsek;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Session;

class MonitoringPrestasiController extends Controller
{
    private function headers()
    {
        return ['Cookie' => 'token=' . Session::get('token')];
    }

    private function base()
    {
        return env('API_BASE_URL');
    }

    public function index(Request $request)
    {
        $user  = Session::get('user_data') ?? Session::get('user');
        $token = Session::get('token');

        if (! $user || ! $token) {
            return redirect()->route('login');
        }

        $role           = $user['role'] ?? 'kepala_sekolah';
        $filterKategori = $request->get('kategori');
        $filterTingkat  = $request->get('tingkat');
        $page           = max(1, (int) $request->get('page', 1));
        $limit          = 12; // grid 3 kolom, 4 baris = 12
        $reqIdTa        = $request->get('id_tahun_ajaran');

        $prestasi       = [];
        $pagination     = ['total' => 0, 'page' => $page, 'limit' => $limit, 'totalPages' => 1];
        $taString       = null;
        $semesterString = null;

        try {
            // 1. Resolve Tahun Ajaran — identik dengan pola GuruBK
            $rTa = Http::withHeaders($this->headers())->get($this->base() . '/tahun_ajaran');
            if ($rTa->successful()) {
                $taList     = $rTa->json();
                $taTerpilih = null;

                if ($reqIdTa) {
                    $taTerpilih = collect($taList)->firstWhere('id', $reqIdTa) ?? collect($taList)->firstWhere('id', (int) $reqIdTa);
                }
                if (! $taTerpilih) {
                    $taTerpilih = collect($taList)->firstWhere('status', 'aktif');
                }
                if (! $taTerpilih && count($taList) > 0) {
                    $taTerpilih = $taList[0];
                }

                if ($taTerpilih) {
                    $taString       = $taTerpilih['tahun_ajaran'];
                    $semesterString = $taTerpilih['semester'];
                }
            }

            if (! $taString) {
                goto render;
            }

            // 2. Ambil data prestasi dengan filter TA + semester (langsung 1 request, tidak loop)
            $params = ['page' => $page, 'limit' => $limit];
            if ($taString) {
                $params['tahun_ajaran'] = $taString;
            }

            if ($semesterString) {
                $params['semester'] = $semesterString;
            }

            $response = Http::withHeaders($this->headers())
                ->get($this->base() . '/prestasi', $params);

            if ($response->successful()) {
                $json       = $response->json();
                $rows       = $json['data'] ?? [];
                $pagination = $json['pagination'] ?? $pagination;

                // Filter kategori & tingkat di PHP (BE /prestasi tidak support param ini)
                if ($filterKategori) {
                    $rows = array_values(array_filter(
                        $rows,
                        fn($item) => ($item['kategori'] ?? '') === $filterKategori
                    ));
                }
                if ($filterTingkat) {
                    $rows = array_values(array_filter(
                        $rows,
                        fn($item) => ($item['tingkat'] ?? '') === $filterTingkat
                    ));
                }

                $prestasi = $rows;
            }

        } catch (\Exception $e) {
            \Log::error('MonitoringPrestasi Kepsek error: ' . $e->getMessage());
        }

        render:
        return view('kepsek.monitoring_prestasi', compact(
            'prestasi',
            'pagination',
            'role',
            'filterKategori',
            'filterTingkat',
            'reqIdTa',
            'taString',
            'page',
            'limit'
        ));
    }
}
