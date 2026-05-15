<?php
namespace App\Http\Controllers\GuruBK;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Session;

class HomeVisitController extends Controller
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
        $user  = Session::get('user_data');
        $token = Session::get('token');
        if (! $user || ! $token) {
            return redirect()->route('login');
        }

        $role        = $user['role'] ?? 'guru_bk';
        $filterKelas = $request->query('kelas', '');
        $taResolved  = $this->resolveTahunAjaran($request->query('id_tahun_ajaran'));

        $visitSelesai = [];
        $visitPending = [];
        $kelasList    = [];

        try {
            $rKls = Http::withHeaders($this->headers())->get($this->base() . '/siswa/kelas');
            if ($rKls->successful()) {
                $kelasList = $rKls->json()['kelas_list'] ?? [];
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

        return view('guru_bk.home_visit', compact('visitSelesai', 'visitPending', 'role', 'kelasList', 'filterKelas'));
    }

    public function getSiswaByKelas(Request $request)
    {
        $taResolved = $this->resolveTahunAjaran($request->query('id_tahun_ajaran'));
        $params     = ['kelas' => $request->query('kelas'), 'limit' => 500];
        if ($taResolved['tahun_ajaran']) {
            $params['tahun_ajaran'] = $taResolved['tahun_ajaran'];
        }

        if ($taResolved['semester']) {
            $params['semester'] = $taResolved['semester'];
        }

        try {
            $r = Http::withHeaders($this->headers())->get($this->base() . '/siswa', $params);
            if ($r->successful()) {
                return response()->json($r->json()['data'] ?? []);
            }

        } catch (\Exception $e) {}
        return response()->json([]);
    }

    public function detail($id)
    {
        try {
            $rVis = Http::withHeaders($this->headers())->get($this->base() . '/home_visit/' . $id);
            if (! $rVis->successful()) {
                return response()->json(['error' => 'Gagal'], 500);
            }

            $visitData = $rVis->json();
            $rSis      = Http::withHeaders($this->headers())->get($this->base() . '/siswa/' . $visitData['id_siswa']);
            if ($rSis->successful()) {
                $siswaData               = $rSis->json();
                $visitData['nama_wali']  = $siswaData['nama_wali'] ?? $siswaData['nama_ayah'] ?? 'Tidak diketahui';
                $visitData['no_telepon'] = $siswaData['no_telepon'] ?? '';
                $visitData['alamat']     = $siswaData['alamat'] ?? 'Alamat belum diisi';
            }
            return response()->json($visitData);
        } catch (\Exception $e) {return response()->json(['error' => $e->getMessage()], 500);}
    }

    public function store(Request $request)
    {
        $token = Session::get('token');

        $response = Http::withHeaders(['Cookie' => 'token=' . $token])
            ->post(env('API_BASE_URL') . '/home_visit', [
                'id_siswa' => $request->id_siswa,
                'tanggal'  => $request->tanggal,
                'status'   => $request->status,
            ]);

        if ($response->successful()) {
            return back()->with('success', 'Jadwal kunjungan berhasil ditambahkan!');
        }
        return back()->with('error', 'Gagal menambahkan kunjungan.');
    }

    public function update(Request $request, $id)
    {
        $token = Session::get('token');

        $response = Http::withHeaders(['Cookie' => 'token=' . $token])
            ->patch(env('API_BASE_URL') . '/home_visit/' . $id, [
                'tanggal' => $request->tanggal,
                'status'  => $request->status,
            ]);

        if ($response->successful()) {
            return back()->with('success', 'Data kunjungan berhasil diupdate!');
        }
        return back()->with('error', 'Gagal mengupdate kunjungan.');
    }

    public function destroy($id)
    {
        $token = Session::get('token');

        $response = Http::withHeaders(['Cookie' => 'token=' . $token])
            ->delete(env('API_BASE_URL') . '/home_visit/' . $id);

        if ($response->successful()) {
            return back()->with('success', 'Data kunjungan berhasil dihapus!');
        }
        return back()->with('error', 'Gagal menghapus data.');
    }
}
