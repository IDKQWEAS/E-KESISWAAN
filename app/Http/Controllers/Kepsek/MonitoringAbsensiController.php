<?php
namespace App\Http\Controllers\Kepsek;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Session;

class MonitoringAbsensiController extends Controller
{
    public function index(Request $request)
    {
        $user  = Session::get('user_data');
        $token = Session::get('token');

        if (! $user || ! $token) {
            return redirect()->route('login');
        }

        $role       = $user['role'] ?? 'kepala_sekolah';
        $kelasAktif = $request->get('kelas', '');

        $absensi    = [];
        $pagination = [];

        try {
            $params = [
                'page'  => $request->get('page', 1),
                'limit' => 20,
            ];

            if ($kelasAktif) {
                $params['kelas'] = $kelasAktif;
            }

            $response = Http::withHeaders([
                'Cookie' => 'token=' . $token,
            ])->get(env('API_BASE_URL') . '/rekap_kehadiran', $params);

            if ($response->successful()) {
                $json       = $response->json();
                $absensi    = $json['data'] ?? [];
                $pagination = $json['pagination'] ?? [];
            }

        } catch (\Exception $e) {
            \Log::error('MonitoringAbsensi error: ' . $e->getMessage());
        }

        return view('kepsek.monitoring_absensi', compact(
            'absensi',
            'pagination',
            'role',
            'kelasAktif'
        ));
    }
}
