<?php
namespace App\Http\Controllers\Kepsek;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Session;

class DashboardController extends Controller
{
    private function resolveTahunAjaran($reqIdTa)
    {
        try {
            $r = Http::withHeaders(['Cookie' => 'token=' . Session::get('token')])->get(env('API_BASE_URL') . '/tahun_ajaran');
            if ($r->successful()) {
                $taList     = $r->json();
                $taTerpilih = $reqIdTa ? (collect($taList)->firstWhere('id', $reqIdTa) ?? collect($taList)->firstWhere('id', (int) $reqIdTa)) : collect($taList)->firstWhere('status', 'aktif');
                if (! $taTerpilih && count($taList) > 0) {
                    $taTerpilih = $taList[0];
                }

                return $taTerpilih['id'] ?? null;
            }
        } catch (\Exception $e) {}
        return null;
    }

    public function index(Request $request)
    {
        $user  = Session::get('user_data') ?? Session::get('user');
        $token = Session::get('token');

        if (! $user || ! $token) {
            return redirect()->route('login');
        }

        $role    = $user['role'] ?? 'kepala_sekolah';
        $headers = ['Cookie' => 'token=' . $token];
        $base    = env('API_BASE_URL');

        $idTahunAjaran = $this->resolveTahunAjaran($request->get('id_tahun_ajaran'));

        $emptyTingkat = fn() => [
            ['tingkat' => '7', 'hadir' => 0, 'izin' => 0, 'sakit' => 0, 'alpha' => 0],
            ['tingkat' => '8', 'hadir' => 0, 'izin' => 0, 'sakit' => 0, 'alpha' => 0],
            ['tingkat' => '9', 'hadir' => 0, 'izin' => 0, 'sakit' => 0, 'alpha' => 0],
        ];

        $rekapHarian = $rekapMingguan = $rekapBulanan = $emptyTingkat();
        $statistik   = $tren   = [];

        $normalisasi = function (array $rows): array {
            $map    = [];foreach ($rows as $row) {$map[(string) ($row['tingkat'] ?? '')] = $row;}
            $result = [];
            foreach (['7', '8', '9'] as $t) {
                $result[] = ['tingkat' => $t, 'hadir' => (int) ($map[$t]['hadir'] ?? 0), 'izin' => (int) ($map[$t]['izin'] ?? 0), 'sakit' => (int) ($map[$t]['sakit'] ?? 0), 'alpha' => (int) ($map[$t]['alpha'] ?? 0)];
            }
            return $result;
        };

        try {
            if (! $idTahunAjaran) {
                goto render;
            }

            $r = Http::withHeaders($headers)->get($base . '/statistik', ['id_tahun_ajaran' => $idTahunAjaran]);
            if ($r->successful()) {
                $statistik = $r->json();
                if (! empty($statistik['rata_rata_kedatangan'])) {
                    $statistik['rata_rata_kedatangan'] = substr($statistik['rata_rata_kedatangan'], 0, 8);
                }

            }

            if (! empty($statistik['siswaTerlambatTerkini'])) {
                $statistik['siswaTerlambatTerkini'] = array_map(function ($siswa) {
                    $jamMasuk                 = $siswa['jam_masuk'] ?? '00:00:00';
                    $siswa['waktu_datang']    = substr($jamMasuk, 0, 5);
                    $siswa['menit_terlambat'] = max(0, (int) round((strtotime($jamMasuk) - strtotime('07:00:00')) / 60));
                    return $siswa;
                }, $statistik['siswaTerlambatTerkini']);
            }

            $r1 = Http::withHeaders($headers)->get($base . '/statistik/rekap-kehadiran', ['id_tahun_ajaran' => $idTahunAjaran, 'type' => 'daily']);
            if ($r1->successful()) {
                $rekapHarian = $normalisasi($r1->json()['tingkat'] ?? []);
            }

            $r2 = Http::withHeaders($headers)->get($base . '/statistik/rekap-kehadiran', ['id_tahun_ajaran' => $idTahunAjaran, 'type' => 'weekly']);
            if ($r2->successful()) {
                $rekapMingguan = $normalisasi($r2->json()['tingkat'] ?? []);
            }

            $r3 = Http::withHeaders($headers)->get($base . '/statistik/rekap-kehadiran', ['id_tahun_ajaran' => $idTahunAjaran, 'type' => 'monthly']);
            if ($r3->successful()) {
                $rekapBulanan = $normalisasi($r3->json()['tingkat'] ?? []);
            }

            $r4 = Http::withHeaders($headers)->get($base . '/statistik/tren-pelanggaran', ['id_tahun_ajaran' => $idTahunAjaran]);
            if ($r4->successful()) {
                $tren = $r4->json()['trenPelanggaran'] ?? [];
            }

        } catch (\Exception $e) {}

        render:
        return view('kepsek.dashboard', compact('statistik', 'tren', 'role', 'idTahunAjaran', 'rekapHarian', 'rekapMingguan', 'rekapBulanan'));
    }
}
