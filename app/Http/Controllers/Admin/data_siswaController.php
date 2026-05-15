<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Session;

class data_siswaController extends Controller
{
    /*
    |--------------------------------------------------------------------------
    | HEADER TOKEN
    |--------------------------------------------------------------------------
    */

    private function headers()
    {
        return [
            'Cookie' => 'token=' . Session::get('token')
        ];
    }

    /*
    |--------------------------------------------------------------------------
    | BASE API
    |--------------------------------------------------------------------------
    */

    private function base()
    {
        return env('API_BASE_URL');
    }

    /*
    |--------------------------------------------------------------------------
    | INDEX
    |--------------------------------------------------------------------------
    */

    public function index(Request $request)
    {
        /*
        |--------------------------------------------------------------------------
        | VALIDASI LOGIN
        |--------------------------------------------------------------------------
        */

        $token = Session::get('token');

        if (!$token) {

            return redirect()->route('login');

        }

        /*
        |--------------------------------------------------------------------------
        | DEFAULT DATA
        |--------------------------------------------------------------------------
        */

        $dataSiswa = [
            'data' => [],
            'current_page' => 1,
            'last_page' => 1,
            'total' => 0,
            'per_page' => 10
        ];

        $taList = [];

        $taAktif = null;

        try {

            /*
            |--------------------------------------------------------------------------
            | GET TAHUN AJARAN
            |--------------------------------------------------------------------------
            */

            $resTa = Http::withHeaders($this->headers())
                ->get($this->base() . '/tahun_ajaran');

            if ($resTa->successful()) {

                $taList = $resTa->json();

                $reqIdTa = $request->get('tahun_id');

                $taAktif = $reqIdTa
                    ? collect($taList)->firstWhere('id', (int) $reqIdTa)
                    : collect($taList)->firstWhere('status', 'aktif');

                if (!$taAktif && count($taList) > 0) {

                    $taAktif = $taList[0];

                }
            }

            /*
            |--------------------------------------------------------------------------
            | PARAMETER API
            |--------------------------------------------------------------------------
            */

            $params = [

                'page'  => $request->get('page', 1),

                'limit' => 10,

            ];

            /*
            |--------------------------------------------------------------------------
            | FILTER TAHUN AJARAN
            |--------------------------------------------------------------------------
            */

            if ($taAktif) {

                $params['tahun_ajaran'] = $taAktif['tahun_ajaran'];

                $params['semester'] = $taAktif['semester'];

            }

            /*
            |--------------------------------------------------------------------------
            | FILTER KELAS
            |--------------------------------------------------------------------------
            */

            if ($request->filled('kelas')) {

                $params['kelas'] = $request->kelas;

            }

            /*
            |--------------------------------------------------------------------------
            | SEARCH
            |--------------------------------------------------------------------------
            */

            if ($request->filled('q')) {

                $params['search'] = $request->q;

            }

            /*
            |--------------------------------------------------------------------------
            | REQUEST API SISWA
            |--------------------------------------------------------------------------
            */

            $response = Http::withHeaders($this->headers())
                ->get($this->base() . '/siswa', $params);

            /*
            |--------------------------------------------------------------------------
            | SUCCESS RESPONSE
            |--------------------------------------------------------------------------
            */

            if ($response->successful()) {

                $json = $response->json();

                $pagination = $json['pagination'] ?? [];

                $dataSiswa = [

                    'data' => $json['data'] ?? [],

                    'current_page' => $pagination['page'] ?? 1,

                    'last_page' => $pagination['totalPages'] ?? 1,

                    'total' => $pagination['total'] ?? 0,

                    'per_page' => $pagination['limit'] ?? 10

                ];
            }

        } catch (\Exception $e) {

            /*
            |--------------------------------------------------------------------------
            | ERROR LOG
            |--------------------------------------------------------------------------
            */

            \Log::error('ADMIN SISWA ERROR : ' . $e->getMessage());

        }

        /*
        |--------------------------------------------------------------------------
        | AJAX REQUEST
        |--------------------------------------------------------------------------
        */

        if ($request->ajax()) {

            return response()->json([

                'table' => view('admin.data_siswa', compact(
                    'dataSiswa',
                    'taList',
                    'taAktif'
                ))->render()

            ]);

        }

        /*
        |--------------------------------------------------------------------------
        | NORMAL VIEW
        |--------------------------------------------------------------------------
        */

        return view('admin.data_siswa', compact(
            'dataSiswa',
            'taList',
            'taAktif'
        ));
    }
}