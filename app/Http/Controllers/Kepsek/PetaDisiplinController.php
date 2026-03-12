<?php
namespace App\Http\Controllers\Kepsek;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Session;

class PetaDisiplinController extends Controller
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

        $role = $user['role'] ?? 'kepala_sekolah';

        // Filter dari dalam halaman — hanya kelas
        $filterKelas = $request->get('kelas');
        $page        = max(1, (int) $request->get('page', 1));
        $limit       = 20;
        $reqIdTa     = $request->get('id_tahun_ajaran');

        $dataPelanggaran = [];
        $pagination      = ['total' => 0, 'page' => $page, 'limit' => $limit, 'totalPages' => 1];
        $taString        = null;
        $semesterString  = null;

        try {
            // ── 1. Resolve Tahun Ajaran — IDENTIK dengan GuruBK ──────────────
            $rTa = Http::withHeaders($this->headers())->get($this->base() . '/tahun_ajaran');
            if ($rTa->successful()) {
                $taList     = $rTa->json();
                $taTerpilih = null;

                if ($reqIdTa) {
                    // Coba match by id (cast int, persis seperti GuruBK)
                    $taTerpilih = collect($taList)->firstWhere('id', $reqIdTa) ?? collect($taList)->firstWhere('id', (int) $reqIdTa);
                }
                // Fallback ke yang statusnya aktif
                if (! $taTerpilih) {
                    $taTerpilih = collect($taList)->firstWhere('status', 'aktif');
                }
                // Fallback ke data pertama
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

            // ── 2. Ambil data total poin pelanggaran — kirim TA + semester persis seperti GuruBK ──
            $params = ['page' => $page, 'limit' => $limit];
            if ($taString) {
                $params['tahun_ajaran'] = $taString;
            }

            if ($semesterString) {
                $params['semester'] = $semesterString;
            }

            $response = Http::withHeaders($this->headers())
                ->get($this->base() . '/pelanggaran/total', $params);

            if ($response->successful()) {
                $json       = $response->json();
                $rows       = $json['data'] ?? [];
                $pagination = $json['pagination'] ?? $pagination;

                // Filter kelas di PHP (BE tidak support param kelas di endpoint ini)
                if ($filterKelas) {
                    $rows = array_values(array_filter(
                        $rows,
                        fn($s) => ($s['kelas'] ?? '') === $filterKelas
                    ));
                }

                // Decode riwayat_pelanggaran (JSON_ARRAYAGG dari MySQL → string) + sort terbaru
                foreach ($rows as &$siswa) {
                    if (isset($siswa['riwayat_pelanggaran']) && is_string($siswa['riwayat_pelanggaran'])) {
                        $siswa['riwayat_pelanggaran'] = json_decode($siswa['riwayat_pelanggaran'], true) ?? [];
                    }
                    if (! empty($siswa['riwayat_pelanggaran'])) {
                        usort($siswa['riwayat_pelanggaran'], fn($a, $b) => strcmp(
                            $b['tanggal'] ?? '',
                            $a['tanggal'] ?? ''
                        ));
                    }
                }
                unset($siswa);

                $dataPelanggaran = $rows;
            }

        } catch (\Exception $e) {
            \Log::error('PetaDisiplin Kepsek error: ' . $e->getMessage());
        }

        render:
        return view('kepsek.peta_disiplin', compact(
            'dataPelanggaran',
            'pagination',
            'role',
            'filterKelas',
            'reqIdTa',
            'taString',
            'page',
            'limit'
        ));
    }
}
