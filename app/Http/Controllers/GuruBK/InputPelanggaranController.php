<?php
namespace App\Http\Controllers\GuruBK;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Session;

class InputPelanggaranController extends Controller
{
    private function headers()
    {return ['Cookie' => 'token=' . Session::get('token')];}
    private function base()
    {return env('API_BASE_URL');}
    private function normalizeDate(?string $tanggal): ?string
    {return $tanggal ? substr($tanggal, 0, 10) : null;}

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

        $role  = $user['role'] ?? 'guru_bk';
        $page  = $request->get('page', 1);
        $kelas = $request->get('kelas', '');

        $taResolved = $this->resolveTahunAjaran($request->get('id_tahun_ajaran'));

        $pelanggaran = [];
        $jenisList   = [];
        $siswaList   = [];
        $pagination  = [];

        try {
            $params = ['page' => $page, 'limit' => 10];
            if ($kelas) {
                $params['kelas'] = $kelas;
            }

            if ($taResolved['tahun_ajaran']) {
                $params['tahun_ajaran'] = $taResolved['tahun_ajaran'];
            }

            if ($taResolved['semester']) {
                $params['semester'] = $taResolved['semester'];
            }

            $r1 = Http::withHeaders($this->headers())->get($this->base() . '/pelanggaran', $params);
            if ($r1->successful()) {
                $pelanggaran = $r1->json()['data'] ?? [];
                $pagination  = $r1->json()['pagination'] ?? [];
            }

            $r2 = Http::withHeaders($this->headers())->get($this->base() . '/jenis_pelanggaran');
            if ($r2->successful()) {
                $jenisList = $r2->json();
            }

            $paramsSiswa = ['limit' => 1000];
            if ($taResolved['tahun_ajaran']) {
                $paramsSiswa['tahun_ajaran'] = $taResolved['tahun_ajaran'];
            }

            if ($taResolved['semester']) {
                $paramsSiswa['semester'] = $taResolved['semester'];
            }

            $r3 = Http::withHeaders($this->headers())->get($this->base() . '/siswa', $paramsSiswa);
            if ($r3->successful()) {
                $siswaList = $r3->json()['data'] ?? $r3->json();
            }

        } catch (\Exception $e) {}

        return view('guru_bk.input_pelanggaran', compact('role', 'pelanggaran', 'jenisList', 'siswaList', 'pagination', 'kelas', 'page'));
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
