<?php
namespace App\Http\Controllers\GuruBK;

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

        $role    = $user['role'] ?? 'guru_bk';
        $headers = ['Cookie' => 'token=' . $token];
        $base    = env('API_BASE_URL');

        $statistik = [];
        $tren      = [];

        // 1. TANGKAP DULU PILIHAN USER DARI DROPDOWN HEADER
        $idTahunAjaran = $request->get('id_tahun_ajaran');

        $emptyTingkat = fn() => [
            ['tingkat' => '7', 'hadir' => 0, 'izin' => 0, 'sakit' => 0, 'alpha' => 0],
            ['tingkat' => '8', 'hadir' => 0, 'izin' => 0, 'sakit' => 0, 'alpha' => 0],
            ['tingkat' => '9', 'hadir' => 0, 'izin' => 0, 'sakit' => 0, 'alpha' => 0],
        ];

        $rekapHarian   = $emptyTingkat();
        $rekapMingguan = $emptyTingkat();
        $rekapBulanan  = $emptyTingkat();

        $normalisasi = function (array $rows): array {
            $map = [];
            foreach ($rows as $row) {
                $map[(string) ($row['tingkat'] ?? '')] = $row;
            }
            $result = [];
            foreach (['7', '8', '9'] as $t) {
                $result[] = [
                    'tingkat' => $t,
                    'hadir'   => (int) ($map[$t]['hadir'] ?? 0),
                    'izin'    => (int) ($map[$t]['izin'] ?? 0),
                    'sakit'   => (int) ($map[$t]['sakit'] ?? 0),
                    'alpha'   => (int) ($map[$t]['alpha'] ?? 0),
                ];
            }
            return $result;
        };

        try {
            // 2. LOGIKA BARU: Cari id_tahun_ajaran yang VALID
            if (! $idTahunAjaran) {
                // Kalau user belum milih dropdown, kita cari otomatis yang statusnya "Aktif" di database
                $resp = Http::withHeaders($headers)->get($base . '/tahun_ajaran');
                if ($resp->successful()) {
                    $taList = $resp->json();
                    foreach ($taList as $ta) {
                        if (($ta['status'] ?? '') === 'aktif') {
                            $idTahunAjaran = $ta['id'];
                            break;
                        }
                    }
                    // Kalau ternyata gak ada satupun yang status='aktif', ambil aja data pertama dari list
                    if (! $idTahunAjaran && count($taList) > 0) {
                        $idTahunAjaran = $taList[0]['id'];
                    }
                }
            }

            if (! $idTahunAjaran) {
                goto render; // Jika database benar-benar kosong, lewati ambil statistik
            }

            // 3. TARIK STATISTIK BERDASARKAN TA YANG DIPILIH / AKTIF
            $r = Http::withHeaders($headers)
                ->get($base . '/statistik', ['id_tahun_ajaran' => $idTahunAjaran]);
            if ($r->successful()) {
                $statistik = $r->json();

                // FIX: Bersihkan format rata-rata kedatangan dari desimal (.0000)
                if (! empty($statistik['rata_rata_kedatangan'])) {
                    // Memotong string "18:55:32.0000" menjadi "18:55:32"
                    $statistik['rata_rata_kedatangan'] = substr($statistik['rata_rata_kedatangan'], 0, 8);
                }
            }

            // Mapping siswaTerlambatTerkini
            if (! empty($statistik['siswaTerlambatTerkini'])) {
                $statistik['siswaTerlambatTerkini'] = array_map(function ($siswa) {
                    $jamMasuk                 = $siswa['jam_masuk'] ?? '00:00:00';
                    $menitTelat               = max(0, (int) round((strtotime($jamMasuk) - strtotime('07:00:00')) / 60));
                    $siswa['waktu_datang']    = substr($jamMasuk, 0, 5);
                    $siswa['menit_terlambat'] = $menitTelat;
                    return $siswa;
                }, $statistik['siswaTerlambatTerkini']);
            }

            // Rekap per tingkat
            $r = Http::withHeaders($headers)->get($base . '/statistik/rekap-kehadiran', [
                'id_tahun_ajaran' => $idTahunAjaran, 'type' => 'daily',
            ]);
            if ($r->successful()) {
                $rekapHarian = $normalisasi($r->json()['tingkat'] ?? []);
            }

            $r = Http::withHeaders($headers)->get($base . '/statistik/rekap-kehadiran', [
                'id_tahun_ajaran' => $idTahunAjaran, 'type' => 'weekly',
            ]);
            if ($r->successful()) {
                $rekapMingguan = $normalisasi($r->json()['tingkat'] ?? []);
            }

            $r = Http::withHeaders($headers)->get($base . '/statistik/rekap-kehadiran', [
                'id_tahun_ajaran' => $idTahunAjaran, 'type' => 'monthly',
            ]);
            if ($r->successful()) {
                $rekapBulanan = $normalisasi($r->json()['tingkat'] ?? []);
            }

            // Tren pelanggaran
            $r = Http::withHeaders($headers)->get($base . '/statistik/tren-pelanggaran', [
                'id_tahun_ajaran' => $idTahunAjaran,
            ]);
            if ($r->successful()) {
                $tren = $r->json()['trenPelanggaran'] ?? [];
            }

        } catch (\Exception $e) {
            \Log::error('Dashboard BK error: ' . $e->getMessage());
        }

        render:
        return view('guru_bk.dashboard', compact(
            'statistik', 'tren', 'role', 'idTahunAjaran',
            'rekapHarian', 'rekapMingguan', 'rekapBulanan'
        ));
    }
}
