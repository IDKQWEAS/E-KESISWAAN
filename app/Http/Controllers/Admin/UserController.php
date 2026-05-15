<?php
namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Session;

class UserController extends Controller
{
    // 1. TAMPILKAN DATA USER
    public function index()
    {
        $token = Session::get('token');
        if (! $token) {
            return redirect()->route('login');
        }

        $dataUser = [];

        try {
            $response = Http::withHeaders([
                'Cookie'        => 'token=' . $token, // <-- PERBAIKAN: Tambahkan Cookie Token di sini
                'Authorization' => 'Bearer ' . $token,
                'Accept'        => 'application/json',
            ])->get(env('API_BASE_URL') . '/users');

            if ($response->successful()) {
                $resJson = $response->json();

                if (isset($resJson['data']) && is_array($resJson['data'])) {
                    $dataUser = $resJson['data'];
                } elseif (isset($resJson['users']) && is_array($resJson['users'])) {
                    $dataUser = $resJson['users'];
                } elseif (is_array($resJson)) {
                    $dataUser = $resJson;
                }
            }
        } catch (\Exception $e) {
            Log::error('Gagal ambil data user: ' . $e->getMessage());
        }

        return view('admin.users', compact('dataUser'));
    }

    // 2. TAMBAH USER BARU
    public function store(Request $request)
    {
        $token = Session::get('token');
        try {
            $response = Http::withHeaders([
                'Cookie'        => 'token=' . $token, // <-- PERBAIKAN DI SINI
                'Authorization' => 'Bearer ' . $token,
                'Accept'        => 'application/json',
            ])->post(env('API_BASE_URL') . '/users', $request->all());

            return response()->json(['status' => $response->status(), 'data' => $response->json()], $response->status());
        } catch (\Exception $e) {
            return response()->json(['status' => 500, 'data' => ['message' => $e->getMessage()]], 500);
        }
    }

    // 3. UPDATE USER
    // 3. UPDATE USER
    public function update(Request $request, $id)
    {
        $token = Session::get('token');
        try {
            // PERBAIKAN: Gunakan PATCH agar sinkron dengan Node.js routes.js
            $response = Http::withHeaders([
                'Cookie'        => 'token=' . $token,
                'Authorization' => 'Bearer ' . $token,
                'Accept'        => 'application/json',
            ])->patch(env('API_BASE_URL') . '/users/' . $id, $request->all());

            return response()->json(['status' => $response->status(), 'data' => $response->json()], $response->status());
        } catch (\Exception $e) {
            return response()->json(['status' => 500, 'data' => ['message' => $e->getMessage()]], 500);
        }
    }

    // 4. HAPUS USER
    public function destroy($id)
    {
        $token = Session::get('token');
        try {
            $response = Http::withHeaders([
                'Cookie'        => 'token=' . $token, // <-- PERBAIKAN DI SINI
                'Authorization' => 'Bearer ' . $token,
                'Accept'        => 'application/json',
            ])->delete(env('API_BASE_URL') . '/users/' . $id);

            return response()->json(['status' => $response->status(), 'data' => $response->json()], $response->status());
        } catch (\Exception $e) {
            return response()->json(['status' => 500, 'data' => ['message' => 'Gagal terhubung ke server']], 500);
        }
    }
}
