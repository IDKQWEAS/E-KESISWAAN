<?php
namespace App\Http\Controllers\Kepsek;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Session;

class DashboardController extends Controller
{
    public function index(Request $request)
    {
        $user  = Session::get('user_data') ?? Session::get('user');
        $token = Session::get('token');

        if (! $user || ! $token) {
            return redirect()->route('login');
        }

        $role          = $user['role'] ?? 'kepala_sekolah';
        $idTahunAjaran = $request->get('id_tahun_ajaran');

        // Ambil tahun ajaran aktif jika tidak ada di query
        if (! $idTahunAjaran) {
            try {
                $resp = Http::withHeaders(['Cookie' => 'token=' . $token])
                    ->get(env('API_BASE_URL') . '/tahun_ajaran');

                if ($resp->successful()) {
                    $list = $resp->json();
                    foreach ($list as $ta) {
                        if ($ta['is_aktif'] ?? $ta['aktif'] ?? $ta['status'] ?? false) {
                            $idTahunAjaran = $ta['id'];
                            break;
                        }
                    }
                    if (! $idTahunAjaran && count($list) > 0) {
                        $idTahunAjaran = $list[0]['id'];
                    }
                }
            } catch (\Exception $e) {
                \Log::error('Dashboard tahun ajaran error: ' . $e->getMessage());
            }
        }

        $statistik    = [];
        $rekap        = ['hadir' => 0, 'izin' => 0, 'sakit' => 0, 'alpha' => 0];
        $rekapWeekly  = ['hadir' => 0, 'izin' => 0, 'sakit' => 0, 'alpha' => 0];
        $rekapMonthly = ['hadir' => 0, 'izin' => 0, 'sakit' => 0, 'alpha' => 0];
        $tren         = [];

        if ($idTahunAjaran) {
            $headers = ['Cookie' => 'token=' . $token];
            $base    = env('API_BASE_URL');

            try {
                // [1] Statistik utama
                $r = Http::withHeaders($headers)
                    ->get($base . '/statistik', ['id_tahun_ajaran' => $idTahunAjaran]);
                if ($r->successful()) {
                    $statistik = $r->json();
                }

                // [2] Rekap kehadiran daily
                $r = Http::withHeaders($headers)
                    ->get($base . '/statistik/rekap-kehadiran', [
                        'id_tahun_ajaran' => $idTahunAjaran,
                        'type'            => 'daily',
                    ]);
                if ($r->successful()) {
                    $rekap = $r->json()['data'] ?? $rekap;
                }

                // [3] Rekap kehadiran weekly
                $r = Http::withHeaders($headers)
                    ->get($base . '/statistik/rekap-kehadiran', [
                        'id_tahun_ajaran' => $idTahunAjaran,
                        'type'            => 'weekly',
                    ]);
                if ($r->successful()) {
                    $rekapWeekly = $r->json()['data'] ?? $rekapWeekly;
                }

                // [4] Rekap kehadiran monthly
                $r = Http::withHeaders($headers)
                    ->get($base . '/statistik/rekap-kehadiran', [
                        'id_tahun_ajaran' => $idTahunAjaran,
                        'type'            => 'monthly',
                    ]);
                if ($r->successful()) {
                    $rekapMonthly = $r->json()['data'] ?? $rekapMonthly;
                }

                // [5] Tren pelanggaran
                $r = Http::withHeaders($headers)
                    ->get($base . '/statistik/tren-pelanggaran', [
                        'id_tahun_ajaran' => $idTahunAjaran,
                    ]);
                if ($r->successful()) {
                    $tren = $r->json()['trenPelanggaran'] ?? [];
                }

            } catch (\Exception $e) {
                \Log::error('Dashboard statistik error: ' . $e->getMessage());
            }
        }

        return view('kepsek.dashboard', compact(
            'statistik',
            'rekap',
            'rekapWeekly',
            'rekapMonthly',
            'tren',
            'role',
            'idTahunAjaran'
        ));
    }
}
