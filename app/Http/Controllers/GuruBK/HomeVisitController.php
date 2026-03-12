<?php
namespace App\Http\Controllers\GuruBK;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Session;

class HomeVisitController extends Controller
{
    public function index(Request $request)
    {
        $user  = Session::get('user_data');
        $token = Session::get('token');

        if (! $user || ! $token) {
            return redirect()->route('login');
        }

        $role         = $user['role'] ?? 'guru_bk';
        $visitSelesai = [];
        $visitPending = [];
        $kelasList    = [];

        $filterKelas = $request->query('kelas', '');
        $reqIdTa     = $request->query('id_tahun_ajaran'); // Tangkap filter tahun ajaran

        try {
            // 1. Resolve ID Tahun Ajaran jadi Teks (untuk diumpan ke API)
            $taString       = null;
            $semesterString = null;
            $rTa            = Http::withHeaders(['Cookie' => 'token=' . $token])->get(env('API_BASE_URL') . '/tahun_ajaran');

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

            // 2. Ambil Data Kelas untuk Dropdown Filter
            $kelasResponse = Http::withHeaders(['Cookie' => 'token=' . $token])
                ->get(env('API_BASE_URL') . '/siswa/kelas');

            if ($kelasResponse->successful()) {
                $kelasList = $kelasResponse->json()['kelas_list'] ?? [];
            }

            // 3. Ambil Data Home Visit (Disertai Filter TA)
            $allRows    = [];
            $page       = 1;
            $totalPages = 1;

            do {
                $params = [
                    'page'  => $page,
                    'limit' => 100,
                    'kelas' => $filterKelas,
                ];
                if ($taString) {
                    $params['tahun_ajaran'] = $taString;
                }

                if ($semesterString) {
                    $params['semester'] = $semesterString;
                }

                $response = Http::withHeaders([
                    'Cookie' => 'token=' . $token,
                ])->get(env('API_BASE_URL') . '/home_visit', $params);

                if (! $response->successful()) {
                    break;
                }

                $json       = $response->json();
                $rows       = $json['data'] ?? [];
                $totalPages = $json['pagination']['totalPages'] ?? 1;

                $allRows = array_merge($allRows, $rows);
                $page++;

            } while ($page <= $totalPages);

            // Pisahkan berdasarkan status
            foreach ($allRows as $visit) {
                $status = $visit['status'] ?? '';
                if ($status === 'Sudah Terlaksana') {
                    $visitSelesai[] = $visit;
                } elseif ($status === 'Rencana Kunjungan') {
                    $visitPending[] = $visit;
                }
            }

        } catch (\Exception $e) {
            \Log::error('GuruBK HomeVisit error: ' . $e->getMessage());
        }

        return view('guru_bk.home_visit', compact('visitSelesai', 'visitPending', 'role', 'kelasList', 'filterKelas'));
    }

    // Fungsi untuk AJAX get Siswa berdasarkan Kelas di form Tambah
    public function getSiswaByKelas(Request $request)
    {
        $token = Session::get('token');
        $kelas = $request->query('kelas');

        $response = Http::withHeaders(['Cookie' => 'token=' . $token])
            ->get(env('API_BASE_URL') . '/siswa', [
                'kelas' => $kelas,
                'limit' => 500, // Ambil banyak sekaligus
            ]);

        if ($response->successful()) {
            return response()->json($response->json()['data'] ?? []);
        }
        return response()->json([]);
    }

    public function detail($id)
    {
        $token = Session::get('token');

        try {
            // Ambil data visit
            $visitResp = Http::withHeaders(['Cookie' => 'token=' . $token])
                ->get(env('API_BASE_URL') . '/home_visit/' . $id);

            if (! $visitResp->successful()) {
                return response()->json(['error' => 'Gagal ambil data visit'], 500);
            }

            $visitData = $visitResp->json();
            $idSiswa   = $visitData['id_siswa'];

            // Ambil detail siswa untuk dapet nama wali, telepon, alamat
            $siswaResp = Http::withHeaders(['Cookie' => 'token=' . $token])
                ->get(env('API_BASE_URL') . '/siswa/' . $idSiswa);

            if ($siswaResp->successful()) {
                $siswaData = $siswaResp->json();
                // Gabungkan datanya
                $visitData['nama_wali']  = $siswaData['nama_wali'] ?? $siswaData['nama_ayah'] ?? 'Tidak diketahui';
                $visitData['no_telepon'] = $siswaData['no_telepon'] ?? '';
                $visitData['alamat']     = $siswaData['alamat'] ?? 'Alamat belum diisi';
            }

            return response()->json($visitData);

        } catch (\Exception $e) {
            return response()->json(['error' => $e->getMessage()], 500);
        }
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
