<?php
namespace App\Http\Controllers\GuruBK;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Session;

class RekapKehadiranController extends Controller
{
    // Fungsi helper: merubah ID dari dropdown jadi array [tahun_ajaran, semester]
    private function resolveTahunAjaran($token, $reqIdTa)
    {
        try {
            $r = Http::withHeaders(['Cookie' => 'token=' . $token])
                ->get(env('API_BASE_URL') . '/tahun_ajaran');

            if ($r->successful()) {
                $taList     = $r->json();
                $taTerpilih = null;

                if ($reqIdTa) {
                    $taTerpilih = collect($taList)->firstWhere('id', $reqIdTa) ?? collect($taList)->firstWhere('id', (int) $reqIdTa);
                }
                if (! $taTerpilih) {
                    $taTerpilih = collect($taList)->firstWhere('status', 'aktif');
                }

                return $taTerpilih;
            }
        } catch (\Exception $e) {
            \Log::error('Fetch TA Error: ' . $e->getMessage());
        }
        return null;
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
        $reqIdTa    = $request->get('id_tahun_ajaran'); // Tangkap filter dari header

        $absensi    = [];
        $pagination = [];

        try {
            $taResolved     = $this->resolveTahunAjaran($token, $reqIdTa);
            $tahunAjaranStr = $taResolved['tahun_ajaran'] ?? null;
            $semesterStr    = $taResolved['semester'] ?? null;

            $params = [
                'page'  => $request->get('page', 1),
                'limit' => 20,
            ];

            if ($kelasAktif) {
                $params['kelas'] = $kelasAktif;
            }

            if ($tahunAjaranStr) {
                $params['tahun_ajaran'] = $tahunAjaranStr;
            }

            if ($semesterStr) {
                $params['semester'] = $semesterStr;
            }

            $response = Http::withHeaders([
                'Cookie' => 'token=' . $token,
            ])->get(env('API_BASE_URL') . '/rekap_kehadiran', $params);

            if ($response->successful()) {
                $json       = $response->json();
                $absensi    = $json['data'] ?? [];
                $pagination = $json['pagination'] ?? [];
            }

        } catch (\Exception $e) {
            \Log::error('GuruBK RekapKehadiran error: ' . $e->getMessage());
        }

        return view('guru_bk.rekap_kehadiran', compact(
            'absensi',
            'pagination',
            'role',
            'kelasAktif'
        ));
    }

    public function downloadPdf(Request $request)
    {
        $token = Session::get('token');
        if (! $token) {
            return redirect()->route('login');
        }

        $kelasAktif = $request->get('kelas', '');
        $reqIdTa    = $request->get('id_tahun_ajaran');

        $taResolved     = $this->resolveTahunAjaran($token, $reqIdTa);
        $tahunAjaranStr = $taResolved['tahun_ajaran'] ?? null;
        $semesterStr    = $taResolved['semester'] ?? null;

        try {
            $params = [];
            if ($kelasAktif) {
                $params['kelas'] = $kelasAktif;
            }

            if ($tahunAjaranStr) {
                $params['tahun_ajaran'] = $tahunAjaranStr;
            }

            if ($semesterStr) {
                $params['semester'] = $semesterStr;
            }

            $response = Http::withHeaders(['Cookie' => 'token=' . $token])
                ->timeout(60)
                ->get(env('API_BASE_URL') . '/export/absensi/pdf', $params);

            if ($response->successful()) {
                $namaFile = $kelasAktif ? "Rekap_Kehadiran_Kelas_{$kelasAktif}.pdf" : "Rekap_Kehadiran_Semua.pdf";
                return response($response->body())
                    ->header('Content-Type', 'application/pdf')
                    ->header('Content-Disposition', 'attachment; filename="' . $namaFile . '"');
            }

            $msg = $response->json()['message'] ?? '';
            return back()->with('error', 'Gagal mengunduh PDF. ' . $msg);

        } catch (\Exception $e) {
            \Log::error('Download PDF error: ' . $e->getMessage());
            return back()->with('error', 'Terjadi kesalahan sistem: ' . $e->getMessage());
        }
    }

    public function downloadExcel(Request $request)
    {
        $token = Session::get('token');
        if (! $token) {
            return redirect()->route('login');
        }

        $kelasAktif = $request->get('kelas', '');
        $reqIdTa    = $request->get('id_tahun_ajaran');

        $taResolved     = $this->resolveTahunAjaran($token, $reqIdTa);
        $tahunAjaranStr = $taResolved['tahun_ajaran'] ?? null;
        $semesterStr    = $taResolved['semester'] ?? null;

        try {
            $params = [];
            if ($kelasAktif) {
                $params['kelas'] = $kelasAktif;
            }

            if ($tahunAjaranStr) {
                $params['tahun_ajaran'] = $tahunAjaranStr;
            }

            if ($semesterStr) {
                $params['semester'] = $semesterStr;
            }

            $response = Http::withHeaders(['Cookie' => 'token=' . $token])
                ->timeout(60)
                ->get(env('API_BASE_URL') . '/export/absensi/excel', $params);

            if ($response->successful()) {
                $namaFile = $kelasAktif ? "Rekap_Kehadiran_Kelas_{$kelasAktif}.xlsx" : "Rekap_Kehadiran_Semua.xlsx";
                return response($response->body())
                    ->header('Content-Type', 'application/vnd.openxmlformats-officedocument.spreadsheetml.sheet')
                    ->header('Content-Disposition', 'attachment; filename="' . $namaFile . '"');
            }

            $msg = $response->json()['message'] ?? '';
            return back()->with('error', 'Gagal mengunduh Excel. ' . $msg);

        } catch (\Exception $e) {
            \Log::error('Download Excel error: ' . $e->getMessage());
            return back()->with('error', 'Terjadi kesalahan sistem: ' . $e->getMessage());
        }
    }
}
