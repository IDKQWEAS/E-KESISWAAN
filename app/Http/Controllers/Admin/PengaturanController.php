<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Session;

class PengaturanController extends Controller
{
    private function headers()
    {
        return [
            'Cookie'        => 'token=' . Session::get('token'),
            'Authorization' => 'Bearer ' . Session::get('token'),
            'Accept'        => 'application/json',
        ];
    }

    public function index()
    {
        $pengaturan = [];
        try {
            $response = Http::withHeaders($this->headers())->get(env('API_BASE_URL') . '/config');
            if ($response->successful()) {
                foreach ($response->json() as $item) {
                    $pengaturan[$item['config_key']] = $item['config_value'];
                }
            }
        } catch (\Exception $e) {}
        return view('admin.pengaturan', compact('pengaturan'));
    }

    public function update(Request $request)
    {
        $base = env('API_BASE_URL') . '/config';
        try {
            $mapping = [
                1 => ['key' => 'maks_poin_pelanggaran', 'val' => $request->poin_awal],
                2 => ['key' => 'jam_masuk',             'val' => $request->jam_masuk],
                3 => ['key' => 'jam_terlambat',         'val' => $request->waktu_terlambat],
                4 => ['key' => 'jam_boleh_pulang',      'val' => $request->jam_pulang_mulai],
                5 => ['key' => 'batas_akhir_pulang',    'val' => $request->jam_pulang_akhir],
            ];

            foreach ($mapping as $id => $data) {
                if ($data['val']) {
                    Http::withHeaders($this->headers())->patch("$base/$id", [
                        'config_key'   => $data['key'],
                        'config_value' => $data['val']
                    ]);
                }
            }
            return back()->with('success', 'Konfigurasi berhasil disimpan!');
        } catch (\Exception $e) {
            return back()->with('error', 'Gagal update data.');
        }
    }
}