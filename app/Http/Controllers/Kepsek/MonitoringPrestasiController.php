<?php
namespace App\Http\Controllers\Kepsek;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Session;

class MonitoringPrestasiController extends Controller
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

        $role           = $user['role'] ?? 'kepala_sekolah';
        $filterKategori = $request->get('kategori');
        $filterTingkat  = $request->get('tingkat');
        $page           = max(1, (int) $request->get('page', 1));
        $limit          = 12;

        $taResolved = $this->resolveTahunAjaran($request->get('id_tahun_ajaran'));
        $reqIdTa    = $taResolved['id'];
        $taString   = $taResolved['tahun_ajaran'];

        $prestasi   = [];
        $pagination = ['total' => 0, 'page' => $page, 'limit' => $limit, 'totalPages' => 1];

        try {
            if (! $taString) {
                goto render;
            }

            $params = ['page' => $page, 'limit' => $limit];
            if ($taResolved['tahun_ajaran']) {
                $params['tahun_ajaran'] = $taResolved['tahun_ajaran'];
            }

            if ($taResolved['semester']) {
                $params['semester'] = $taResolved['semester'];
            }

            $r = Http::withHeaders($this->headers())->get($this->base() . '/prestasi', $params);
            if ($r->successful()) {
                $rows       = $r->json()['data'] ?? [];
                $pagination = $r->json()['pagination'] ?? $pagination;

                if ($filterKategori) {
                    $rows = array_values(array_filter($rows, fn($i) => ($i['kategori'] ?? '') === $filterKategori));
                }

                if ($filterTingkat) {
                    $rows = array_values(array_filter($rows, fn($i) => ($i['tingkat'] ?? '') === $filterTingkat));
                }

                $prestasi = $rows;
            }
        } catch (\Exception $e) {}

        render:
        return view('kepsek.monitoring_prestasi', compact('prestasi', 'pagination', 'role', 'filterKategori', 'filterTingkat', 'reqIdTa', 'taString', 'page', 'limit'));
    }
}
