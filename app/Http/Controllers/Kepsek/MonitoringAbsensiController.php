<?php
namespace App\Http\Controllers\Kepsek;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Session;

class MonitoringAbsensiController extends Controller
{
    private function headers()
    {return ['Cookie' => 'token=' . Session::get('token')];}
    private function base()
    {return env('API_BASE_URL');}

    private function resolveTahunAjaran($reqIdTa)
    {
        try {
            $r = Http::withHeaders($this->headers())->get($this->base() . '/tahun_ajaran');
            if ($r->successful()) {
                $taList     = $r->json();
                $taTerpilih = $reqIdTa ? (collect($taList)->firstWhere('id', $reqIdTa) ?? collect($taList)->firstWhere('id', (int) $reqIdTa)) : collect($taList)->firstWhere('status', 'aktif');
                if (! $taTerpilih && count($taList) > 0) {
                    $taTerpilih = $taList[0];
                }

                return [
                    'id'           => $taTerpilih['id'] ?? null,
                    'tahun_ajaran' => $taTerpilih['tahun_ajaran'] ?? null,
                    'semester'     => $taTerpilih['semester'] ?? null,
                ];
            }
        } catch (\Exception $e) {}
        return ['id' => null, 'tahun_ajaran' => null, 'semester' => null];
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

        $taResolved = $this->resolveTahunAjaran($request->get('id_tahun_ajaran'));
        $reqIdTa    = $taResolved['id'];
        $taString   = $taResolved['tahun_ajaran'];

        $absensi    = [];
        $pagination = [];

        try {
            if (! $taString) {
                goto render;
            }

            $params = ['page' => $page, 'limit' => 20];
            if ($taResolved['tahun_ajaran']) {
                $params['tahun_ajaran'] = $taResolved['tahun_ajaran'];
            }

            if ($taResolved['semester']) {
                $params['semester'] = $taResolved['semester'];
            }

            if ($kelasAktif) {
                $params['kelas'] = $kelasAktif;
            }

            $r = Http::withHeaders($this->headers())->get($this->base() . '/rekap_kehadiran', $params);
            if ($r->successful()) {
                $absensi    = $r->json()['data'] ?? [];
                $pagination = $r->json()['pagination'] ?? [];
            }
        } catch (\Exception $e) {}

        render:
        return view('kepsek.monitoring_absensi', compact('absensi', 'pagination', 'role', 'kelasAktif', 'reqIdTa', 'taString', 'page'));
    }
}
