<?php
namespace App\Http\Controllers\GuruBK;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Session;

class RekapKehadiranController extends Controller
{
    private function resolveTahunAjaran($token, $reqIdTa)
    {
        try {
            $r = Http::withHeaders(['Cookie' => 'token=' . $token])->get(env('API_BASE_URL') . '/tahun_ajaran');
            if ($r->successful()) {
                $taList     = $r->json();
                $taTerpilih = $reqIdTa ? (collect($taList)->firstWhere('id', $reqIdTa) ?? collect($taList)->firstWhere('id', (int) $reqIdTa)) : collect($taList)->firstWhere('status', 'aktif');
                return [
                    'tahun_ajaran' => $taTerpilih['tahun_ajaran'] ?? null,
                    'semester'     => $taTerpilih['semester'] ?? null,
                ];
            }
        } catch (\Exception $e) {}
        return ['tahun_ajaran' => null, 'semester' => null];
    }

    public function index(Request $request)
    {
        $user  = Session::get('user_data');
        $token = Session::get('token');
        if (! $user || ! $token) {
            return redirect()->route('login');
        }

        $role       = $user['role'] ?? 'guru_bk';
        $kelasAktif = $request->get('kelas', '');
        $bulanAktif = $request->get('bulan', date('n')); // Default ke nomor bulan sekarang (1-12)
        $taResolved = $this->resolveTahunAjaran($token, $request->get('id_tahun_ajaran'));

        $absensi    = [];
        $pagination = [];

        try {
            $params = ['page' => $request->get('page', 1), 'limit' => 20];
            if ($kelasAktif) {
                $params['kelas'] = $kelasAktif;
            }
            
            // Kirim parameter bulan ke API Node.js kawan
            if ($bulanAktif) {
                $params['bulan'] = $bulanAktif;
                $params['month'] = $bulanAktif; // Antisipasi kl BE menggunakan key 'month'
            }

            if ($taResolved['tahun_ajaran']) {
                $params['tahun_ajaran'] = $taResolved['tahun_ajaran'];
            }

            if ($taResolved['semester']) {
                $params['semester'] = $taResolved['semester'];
            }

            $r = Http::withHeaders(['Cookie' => 'token=' . $token])->get(env('API_BASE_URL') . '/rekap_kehadiran', $params);
            if ($r->successful()) {
                $absensi    = $r->json()['data'] ?? [];
                $pagination = $r->json()['pagination'] ?? [];
            }
        } catch (\Exception $e) {}

        return view('guru_bk.rekap_kehadiran', compact('absensi', 'pagination', 'role', 'kelasAktif', 'bulanAktif'));
    }

    public function downloadPdf(Request $request)
    {
        $token = Session::get('token');
        if (! $token) {
            return redirect()->route('login');
        }

        $kelasAktif = $request->get('kelas', '');
        $bulanAktif = $request->get('bulan', date('n')); // Ambil data bulan
        $taResolved = $this->resolveTahunAjaran($token, $request->get('id_tahun_ajaran'));

        if (! $taResolved['tahun_ajaran']) {
            return back()->with('error', 'Tahun ajaran tidak valid.');
        }

        try {
            $params = [
                'tahun_ajaran' => $taResolved['tahun_ajaran'],
                'semester'     => $taResolved['semester'],
            ];
            if ($kelasAktif) {
                $params['kelas'] = $kelasAktif;
            }
            if ($bulanAktif) {
                $params['bulan'] = $bulanAktif;
                $params['month'] = $bulanAktif;
            }

            $r = Http::withHeaders(['Cookie' => 'token=' . $token])->timeout(60)->get(env('API_BASE_URL') . '/export/absensi/pdf', $params);

            if ($r->successful()) {
                $namaFile = $kelasAktif ? "Rekap_Kehadiran_Kelas_{$kelasAktif}_Bulan_{$bulanAktif}.pdf" : "Rekap_Kehadiran_Semua_Bulan_{$bulanAktif}.pdf";
                return response($r->body())
                    ->header('Content-Type', 'application/pdf')
                    ->header('Content-Disposition', 'attachment; filename="' . $namaFile . '"');
            }
            return back()->with('error', 'Gagal mengunduh PDF: ' . ($r->json()['message'] ?? ''));
        } catch (\Exception $e) {return back()->with('error', 'Server error.');}
    }

    public function downloadExcel(Request $request)
    {
        $token = Session::get('token');
        if (! $token) {
            return redirect()->route('login');
        }

        $kelasAktif = $request->get('kelas', '');
        $bulanAktif = $request->get('bulan', date('n')); // Ambil data bulan
        $taResolved = $this->resolveTahunAjaran($token, $request->get('id_tahun_ajaran'));

        if (! $taResolved['tahun_ajaran']) {
            return back()->with('error', 'Tahun ajaran tidak valid.');
        }

        try {
            $params = [
                'tahun_ajaran' => $taResolved['tahun_ajaran'],
                'semester'     => $taResolved['semester'],
            ];
            if ($kelasAktif) {
                $params['kelas'] = $kelasAktif;
            }
            if ($bulanAktif) {
                $params['bulan'] = $bulanAktif;
                $params['month'] = $bulanAktif;
            }

            $r = Http::withHeaders(['Cookie' => 'token=' . $token])->timeout(60)->get(env('API_BASE_URL') . '/export/absensi/excel', $params);

            if ($r->successful()) {
                $namaFile = $kelasAktif ? "Rekap_Kehadiran_Kelas_{$kelasAktif}_Bulan_{$bulanAktif}.xlsx" : "Rekap_Kehadiran_Semua_Bulan_{$bulanAktif}.xlsx";
                return response($r->body())
                    ->header('Content-Type', 'application/vnd.openxmlformats-officedocument.spreadsheetml.sheet')
                    ->header('Content-Disposition', 'attachment; filename="' . $namaFile . '"');
            }
            return back()->with('error', 'Gagal mengunduh Excel: ' . ($r->json()['message'] ?? ''));
        } catch (\Exception $e) {return back()->with('error', 'Server error.');}
    }
}