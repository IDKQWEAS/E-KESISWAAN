<?php
namespace App\Http\Controllers\GuruBK;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Session;

class BiodataController extends Controller
{
    private function headers(): array
    {
        return ['Cookie' => 'token=' . Session::get('token')];
    }

    private function base(): string
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

        $role       = $user['role'] ?? 'guru_bk';
        $kelasAktif = $request->get('kelas', '');
        $search     = $request->get('search', '');
        $page       = $request->get('page', 1);
        $reqIdTa    = $request->get('id_tahun_ajaran'); // Tangkap filter dari header

        $siswa           = [];
        $pagination      = [];
        $tahunAjaranList = [];
        $idTahunAjaran   = null;
        $taString        = null;
        $semesterString  = null;

        $kelasList = [
            '7A', '7B', '7C', '7D', '7E', '7F', '7G',
            '8A', '8B', '8C', '8D', '8E', '8F', '8G',
            '9A', '9B', '9C', '9D', '9E', '9F', '9G',
        ];

        try {
            // 1. Ambil list tahun ajaran untuk menterjemahkan ID menjadi String
            $r = Http::withHeaders($this->headers())->get($this->base() . '/tahun_ajaran');
            if ($r->successful()) {
                $tahunAjaranList = $r->json();

                $taTerpilih = null;
                // Jika ada ID dari parameter URL (pilihan dropdown)
                if ($reqIdTa) {
                    $taTerpilih = collect($tahunAjaranList)->firstWhere('id', $reqIdTa) ?? collect($tahunAjaranList)->firstWhere('id', (int) $reqIdTa);
                }

                // Jika tidak ada, default ke tahun ajaran aktif
                if (! $taTerpilih) {
                    $taTerpilih = collect($tahunAjaranList)->firstWhere('status', 'aktif');
                }

                // Fallback jika belum ada yang diset aktif
                if (! $taTerpilih && count($tahunAjaranList) > 0) {
                    $taTerpilih = $tahunAjaranList[0];
                }

                if ($taTerpilih) {
                    $idTahunAjaran  = $taTerpilih['id'];
                    $taString       = $taTerpilih['tahun_ajaran'];
                    $semesterString = $taTerpilih['semester'];
                }
            }

            // 2. Ambil data siswa dengan menyisipkan filter Tahun Ajaran & Semester
            $params = ['page' => $page, 'limit' => 10];
            if ($kelasAktif) {
                $params['kelas'] = $kelasAktif;
            }

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
                ->get($this->base() . '/siswa', $params);

            if ($r->successful()) {
                $json       = $r->json();
                $siswa      = $json['data'] ?? [];
                $pagination = $json['pagination'] ?? [];
            }

        } catch (\Exception $e) {
            \Log::error('Biodata index error: ' . $e->getMessage());
        }

        return view('guru_bk.biodata', compact(
            'role', 'siswa', 'pagination',
            'kelasAktif', 'search', 'kelasList',
            'tahunAjaranList', 'idTahunAjaran'
        ));
    }

    public function store(Request $request)
    {
        $request->validate([
            'id_tahun_ajaran' => 'required',
            'nama'            => 'required|string',
            'nis'             => 'required|string',
            'nisn'            => 'required|string',
            'kelas'           => 'required|string',
            'jenis_kelamin'   => 'required|in:L,P',
        ]);

        try {
            $parts = [
                ['name' => 'id_tahun_ajaran', 'contents' => (string) $request->id_tahun_ajaran],
                ['name' => 'nama', 'contents' => $request->nama],
                ['name' => 'nis', 'contents' => $request->nis],
                ['name' => 'nisn', 'contents' => $request->nisn],
                ['name' => 'kelas', 'contents' => $request->kelas],
                ['name' => 'jenis_kelamin', 'contents' => $request->jenis_kelamin],
                ['name' => 'tempat_lahir', 'contents' => $request->tempat_lahir ?? ''],
                ['name' => 'tanggal_lahir', 'contents' => $request->tanggal_lahir ?? ''],
                ['name' => 'alamat', 'contents' => $request->alamat ?? ''],
                ['name' => 'nama_ayah', 'contents' => $request->nama_ayah ?? ''],
                ['name' => 'pekerjaan_ayah', 'contents' => $request->pekerjaan_ayah ?? ''],
                ['name' => 'nama_ibu', 'contents' => $request->nama_ibu ?? ''],
                ['name' => 'pekerjaan_ibu', 'contents' => $request->pekerjaan_ibu ?? ''],
                ['name' => 'nama_wali', 'contents' => $request->nama_wali ?? ''],
                ['name' => 'pekerjaan_wali', 'contents' => $request->pekerjaan_wali ?? ''],
                ['name' => 'no_telepon', 'contents' => $request->no_telepon ?? ''],
                ['name' => 'penghasilan_orang_tua', 'contents' => (string) ($request->penghasilan_orang_tua ?? 0)],
            ];

            if ($request->hasFile('gambar')) {
                $file    = $request->file('gambar');
                $parts[] = [
                    'name'     => 'gambar',
                    'contents' => fopen($file->getRealPath(), 'r'),
                    'filename' => $file->getClientOriginalName(),
                ];
            }

            $response = Http::withHeaders($this->headers())
                ->asMultipart()
                ->post($this->base() . '/siswa', $parts);

            if ($response->successful()) {
                return redirect()->route('bk.biodata')
                    ->with('success', 'Biodata siswa berhasil ditambahkan.');
            }

            $msg = $response->json()['message'] ?? $response->body();
            return redirect()->back()->with('error', 'Gagal: ' . $msg)->withInput();

        } catch (\Exception $e) {
            \Log::error('Biodata store error: ' . $e->getMessage());
            return redirect()->back()->with('error', 'Server error.')->withInput();
        }
    }

    public function update(Request $request, $id)
    {
        try {
            $parts = [
                ['name' => 'nama', 'contents' => $request->nama ?? ''],
                ['name' => 'kelas', 'contents' => $request->kelas ?? ''],
                ['name' => 'jenis_kelamin', 'contents' => $request->jenis_kelamin ?? ''],
                ['name' => 'tempat_lahir', 'contents' => $request->tempat_lahir ?? ''],
                ['name' => 'tanggal_lahir', 'contents' => $request->tanggal_lahir ?? ''],
                ['name' => 'alamat', 'contents' => $request->alamat ?? ''],
                ['name' => 'nama_ayah', 'contents' => $request->nama_ayah ?? ''],
                ['name' => 'pekerjaan_ayah', 'contents' => $request->pekerjaan_ayah ?? ''],
                ['name' => 'nama_ibu', 'contents' => $request->nama_ibu ?? ''],
                ['name' => 'pekerjaan_ibu', 'contents' => $request->pekerjaan_ibu ?? ''],
                ['name' => 'nama_wali', 'contents' => $request->nama_wali ?? ''],
                ['name' => 'pekerjaan_wali', 'contents' => $request->pekerjaan_wali ?? ''],
                ['name' => 'no_telepon', 'contents' => $request->no_telepon ?? ''],
                ['name' => 'penghasilan_orang_tua', 'contents' => (string) ($request->penghasilan_orang_tua ?? 0)],
            ];

            if ($request->hasFile('gambar')) {
                $file    = $request->file('gambar');
                $parts[] = [
                    'name'     => 'gambar',
                    'contents' => fopen($file->getRealPath(), 'r'),
                    'filename' => $file->getClientOriginalName(),
                ];
            }

            $response = Http::withHeaders($this->headers())
                ->asMultipart()
                ->patch($this->base() . '/siswa/' . $id, $parts);

            if ($response->successful()) {
                return redirect()->route('bk.biodata')
                    ->with('success', 'Biodata berhasil diperbarui.');
            }

            $msg = $response->json()['message'] ?? $response->body();
            return redirect()->back()->with('error', 'Gagal update: ' . $msg);

        } catch (\Exception $e) {
            \Log::error('Biodata update error: ' . $e->getMessage());
            return redirect()->back()->with('error', 'Server error.');
        }
    }

    public function destroy($id)
    {
        try {
            $response = Http::withHeaders($this->headers())
                ->delete($this->base() . '/siswa/' . $id);

            if ($response->successful()) {
                return redirect()->route('bk.biodata')
                    ->with('success', 'Data siswa berhasil dihapus.');
            }

            return redirect()->back()->with('error', 'Gagal menghapus data.');

        } catch (\Exception $e) {
            \Log::error('Biodata destroy error: ' . $e->getMessage());
            return redirect()->back()->with('error', 'Server error.');
        }
    }

    public function downloadTemplate()
    {
        $token = Session::get('token');
        try {
            $response = Http::withHeaders(['Cookie' => 'token=' . $token])
                ->timeout(30)
                ->get($this->base() . '/export/siswa/excel-template');

            if ($response->successful()) {
                return response($response->body())
                    ->header('Content-Type', 'application/vnd.openxmlformats-officedocument.spreadsheetml.sheet')
                    ->header('Content-Disposition', 'attachment; filename="template_data_siswa.xlsx"');
            }

            return back()->with('error', 'Gagal mengunduh template.');
        } catch (\Exception $e) {
            return back()->with('error', 'Server error: ' . $e->getMessage());
        }
    }

    public function downloadExcel(Request $request)
    {
        $token = Session::get('token');
        try {
            $tahunAjaranStr = $request->get('tahun_ajaran');
            $kelas          = $request->get('kelas', ''); // filter kelas dari halaman

            if (! $tahunAjaranStr) {
                $r = Http::withHeaders(['Cookie' => 'token=' . $token])
                    ->get($this->base() . '/tahun_ajaran');

                if ($r->successful()) {
                    $taList = $r->json();
                    foreach ($taList as $ta) {
                        if (($ta['status'] ?? '') === 'aktif') {
                            $tahunAjaranStr = $ta['tahun_ajaran'];
                            break;
                        }
                    }
                    if (! $tahunAjaranStr && count($taList) > 0) {
                        $tahunAjaranStr = $taList[0]['tahun_ajaran'] ?? null;
                    }
                }
            }

            if (! $tahunAjaranStr) {
                return back()->with('error', 'Data tahun ajaran aktif tidak ditemukan di server.');
            }

            $params = ['tahun_ajaran' => $tahunAjaranStr];
            if ($kelas) {
                $params['kelas'] = $kelas;
            }

            $filename = $kelas
                ? "data_siswa_kelas_{$kelas}.xlsx"
                : "data_siswa_semua.xlsx";

            $response = Http::withHeaders(['Cookie' => 'token=' . $token])
                ->timeout(30)
                ->get($this->base() . '/export/siswa/excel', $params);

            if ($response->successful()) {
                return response($response->body())
                    ->header('Content-Type', 'application/vnd.openxmlformats-officedocument.spreadsheetml.sheet')
                    ->header('Content-Disposition', "attachment; filename=\"{$filename}\"");
            }

            $msg = $response->json()['message'] ?? 'Gagal dari sisi server.';
            return back()->with('error', 'Gagal mengunduh Excel: ' . $msg);

        } catch (\Exception $e) {
            \Log::error('Download Excel Siswa Error: ' . $e->getMessage());
            return back()->with('error', 'Server error: ' . $e->getMessage());
        }
    }

    public function importExcel(Request $request)
    {
        $request->validate(['file' => 'required|file|mimes:xlsx,xls']);

        $token = Session::get('token');
        try {
            $file = $request->file('file');

            $response = Http::withHeaders(['Cookie' => 'token=' . $token])
                ->attach('file', fopen($file->getRealPath(), 'r'), $file->getClientOriginalName())
                ->post($this->base() . '/import/siswa/excel');

            if ($response->successful()) {
                $total = $response->json()['total_inserted'] ?? 0;
                return redirect()->route('bk.biodata')
                    ->with('success', "Import berhasil! {$total} data siswa ditambahkan.");
            }

            $errors = $response->json()['errors'] ?? [];
            $msg    = $response->json()['message'] ?? 'Import gagal.';
            if (! empty($errors)) {
                $msg .= ' ' . implode(', ', array_slice($errors, 0, 3));
                if (count($errors) > 3) {
                    $msg .= ' ... dan ' . (count($errors) - 3) . ' error lainnya.';
                }
            }

            return redirect()->back()->with('error', $msg);

        } catch (\Exception $e) {
            \Log::error('Import Excel error: ' . $e->getMessage());
            return redirect()->back()->with('error', 'Server error: ' . $e->getMessage());
        }
    }
}
