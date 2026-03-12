<?php
namespace App\Http\Controllers\Kepsek;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Session;

class MonitoringAbsensiController extends Controller
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

        $role       = $user['role'] ?? 'kepala_sekolah';
        $kelasAktif = $request->get('kelas', '');
        $page       = max(1, (int) $request->get('page', 1));
        $reqIdTa    = $request->get('id_tahun_ajaran');

        $absensi        = [];
        $pagination     = [];
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

            // 2. Ambil rekap kehadiran dengan filter TA + semester
            $params = ['page' => $page, 'limit' => 20];
            if ($taString) {
                $params['tahun_ajaran'] = $taString;
            }

            if ($semesterString) {
                $params['semester'] = $semesterString;
            }

            if ($kelasAktif) {
                $params['kelas'] = $kelasAktif;
            }

            $response = Http::withHeaders($this->headers())
                ->get($this->base() . '/rekap_kehadiran', $params);

            if ($response->successful()) {
                $json       = $response->json();
                $absensi    = $json['data'] ?? [];
                $pagination = $json['pagination'] ?? [];
            }

        } catch (\Exception $e) {
            \Log::error('MonitoringAbsensi Kepsek error: ' . $e->getMessage());
        }

        render:
        return view('kepsek.monitoring_absensi', compact(
            'absensi',
            'pagination',
            'role',
            'kelasAktif',
            'reqIdTa',
            'taString',
            'page'
        ));
    }
}
