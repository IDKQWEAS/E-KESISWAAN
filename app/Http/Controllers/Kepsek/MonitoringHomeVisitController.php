<?php
namespace App\Http\Controllers\Kepsek;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Session;

class MonitoringHomeVisitController extends Controller
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
        $filterKelas = $request->query('kelas', '');

        $taResolved = $this->resolveTahunAjaran($request->get('id_tahun_ajaran'));
        $reqIdTa    = $taResolved['id'];
        $taString   = $taResolved['tahun_ajaran'];

        $visitSelesai = [];
        $visitPending = [];

        try {
            if (! $taString) {
                goto render;
            }

            $allRows    = [];
            $page       = 1;
            $totalPages = 1;
            do {
                $params = ['page' => $page, 'limit' => 100, 'kelas' => $filterKelas];
                if ($taResolved['tahun_ajaran']) {
                    $params['tahun_ajaran'] = $taResolved['tahun_ajaran'];
                }

                if ($taResolved['semester']) {
                    $params['semester'] = $taResolved['semester'];
                }

                $r = Http::withHeaders($this->headers())->get($this->base() . '/home_visit', $params);
                if (! $r->successful()) {
                    break;
                }

                $rows       = $r->json()['data'] ?? [];
                $totalPages = $r->json()['pagination']['totalPages'] ?? 1;
                $allRows    = array_merge($allRows, $rows);
                $page++;
            } while ($page <= $totalPages);

            foreach ($allRows as $visit) {
                if (($visit['status'] ?? '') === 'Sudah Terlaksana') {
                    $visitSelesai[] = $visit;
                } elseif (($visit['status'] ?? '') === 'Rencana Kunjungan') {
                    $visitPending[] = $visit;
                }

            }
        } catch (\Exception $e) {}

        render:
        return view('kepsek.monitoring_visit', compact('visitSelesai', 'visitPending', 'role', 'reqIdTa', 'taString', 'filterKelas'));
    }

    public function detail(Request $request, $id)
    {
        $token = Session::get('token');
        if (! $token) {
            return response()->json(['error' => 'Unauthorized'], 401);
        }

        try {
            $r = Http::withHeaders($this->headers())->get($this->base() . '/home_visit/' . $id);
            if ($r->successful()) {
                return response()->json($r->json());
            }

            return response()->json(['error' => 'Gagal ambil data'], 500);
        } catch (\Exception $e) {return response()->json(['error' => $e->getMessage()], 500);}
    }
}
