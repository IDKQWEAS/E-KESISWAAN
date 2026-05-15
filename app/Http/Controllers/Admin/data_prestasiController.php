<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Session;

class data_prestasiController extends Controller
{
    private function headers() {
        $token = Session::get('token');
        return [
            'Cookie'        => 'token=' . $token,
            'Authorization' => 'Bearer ' . $token,
            'Accept'        => 'application/json',
        ];
    }

    private function base() {
        return env('API_BASE_URL');
    }

    public function index(Request $request) {
        if (!Session::has('token')) return redirect()->route('login');
        $dataPrestasi = []; $taList = []; $taAktif = null;

        try {
            $resTa = Http::withHeaders($this->headers())->get($this->base() . '/tahun_ajaran');
            if ($resTa->successful()) {
                $taList = $resTa->json();
                $reqIdTa = $request->get('id_tahun_ajaran');
                $taAktif = $reqIdTa ? collect($taList)->firstWhere('id', (int)$reqIdTa) : (collect($taList)->firstWhere('status', 'aktif') ?? collect($taList)->first());
            }

            $response = Http::withHeaders($this->headers())->get($this->base() . '/prestasi', [
                'limit' => 100,
                'tahun_ajaran' => $taAktif['tahun_ajaran'] ?? '',
                'semester' => $taAktif['semester'] ?? '',
            ]);

            if ($response->successful()) {
                $resJson = $response->json();
                $dataPrestasi = $resJson['data'] ?? $resJson['prestasi'] ?? [];
            }
        } catch (\Exception $e) { \Log::error('Index Prestasi: ' . $e->getMessage()); }

        return view('admin.data_prestasi', compact('dataPrestasi', 'taList', 'taAktif'));
    }

    public function getSiswaByKelas(Request $request) {
        $resTa = Http::withHeaders($this->headers())->get($this->base() . '/tahun_ajaran');
        $taAktif = collect($resTa->json())->firstWhere('status', 'aktif') ?? collect($resTa->json())->first();

        $response = Http::withHeaders($this->headers())->get($this->base() . '/siswa', [
            'kelas' => $request->kelas,
            'limit' => 200,
            'tahun_ajaran' => $taAktif['tahun_ajaran'] ?? '',
            'semester' => $taAktif['semester'] ?? ''
        ]);
        return response()->json($response->json(), $response->status());
    }

    public function store(Request $request) { return $this->proxySave($request); }
    public function update(Request $request, $id) { return $this->proxySave($request, $id); }

    private function proxySave(Request $request, $id = null) {
        try {
            $apiReq = Http::withHeaders($this->headers());
            if ($id) $apiReq = $apiReq->attach('_method', 'PUT');

            $payload = [
                'id_siswa'      => $request->id_siswa,
                'nama_lomba'    => $request->judul_prestasi,
                'tanggal'       => $request->tanggal,
                'kategori'      => $request->kategori,
                'tingkat'       => $request->tingkat,
                'peringkat'     => $request->juara,
                'penyelenggara' => $request->instansi,
                'keterangan'    => $request->deskripsi,
            ];

            foreach ($payload as $key => $value) {
                if ($value !== null) $apiReq = $apiReq->attach($key, $value);
            }

            if ($request->hasFile('file_bukti')) {
                $file = $request->file('file_bukti');
                $apiReq = $apiReq->attach('gambar', file_get_contents($file->getRealPath()), $file->getClientOriginalName());
            }

            $url = $this->base() . '/prestasi' . ($id ? '/' . $id : '');
            $response = $apiReq->post($url); 
            return response()->json($response->json(), $response->status());
        } catch (\Exception $e) { return response()->json(['message' => $e->getMessage()], 500); }
    }

    public function destroy($id) {
        $response = Http::withHeaders($this->headers())->delete($this->base() . '/prestasi/' . $id);
        return response()->json($response->json(), $response->status());
    }
}