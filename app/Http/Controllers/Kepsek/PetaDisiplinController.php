<?php
namespace App\Http\Controllers\Kepsek;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Session;

class PetaDisiplinController extends Controller
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

        $role        = $user['role'] ?? 'kepala_sekolah';
        $filterKelas = $request->get('kelas', '');
        $page        = max(1, (int) $request->get('page', 1));
        $limit       = 20;

        $taResolved = $this->resolveTahunAjaran($request->get('id_tahun_ajaran'));
        $reqIdTa    = $taResolved['id'];
        $taString   = $taResolved['tahun_ajaran'];

        $dataPelanggaran = [];
        $pagination      = ['total' => 0, 'page' => $page, 'limit' => $limit, 'totalPages' => 1];

        try {
            if (! $taString) {
                goto render;
            }

            $params = ['page' => $page, 'limit' => $limit, 'min_poin' => 1];
            if ($taResolved['tahun_ajaran']) {
                $params['tahun_ajaran'] = $taResolved['tahun_ajaran'];
            }

            if ($taResolved['semester']) {
                $params['semester'] = $taResolved['semester'];
            }

            if ($filterKelas) {
                $params['kelas'] = $filterKelas;
            }

            $r = Http::withHeaders($this->headers())->get($this->base() . '/pelanggaran/total', $params);
            if ($r->successful()) {
                $rows       = $r->json()['data'] ?? [];
                $pagination = $r->json()['pagination'] ?? $pagination;

                foreach ($rows as &$siswa) {
                    if (isset($siswa['riwayat_pelanggaran']) && is_string($siswa['riwayat_pelanggaran'])) {
                        $siswa['riwayat_pelanggaran'] = json_decode($siswa['riwayat_pelanggaran'], true) ?? [];
                    }
                    if (! empty($siswa['riwayat_pelanggaran'])) {
                        $siswa['riwayat_pelanggaran'] = array_values(array_filter($siswa['riwayat_pelanggaran']));
                        usort($siswa['riwayat_pelanggaran'], fn($a, $b) => strcmp($b['tanggal'] ?? '', $a['tanggal'] ?? ''));
                    }
                }
                unset($siswa);

                $dataPelanggaran = $rows;
            }
        } catch (\Exception $e) {}

        render:
        return view('kepsek.peta_disiplin', compact('dataPelanggaran', 'pagination', 'role', 'filterKelas', 'reqIdTa', 'taString', 'page', 'limit'));
    }
}
