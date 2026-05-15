<?php
namespace App\Http\Controllers\GuruBK;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Session;

class PemantauanController extends Controller
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
        $user  = Session::get('user_data') ?? Session::get('user');
        $token = Session::get('token');
        if (! $user || ! $token) {
            return redirect()->route('login');
        }

        $role   = $user['role'] ?? 'guru_bk';
        $page   = (int) $request->get('page', 1);
        $search = $request->get('search', '');
        $kelas  = $request->get('kelas', '');

        $taResolved = $this->resolveTahunAjaran($request->get('id_tahun_ajaran'));

        $siswaList  = [];
        $pagination = [];

        try {
            $params = ['page' => $page, 'limit' => 10, 'min_poin' => 1];
            if ($search) {
                $params['search'] = $search;
            }

            if ($kelas) {
                $params['kelas'] = $kelas;
            }

            if ($taResolved['tahun_ajaran']) {
                $params['tahun_ajaran'] = $taResolved['tahun_ajaran'];
            }

            if ($taResolved['semester']) {
                $params['semester'] = $taResolved['semester'];
            }

            $r = Http::withHeaders($this->headers())->get($this->base() . '/pelanggaran/total', $params);
            if ($r->successful()) {
                $siswaList  = $r->json()['data'] ?? [];
                $pagination = $r->json()['pagination'] ?? [];
            }
        } catch (\Exception $e) {}

        return view('guru_bk.pemantauan', compact('role', 'siswaList', 'pagination', 'page', 'search', 'kelas'));
    }

    public function detail(Request $request, $id)
    {
        try {
            $taResolved = $this->resolveTahunAjaran($request->get('id_tahun_ajaran'));

            $nisn        = $request->get('nisn');
            $paramsSiswa = ['limit' => 10];
            if ($taResolved['tahun_ajaran']) {
                $paramsSiswa['tahun_ajaran'] = $taResolved['tahun_ajaran'];
            }

            if ($taResolved['semester']) {
                $paramsSiswa['semester'] = $taResolved['semester'];
            }

            if ($nisn) {
                $paramsSiswa['search'] = $nisn;
            }

            $rSiswa = Http::withHeaders($this->headers())->get($this->base() . '/siswa', $paramsSiswa);
            $siswa  = null;

            if ($rSiswa->successful()) {
                foreach (($rSiswa->json()['data'] ?? []) as $row) {
                    if ((string) $row['id'] === (string) $id) {$siswa = $row;
                        break;}
                }
            }

            $pelanggaran = [];
            $totalPoin   = 0;
            if ($siswa) {
                $paramsTotal = ['search' => $siswa['nisn'], 'limit' => 10];
                if ($taResolved['tahun_ajaran']) {
                    $paramsTotal['tahun_ajaran'] = $taResolved['tahun_ajaran'];
                }

                if ($taResolved['semester']) {
                    $paramsTotal['semester'] = $taResolved['semester'];
                }

                $rTotal = Http::withHeaders($this->headers())->get($this->base() . '/pelanggaran/total', $paramsTotal);
                if ($rTotal->successful()) {
                    foreach (($rTotal->json()['data'] ?? []) as $row) {
                        if ((string) $row['id'] === (string) $id) {
                            $totalPoin = $row['total_poin'] ?? 0;
                            $raw       = $row['riwayat_pelanggaran'] ?? [];
                            if (is_string($raw)) {
                                $raw = json_decode($raw, true) ?? [];
                            }

                            $pelanggaran = array_values(array_filter($raw));
                            break;
                        }
                    }
                }
            }

            return response()->json(['siswa' => $siswa, 'pelanggaran' => $pelanggaran, 'total_poin' => $totalPoin]);
        } catch (\Exception $e) {return response()->json(['message' => 'Server error'], 500);}
    }
}
