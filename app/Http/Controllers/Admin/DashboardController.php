<?php
namespace App\Http\Controllers\admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Session;

class DashboardController extends Controller
{
    private function headers()
    {return ['Cookie' => 'token=' . Session::get('token')];}
    private function base()
{return env('API_BASE_URL');}

private function normalisasi(array $rows): array
{
    $map = [];

    foreach ($rows as $row) {
        $map[(string)($row['tingkat'] ?? '')] = $row;
    }

    $result = [];

    foreach (['7', '8', '9'] as $t) {
        $result[] = [
            'tingkat' => $t,
            'hadir'   => (int)($map[$t]['hadir'] ?? 0),
            'izin'    => (int)($map[$t]['izin'] ?? 0),
            'sakit'   => (int)($map[$t]['sakit'] ?? 0),
            'alpha'   => (int)($map[$t]['alpha'] ?? 0),
        ];
    }

    return $result;
}
    public function index(Request $request)
    {
        $user  = Session::get('user_data') ?? Session::get('user');
        $token = Session::get('token');

        if (! $user || ! $token) {
            return redirect()->route('login');
        }

        $role          = $user['role'] ?? 'admin';
        $idTahunAjaran = $request->get('id_tahun_ajaran');

        $taList        = [];
        $taAktifObj    = null;
        $statistik     = [];
        $rekapHarian   = [];
        $rekapMingguan = [];
        $rekapBulanan  = [];
        $tren          = [];
        $totalSiswa    = 0;

        try {
            // 1. Ambil List Tahun Ajaran
            $respTa = Http::withHeaders($this->headers())->get($this->base() . '/tahun_ajaran');
            if ($respTa->successful()) {
                $taList = $respTa->json();
                if (! $idTahunAjaran) {
                    $taAktifObj    = collect($taList)->firstWhere('status', 'aktif') ?? ($taList[0] ?? null);
                    $idTahunAjaran = $taAktifObj['id'] ?? null;
                } else {
                    $taAktifObj = collect($taList)->firstWhere('id', (int) $idTahunAjaran);
                }
            }

            if ($idTahunAjaran) {
                // 2. Ambil Total Siswa dari Endpoint /siswa (Mencegah Angka 0)
                $rSiswa = Http::withHeaders($this->headers())->get($this->base() . '/siswa', [
                    'limit'        => 1,
                    'tahun_ajaran' => $taAktifObj['tahun_ajaran'] ?? '',
                    'semester'     => $taAktifObj['semester'] ?? '',
                ]);

                if ($rSiswa->successful()) {
                    $totalSiswa = $rSiswa->json()['pagination']['total'] ?? 0;
                }

                // 3. Statistik Utama
                $rStat = Http::withHeaders($this->headers())->get($this->base() . '/statistik', ['id_tahun_ajaran' => $idTahunAjaran]);
                if ($rStat->successful()) {

    $statistik = $rStat->json();

    $statistik['total_siswa'] = $totalSiswa;

    // Format jam rata-rata datang
    if (!empty($statistik['rata_rata_kedatangan'])) {
        $statistik['rata_rata_kedatangan'] =
            substr($statistik['rata_rata_kedatangan'], 0, 5);
    }

    // Format siswa terlambat
    if (!empty($statistik['siswaTerlambatTerkini'])) {

        $statistik['siswaTerlambatTerkini'] = array_map(function ($siswa) {

            $jamMasuk = $siswa['jam_masuk'] ?? '00:00:00';

            $siswa['waktu_datang'] = substr($jamMasuk, 0, 5);

            $siswa['menit_terlambat'] = max(
                0,
                (int) round(
                    (strtotime($jamMasuk) - strtotime('07:00:00')) / 60
                )
            );

            return $siswa;

        }, $statistik['siswaTerlambatTerkini']);
    }

} else {

    $statistik['total_siswa'] = $totalSiswa;
}

                // 4. Rekap Kehadiran
                $r1 = Http::withHeaders($this->headers())->get($this->base() . '/statistik/rekap-kehadiran', ['id_tahun_ajaran' => $idTahunAjaran, 'type' => 'daily']);
                if ($r1->successful()) {
                    $rekapHarian = $this->normalisasi($r1->json()['tingkat'] ?? []);
                }

                $r2 = Http::withHeaders($this->headers())->get($this->base() . '/statistik/rekap-kehadiran', ['id_tahun_ajaran' => $idTahunAjaran, 'type' => 'weekly']);
                if ($r2->successful()) {
                    $rekapMingguan = $this->normalisasi($r2->json()['tingkat'] ?? []);
                }

                $r3 = Http::withHeaders($this->headers())->get($this->base() . '/statistik/rekap-kehadiran', ['id_tahun_ajaran' => $idTahunAjaran, 'type' => 'monthly']);
                if ($r3->successful()) {
                    $rekapBulanan = $this->normalisasi($r3->json()['tingkat'] ?? []);
                }

                // 5. Tren Pelanggaran
                $rTren = Http::withHeaders($this->headers())->get($this->base() . '/statistik/tren-pelanggaran', ['id_tahun_ajaran' => $idTahunAjaran]);
                if ($rTren->successful()) {
                    $tren = $rTren->json()['trenPelanggaran'] ?? [];
                }

            }
        } catch (\Exception $e) {
            Log::error('Dashboard Admin error: ' . $e->getMessage());
        }

        return view('admin.dashboard', compact(
            'role', 'statistik', 'rekapHarian', 'rekapMingguan', 'rekapBulanan', 'tren', 'taList', 'idTahunAjaran', 'totalSiswa'
        ));
    }

    // ==========================================
    // FUNGSI API PROXY KE BACKEND TAHUN AJARAN
    // ==========================================

    /** GET list semua tahun ajaran (untuk AJAX di modal) */
    public function listTahunAjaran()
    {
        $res = Http::withHeaders($this->headers())->get($this->base() . '/tahun_ajaran');
        return response()->json($res->json(), $res->status());
    }

    public function storeTahunAjaran(Request $request)
    {
        $res = Http::withHeaders($this->headers())->post($this->base() . '/tahun_ajaran', [
            'tahun_ajaran' => $request->input('tahun_ajaran') ?? $request->tahun_ajaran,
            'semester'     => $request->input('semester') ?? $request->semester,
        ]);
        return response()->json($res->json(), $res->status());
    }

    public function activateTahunAjaran(Request $request, $id)
    {
        // Tembak langsung endpoint PATCH {{BASE_URL}}/tahun_ajaran/aktifkan/{id}
        $res = Http::withHeaders($this->headers())->patch($this->base() . '/tahun_ajaran/aktifkan/' . $id);
        return response()->json($res->json(), $res->status());
    }
}
