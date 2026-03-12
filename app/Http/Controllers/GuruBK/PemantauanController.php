<?php
namespace App\Http\Controllers\GuruBK;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Session;

class PemantauanController extends Controller
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

        $role    = $user['role'] ?? 'guru_bk';
        $page    = $request->get('page', 1);
        $search  = $request->get('search', '');
        $reqIdTa = $request->get('id_tahun_ajaran'); // Tangkap filter dari header

        $siswaList  = [];
        $pagination = [];

        try {
            // 1. Resolve Tahun Ajaran
            $taString       = null;
            $semesterString = null;

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
                if ($taTerpilih) {
                    $taString       = $taTerpilih['tahun_ajaran'];
                    $semesterString = $taTerpilih['semester'];
                }
            }

            // 2. Fetch data total poin beserta filter tahun ajaran dan semester
            $params = ['page' => $page, 'limit' => 10, 'min_poin' => 1];
            if ($search) {
                $params['search'] = $search;
            }

            if ($taString) {
                $params['tahun_ajaran'] = $taString;
            }

            if ($semesterString) {
                $params['semester'] = $semesterString;
            }

            $r = Http::withHeaders($this->headers())
                ->get($this->base() . '/pelanggaran/total', $params);

            if ($r->successful()) {
                $allData    = $r->json()['data'] ?? [];
                $pagination = $r->json()['pagination'] ?? [];

                // Hanya tampilkan siswa yang punya pelanggaran (total_poin > 0)
                $siswaList = array_values(array_filter($allData, fn($s) => ($s['total_poin'] ?? 0) > 0));

                // Sesuaikan total di pagination dengan jumlah yang sudah difilter
                $pagination['total'] = count($siswaList);
            }
        } catch (\Exception $e) {
            \Log::error('Pemantauan index error: ' . $e->getMessage());
        }

        return view('guru_bk.pemantauan', compact(
            'role', 'siswaList', 'pagination', 'page', 'search'
        ));
    }

    public function detail($id)
    {
        try {
            // 1. Ambil data siswa lengkap dari /siswa/:id
            $rSiswa = Http::withHeaders($this->headers())
                ->get($this->base() . '/siswa/' . $id);

            $siswa = $rSiswa->successful() ? $rSiswa->json() : null;

            // 2. Ambil riwayat pelanggaran dari /pelanggaran/total?search=<nama_siswa>
            $pelanggaran = [];
            $totalPoin   = 0;

            if ($siswa) {
                $rTotal = Http::withHeaders($this->headers())
                    ->get($this->base() . '/pelanggaran/total', [
                        'search' => $siswa['nama'] ?? '',
                        'limit'  => 1000,
                    ]);

                if ($rTotal->successful()) {
                    $rows = $rTotal->json()['data'] ?? [];

                    // Cari baris yang id-nya cocok dengan $id
                    foreach ($rows as $row) {
                        if ((string) $row['id'] === (string) $id) {
                            $totalPoin = $row['total_poin'] ?? 0;

                            // riwayat_pelanggaran adalah JSON string dari JSON_ARRAYAGG
                            $raw = $row['riwayat_pelanggaran'] ?? [];
                            if (is_string($raw)) {
                                $raw = json_decode($raw, true) ?? [];
                            }
                            // Buang entry null (dari CASE WHEN di query)
                            $pelanggaran = array_values(array_filter($raw));
                            break;
                        }
                    }
                }
            }

            return response()->json([
                'siswa'       => $siswa,
                'pelanggaran' => $pelanggaran,
                'total_poin'  => $totalPoin,
            ]);
        } catch (\Exception $e) {
            return response()->json(['message' => 'Server error'], 500);
        }
    }
}
