<?php
namespace App\Http\Controllers\Kepsek;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Session;

class MonitoringHomeVisitController extends Controller
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

        $role    = $user['role'] ?? 'kepala_sekolah';
        $reqIdTa = $request->get('id_tahun_ajaran');

        $visitSelesai   = [];
        $visitPending   = [];
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

            // 2. Ambil semua home visit sekaligus dengan limit besar
            //    (data home visit relatif sedikit, tidak perlu pagination di view)
            $params = ['page' => 1, 'limit' => 200];
            if ($taString) {
                $params['tahun_ajaran'] = $taString;
            }

            if ($semesterString) {
                $params['semester'] = $semesterString;
            }

            $response = Http::withHeaders($this->headers())
                ->get($this->base() . '/home_visit', $params);

            if ($response->successful()) {
                $rows = $response->json()['data'] ?? [];

                foreach ($rows as $visit) {
                    $status = $visit['status'] ?? '';
                    if ($status === 'Sudah Terlaksana') {
                        $visitSelesai[] = $visit;
                    } elseif ($status === 'Rencana Kunjungan') {
                        $visitPending[] = $visit;
                    }
                }
            }

        } catch (\Exception $e) {
            \Log::error('MonitoringHomeVisit Kepsek error: ' . $e->getMessage());
        }

        render:
        return view('kepsek.monitoring_visit', compact(
            'visitSelesai',
            'visitPending',
            'role',
            'reqIdTa',
            'taString'
        ));
    }

    public function detail(Request $request, $id)
    {
        $token = Session::get('token');

        if (! $token) {
            return response()->json(['error' => 'Unauthorized'], 401);
        }

        try {
            $response = Http::withHeaders($this->headers())
                ->get($this->base() . '/home_visit/' . $id);

            if ($response->successful()) {
                return response()->json($response->json());
            }

            return response()->json(['error' => 'Gagal ambil data'], 500);
        } catch (\Exception $e) {
            return response()->json(['error' => $e->getMessage()], 500);
        }
    }
}
