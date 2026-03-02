<?php
namespace App\Http\Controllers\Kepsek;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Session;

class MonitoringHomeVisitController extends Controller
{
    public function index(Request $request)
    {
        $user  = Session::get('user_data');
        $token = Session::get('token');

        if (! $user || ! $token) {
            return redirect()->route('login');
        }

        $role         = $user['role'] ?? 'kepala_sekolah';
        $visitSelesai = [];
        $visitPending = [];

        try {
            $allRows    = [];
            $page       = 1;
            $totalPages = 1;

            do {
                $response = Http::withHeaders([
                    'Cookie' => 'token=' . $token,
                ])->get(env('API_BASE_URL') . '/home_visit', [
                    'page'  => $page,
                    'limit' => 100,
                ]);

                if (! $response->successful()) {
                    break;
                }

                $json       = $response->json();
                $rows       = $json['data'] ?? [];
                $totalPages = $json['pagination']['totalPages'] ?? 1;

                $allRows = array_merge($allRows, $rows);
                $page++;

            } while ($page <= $totalPages);

            foreach ($allRows as $visit) {
                $status = $visit['status'] ?? '';
                if ($status === 'Sudah Terlaksana') {
                    $visitSelesai[] = $visit;
                } elseif ($status === 'Rencana Kunjungan') {
                    $visitPending[] = $visit;
                }
            }

        } catch (\Exception $e) {
            \Log::error('MonitoringHomeVisit error: ' . $e->getMessage());
        }

        return view('kepsek.monitoring_visit', compact('visitSelesai', 'visitPending', 'role'));
    }

    public function detail(Request $request, $id)
    {
        $token = Session::get('token');

        if (! $token) {
            return response()->json(['error' => 'Unauthorized'], 401);
        }

        try {
            $response = Http::withHeaders([
                'Cookie' => 'token=' . $token,
            ])->get(env('API_BASE_URL') . '/home_visit/' . $id);

            if ($response->successful()) {
                return response()->json($response->json());
            }

            return response()->json(['error' => 'Gagal ambil data'], 500);

        } catch (\Exception $e) {
            return response()->json(['error' => $e->getMessage()], 500);
        }
    }
}
