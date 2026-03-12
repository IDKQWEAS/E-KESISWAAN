<?php
namespace App\Http\Controllers\GuruBK;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Session;

class InputPelanggaranController extends Controller
{
    private function headers()
    {
        return ['Cookie' => 'token=' . Session::get('token')];
    }

    private function base()
    {
        return env('API_BASE_URL');
    }

    private function normalizeDate(?string $tanggal): ?string
    {
        if (! $tanggal) {
            return null;
        }
        return substr($tanggal, 0, 10);
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
        $kelas   = $request->get('kelas', '');
        $reqIdTa = $request->get('id_tahun_ajaran'); // Tangkap id_tahun_ajaran dari dropdown

        $pelanggaran = [];
        $jenisList   = [];
        $siswaList   = [];
        $pagination  = [];

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

            // 2. Ambil data Pelanggaran dengan filter TA
            $params = ['page' => $page, 'limit' => 10];
            if ($kelas) {
                $params['kelas'] = $kelas;
            }

            if ($taString) {
                $params['tahun_ajaran'] = $taString;
            }

            if ($semesterString) {
                $params['semester'] = $semesterString;
            }

            $r = Http::withHeaders($this->headers())
                ->get($this->base() . '/pelanggaran', $params);

            if ($r->successful()) {
                $pelanggaran = $r->json()['data'] ?? [];
                $pagination  = $r->json()['pagination'] ?? [];
            }

            // 3. Ambil data Jenis Pelanggaran
            $r = Http::withHeaders($this->headers())
                ->get($this->base() . '/jenis_pelanggaran');

            if ($r->successful()) {
                $jenisList = $r->json();
            }

            // 4. Ambil data Siswa dengan filter TA (agar dropdown hanya berisi siswa tahun terkait)
            $paramsSiswa = ['limit' => 1000];
            if ($taString) {
                $paramsSiswa['tahun_ajaran'] = $taString;
            }

            if ($semesterString) {
                $paramsSiswa['semester'] = $semesterString;
            }

            $r = Http::withHeaders($this->headers())
                ->get($this->base() . '/siswa', $paramsSiswa);

            if ($r->successful()) {
                $siswaList = $r->json()['data'] ?? $r->json();
            }

        } catch (\Exception $e) {
            \Log::error('InputPelanggaran index error: ' . $e->getMessage());
        }

        return view('guru_bk.input_pelanggaran', compact(
            'role', 'pelanggaran', 'jenisList', 'siswaList',
            'pagination', 'kelas', 'page'
        ));
    }

    public function store(Request $request)
    {
        try {
            $r = Http::withHeaders($this->headers())
                ->post($this->base() . '/pelanggaran', [
                    'id_siswa'             => $request->id_siswa,
                    'id_jenis_pelanggaran' => $request->id_jenis_pelanggaran,
                    'tanggal'              => $this->normalizeDate($request->tanggal),
                    'keterangan'           => $request->keterangan,
                ]);

            if ($r->successful()) {
                return response()->json(['success' => true, 'message' => 'Pelanggaran berhasil dicatat.']);
            }
            return response()->json(['success' => false, 'message' => $r->json()['message'] ?? 'Gagal menyimpan.']);
        } catch (\Exception $e) {
            return response()->json(['success' => false, 'message' => 'Terjadi kesalahan server.']);
        }
    }

    public function update(Request $request, $id)
    {
        try {
            $payload = [
                'tanggal'    => $this->normalizeDate($request->tanggal),
                'keterangan' => $request->keterangan,
            ];

            if ($request->filled('id_jenis_pelanggaran')) {
                $payload['id_jenis_pelanggaran'] = $request->id_jenis_pelanggaran;
            }

            $r = Http::withHeaders($this->headers())
                ->patch($this->base() . '/pelanggaran/' . $id, $payload);

            if ($r->successful()) {
                return response()->json(['success' => true, 'message' => 'Pelanggaran berhasil diperbarui.']);
            }
            return response()->json(['success' => false, 'message' => $r->json()['message'] ?? 'Gagal memperbarui.']);
        } catch (\Exception $e) {
            return response()->json(['success' => false, 'message' => 'Terjadi kesalahan server.']);
        }
    }

    public function destroy($id)
    {
        try {
            $r = Http::withHeaders($this->headers())
                ->delete($this->base() . '/pelanggaran/' . $id);

            if ($r->successful()) {
                return response()->json(['success' => true, 'message' => 'Pelanggaran berhasil dihapus.']);
            }
            return response()->json(['success' => false, 'message' => 'Gagal menghapus.']);
        } catch (\Exception $e) {
            return response()->json(['success' => false, 'message' => 'Terjadi kesalahan server.']);
        }
    }

    public function storeJenis(Request $request)
    {
        try {
            $r = Http::withHeaders($this->headers())
                ->post($this->base() . '/jenis_pelanggaran', [
                    'pelanggaran' => $request->pelanggaran,
                    'poin'        => $request->poin,
                ]);

            if ($r->successful()) {
                return response()->json(['success' => true, 'message' => 'Jenis pelanggaran berhasil ditambahkan.']);
            }
            return response()->json(['success' => false, 'message' => $r->json()['message'] ?? 'Gagal menyimpan.']);
        } catch (\Exception $e) {
            return response()->json(['success' => false, 'message' => 'Terjadi kesalahan server.']);
        }
    }

    public function updateJenis(Request $request, $id)
    {
        try {
            $r = Http::withHeaders($this->headers())
                ->patch($this->base() . '/jenis_pelanggaran/' . $id, [
                    'pelanggaran' => $request->pelanggaran,
                    'poin'        => $request->poin,
                ]);

            if ($r->successful()) {
                return response()->json(['success' => true, 'message' => 'Jenis pelanggaran berhasil diperbarui.']);
            }
            return response()->json(['success' => false, 'message' => $r->json()['message'] ?? 'Gagal memperbarui.']);
        } catch (\Exception $e) {
            return response()->json(['success' => false, 'message' => 'Terjadi kesalahan server.']);
        }
    }

    public function destroyJenis($id)
    {
        try {
            $r = Http::withHeaders($this->headers())
                ->delete($this->base() . '/jenis_pelanggaran/' . $id);

            if ($r->successful()) {
                return response()->json(['success' => true, 'message' => 'Jenis pelanggaran berhasil dihapus.']);
            }
            return response()->json(['success' => false, 'message' => 'Gagal menghapus.']);
        } catch (\Exception $e) {
            return response()->json(['success' => false, 'message' => 'Terjadi kesalahan server.']);
        }
    }
}
