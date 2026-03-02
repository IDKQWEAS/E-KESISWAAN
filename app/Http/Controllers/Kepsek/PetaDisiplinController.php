<?php
namespace App\Http\Controllers\Kepsek;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Session;

class PetaDisiplinController extends Controller
{
    public function index(Request $request)
    {
        $user  = Session::get('user_data');
        $token = Session::get('token');

        if (! $user || ! $token) {
            return redirect()->route('login');
        }

        $role        = $user['role'] ?? 'kepala_sekolah';
        $filterKelas = $request->get('kelas');

        $dataPelanggaran = [];

        try {
            $allRows    = [];
            $page       = 1;
            $totalPages = 1;

            do {
                $response = Http::withHeaders([
                    'Cookie' => 'token=' . $token,
                ])->get(env('API_BASE_URL') . '/pelanggaran/total', [
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

            // Filter kelas di PHP
            if ($filterKelas) {
                $allRows = array_filter(
                    $allRows,
                    fn($s) => ($s['kelas'] ?? '') === $filterKelas
                );
            }

            // Hanya tampilkan yang punya pelanggaran
            $allRows = array_filter(
                $allRows,
                fn($s) => (int) ($s['total_poin'] ?? 0) > 0
            );

            $dataPelanggaran = array_values($allRows);

        } catch (\Exception $e) {
            \Log::error('PetaDisiplin error: ' . $e->getMessage());
            $dataPelanggaran = [];
        }

        return view('kepsek.peta_disiplin', compact(
            'dataPelanggaran',
            'role',
            'filterKelas'
        ));
    }
}
