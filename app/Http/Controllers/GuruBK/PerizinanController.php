<?php
namespace App\Http\Controllers\GuruBK;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Session;

class PerizinanController extends Controller
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

        $role      = $user['role'] ?? 'guru_bk';
        $reqIdTa   = $request->get('id_tahun_ajaran');
        $perizinan = [];
        $siswaList = [];

        $kelasList = [
            '7A', '7B', '7C', '7D', '7E', '7F', '7G',
            '8A', '8B', '8C', '8D', '8E', '8F', '8G',
            '9A', '9B', '9C', '9D', '9E', '9F', '9G',
        ];

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

            // 2. Fetch Perizinan
            $params = [];
            if ($taString) {
                $params['tahun_ajaran'] = $taString;
            }

            if ($semesterString) {
                $params['semester'] = $semesterString;
            }

            $r = Http::withHeaders($this->headers())
                ->get($this->base() . '/perizinan', $params);

            if ($r->successful()) {
                $body      = $r->json();
                $perizinan = is_array($body) ? $body : ($body['data'] ?? []);
            }

            // 3. Fetch Siswa untuk Modal Tambah Izin
            $paramsSiswa = ['limit' => 9999];
            if ($taString) {
                $paramsSiswa['tahun_ajaran'] = $taString;
            }

            if ($semesterString) {
                $paramsSiswa['semester'] = $semesterString;
            }

            $rSiswa = Http::withHeaders($this->headers())
                ->get($this->base() . '/siswa', $paramsSiswa);

            if ($rSiswa->successful()) {
                $body      = $rSiswa->json();
                $siswaList = $body['data'] ?? $body;
            }
        } catch (\Exception $e) {
            \Log::error('Perizinan index error: ' . $e->getMessage());
        }

        // Filtering di frontend jika form filter disubmit
        if ($request->filled('kelas')) {
            $perizinan = array_values(array_filter(
                $perizinan,
                fn($p) => ($p['kelas'] ?? '') === $request->kelas
            ));
        }

        if ($request->filled('tanggal')) {
            $perizinan = array_values(array_filter(
                $perizinan,
                function ($p) use ($request) {
                    $tgl = $p['tanggal'] ?? $p['created_at'] ?? '';
                    return str_starts_with((string) $tgl, $request->tanggal);
                }
            ));
        }

        return view('guru_bk.perizinan', compact('role', 'perizinan', 'siswaList', 'kelasList'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'id_siswa' => 'required',
            'status'   => 'required|in:izin,sakit,alpha',
            'tanggal'  => 'required|date',
        ]);

        try {
            $parts = [
                ['name' => 'id_siswa', 'contents' => (string) $request->id_siswa],
                ['name' => 'status', 'contents' => $request->status],
                ['name' => 'tanggal', 'contents' => $request->tanggal],
                ['name' => 'keterangan', 'contents' => $request->keterangan ?? ''],
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
                ->post($this->base() . '/perizinan', $parts);

            if ($response->successful()) {
                return redirect()->route('bk.perizinan')
                    ->with('success', 'Perizinan berhasil dibuat.');
            }

            return redirect()->back()
                ->with('error', 'Gagal membuat perizinan. ' . $response->body())
                ->withInput();

        } catch (\Exception $e) {
            \Log::error('Perizinan store error: ' . $e->getMessage());
            return redirect()->back()->with('error', 'Server error.')->withInput();
        }
    }

    public function update(Request $request, $id)
    {
        $request->validate([
            'status'  => 'nullable|in:izin,sakit,alpha',
            'tanggal' => 'nullable|date',
        ]);

        try {
            if ($request->hasFile('gambar')) {
                $parts = [];

                if ($request->filled('status')) {
                    $parts[] = ['name' => 'status', 'contents' => $request->status];
                }
                if ($request->filled('keterangan')) {
                    $parts[] = ['name' => 'keterangan', 'contents' => $request->keterangan];
                }
                if ($request->filled('tanggal')) {
                    $parts[] = ['name' => 'tanggal', 'contents' => $request->tanggal];
                }

                $file    = $request->file('gambar');
                $parts[] = [
                    'name'     => 'gambar',
                    'contents' => fopen($file->getRealPath(), 'r'),
                    'filename' => $file->getClientOriginalName(),
                ];

                $response = Http::withHeaders($this->headers())
                    ->asMultipart()
                    ->patch($this->base() . '/perizinan/' . $id, $parts);

            } else {
                $data = array_filter([
                    'status'     => $request->status ?: null,
                    'keterangan' => $request->keterangan ?: null,
                    'tanggal'    => $request->tanggal ?: null,
                ], fn($v) => ! is_null($v));

                $response = Http::withHeaders($this->headers())
                    ->patch($this->base() . '/perizinan/' . $id, $data);
            }

            if ($response->successful()) {
                return redirect()->route('bk.perizinan')
                    ->with('success', 'Perizinan berhasil diperbarui.');
            }

            return redirect()->back()
                ->with('error', 'Gagal memperbarui perizinan. Status: ' . $response->status() . ' - ' . $response->body());

        } catch (\Exception $e) {
            \Log::error('Perizinan update error: ' . $e->getMessage());
            return redirect()->back()->with('error', 'Server error: ' . $e->getMessage());
        }
    }

    public function destroy($id)
    {
        try {
            $response = Http::withHeaders($this->headers())
                ->delete($this->base() . '/perizinan/' . $id);

            if ($response->successful()) {
                return redirect()->route('bk.perizinan')
                    ->with('success', 'Perizinan berhasil dihapus.');
            }

            return redirect()->back()->with('error', 'Gagal menghapus perizinan.');

        } catch (\Exception $e) {
            \Log::error('Perizinan destroy error: ' . $e->getMessage());
            return redirect()->back()->with('error', 'Server error.');
        }
    }
}
