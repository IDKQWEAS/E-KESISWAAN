<?php
namespace App\Http\Controllers\GuruBK;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Session;

class PerizinanController extends Controller
{
    private function headers(): array
    {return ['Cookie' => 'token=' . Session::get('token')];}
    private function base(): string
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

        $role       = $user['role'] ?? 'guru_bk';
        $taResolved = $this->resolveTahunAjaran($request->get('id_tahun_ajaran'));

        $perizinan = [];
        $siswaList = [];
        $kelasList = ['7A', '7B', '7C', '7D', '7E', '7F', '7G', '8A', '8B', '8C', '8D', '8E', '8F', '8G', '9A', '9B', '9C', '9D', '9E', '9F', '9G'];

        try {
            $params = [];
            if ($taResolved['tahun_ajaran']) {
                $params['tahun_ajaran'] = $taResolved['tahun_ajaran'];
            }

            if ($taResolved['semester']) {
                $params['semester'] = $taResolved['semester'];
            }

            $r1 = Http::withHeaders($this->headers())->get($this->base() . '/perizinan', $params);
            if ($r1->successful()) {
                $body      = $r1->json();
                $perizinan = is_array($body) ? $body : ($body['data'] ?? []);
                $perizinan = array_map(function ($p) {
                    $p['jam_mulai']   = isset($p['jam_mulai']) ? (int) $p['jam_mulai'] : null;
                    $p['jam_selesai'] = isset($p['jam_selesai']) ? (int) $p['jam_selesai'] : null;
                    return $p;
                }, $perizinan);
            }

            $paramsSiswa = ['limit' => 9999];
            if ($taResolved['tahun_ajaran']) {
                $paramsSiswa['tahun_ajaran'] = $taResolved['tahun_ajaran'];
            }

            if ($taResolved['semester']) {
                $paramsSiswa['semester'] = $taResolved['semester'];
            }

            $r2 = Http::withHeaders($this->headers())->get($this->base() . '/siswa', $paramsSiswa);
            if ($r2->successful()) {
                $body      = $r2->json();
                $siswaList = $body['data'] ?? $body;
            }
        } catch (\Exception $e) {}

        if ($request->filled('kelas')) {
            $perizinan = array_values(array_filter($perizinan, fn($p) => ($p['kelas'] ?? '') === $request->kelas));
        }

        if ($request->filled('tanggal')) {
            $perizinan = array_values(array_filter($perizinan, function ($p) use ($request) {
                return str_starts_with((string) ($p['tanggal'] ?? $p['created_at'] ?? ''), $request->tanggal);
            }));
        }

        return view('guru_bk.perizinan', compact('role', 'perizinan', 'siswaList', 'kelasList'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'id_siswa'    => 'required',
            'status'      => 'required|in:izin,sakit,alpha',
            'tanggal'     => 'required|date',
            'jam_mulai'   => 'required|integer|min:1|max:10',
            'jam_selesai' => 'required|integer|min:1|max:10|gte:jam_mulai',
        ]);

        try {
            $parts = [
                ['name' => 'id_siswa', 'contents' => (string) $request->id_siswa],
                ['name' => 'status', 'contents' => $request->status],
                ['name' => 'tanggal', 'contents' => $request->tanggal],
                ['name' => 'jam_mulai', 'contents' => (string) $request->jam_mulai],
                ['name' => 'jam_selesai', 'contents' => (string) $request->jam_selesai],
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
            'status'      => 'nullable|in:izin,sakit,alpha',
            'tanggal'     => 'nullable|date',
            'jam_mulai'   => 'nullable|integer|min:1|max:10',
            'jam_selesai' => 'nullable|integer|min:1|max:10|gte:jam_mulai',
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

                if ($request->filled('jam_mulai')) {
                    $parts[] = ['name' => 'jam_mulai', 'contents' => (string) $request->jam_mulai];
                }

                if ($request->filled('jam_selesai')) {
                    $parts[] = ['name' => 'jam_selesai', 'contents' => (string) $request->jam_selesai];
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
                    'status'      => $request->status ?: null,
                    'keterangan'  => $request->keterangan ?: null,
                    'tanggal'     => $request->tanggal ?: null,
                    'jam_mulai'   => $request->jam_mulai ? (int) $request->jam_mulai : null,
                    'jam_selesai' => $request->jam_selesai ? (int) $request->jam_selesai : null,
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
