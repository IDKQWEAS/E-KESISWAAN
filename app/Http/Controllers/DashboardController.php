<?php
namespace App\Http\Controllers;

use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Session;

class DashboardController extends Controller
{
    public function index()
    {
        // 1. Ambil data user dari session
        $user = Session::get('user_data');
        if (! $user) {
            return redirect()->route('login');
        }

        $role = $user['role'];

        // 2. Siapkan data statistik default
        $statistik = $this->getDefaultStatistik();

        try {
            // 3. Tarik data asli dari API Node.js
            $response = Http::get(env('API_BASE_URL') . '/statistik');
            if ($response->successful()) {
                $statistik = array_merge($statistik, $response->json('data') ?? []);
            }
        } catch (\Exception $e) {
            // Jika API mati, tetap tampilkan dashboard dengan angka 0
        }

        // 4. Arahkan ke folder view berdasarkan role
        // Jika role 'admin' -> views/admin/dashboard.blade.php
        // Jika role 'guru_bk' -> views/bk/dashboard.blade.php
        $viewFolder = match ($role) {
            'admin'          => 'admin',
            'kepala_sekolah' => 'kepsek',
            'guru_bk'        => 'bk',
            'absensi'        => 'absensi',
            default          => 'admin'
        };

        return view("$viewFolder.dashboard", compact('statistik'));
    }

    private function getDefaultStatistik()
    {
        return [
            'total_siswa'         => 0, 'hadir_hari_ini'     => 0, 'terlambat' => 0,
            'tahun_ajaran'        => '2025/2026', 'semester' => 'Ganjil',
            'grafik_harian'       => ['hadir' => [0, 0, 0], 'izin' => [0, 0, 0], 'sakit' => [0, 0, 0], 'alpa' => [0, 0, 0]],
            'grafik_status'       => [0, 0],
            'grafik_pelanggaran'  => ['poin' => [0, 0, 0, 0, 0, 0], 'labels' => ["Jan", "Feb", "Mar", "Apr", "Mei", "Jun"]],
            'aktivitas_terlambat' => [],
        ];
    }
}
