<?php
namespace App\Http\Controllers\Kepsek;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Session;

class EditProfilController extends Controller
{
    public function index()
    {
        $user  = Session::get('user_data') ?? Session::get('user');
        $token = Session::get('token');

        if (! $user || ! $token) {
            return redirect()->route('login');
        }

        $role     = $user['role'] ?? 'kepala_sekolah';
        $userData = [];

        try {
            $response = Http::withHeaders([
                'Cookie' => 'token=' . $token,
            ])->get(env('API_BASE_URL') . '/users/' . $user['id']);

            if ($response->successful()) {
                $userData = $response->json();
            }
        } catch (\Exception $e) {
            \Log::error('EditProfil fetch error: ' . $e->getMessage());
        }

        return view('kepsek.edit_profile', compact('userData', 'role'));
    }

    public function update(Request $request)
    {
        $user  = Session::get('user_data') ?? Session::get('user');
        $token = Session::get('token');

        if (! $user || ! $token) {
            return response()->json(['success' => false, 'message' => 'Sesi habis, silakan login ulang.'], 401);
        }

        $nama        = trim($request->input('nama', ''));
        $oldPassword = trim($request->input('old_password', ''));
        $newPassword = trim($request->input('new_password', ''));
        $confirmPass = trim($request->input('confirm_password', ''));

        if (empty($nama)) {
            return response()->json(['success' => false, 'message' => 'Nama tidak boleh kosong.']);
        }

        $payload = ['nama' => $nama];

        if ($newPassword !== '') {
            if (empty($oldPassword)) {
                return response()->json(['success' => false, 'message' => 'Password lama wajib diisi untuk mengganti password.']);
            }

            if (strlen($newPassword) < 6) {
                return response()->json(['success' => false, 'message' => 'Password baru minimal 6 karakter.']);
            }

            if ($newPassword !== $confirmPass) {
                return response()->json(['success' => false, 'message' => 'Konfirmasi password tidak cocok.']);
            }

            $loginCheck = Http::post(env('API_BASE_URL') . '/login', [
                'username' => $user['username'],
                'password' => $oldPassword,
            ]);

            if (! $loginCheck->successful()) {
                return response()->json(['success' => false, 'message' => 'Password lama salah.']);
            }

            $payload['password'] = $newPassword;
        }

        try {
            $response = Http::withHeaders([
                'Cookie' => 'token=' . $token,
            ])->patch(env('API_BASE_URL') . '/users/' . $user['id'], $payload);

            if ($response->successful()) {
                $updatedUser         = $user;
                $updatedUser['nama'] = $nama;
                Session::put('user_data', $updatedUser);

                return response()->json(['success' => true, 'message' => 'Profil berhasil diperbarui.']);
            }

            return response()->json(['success' => false, 'message' => 'Gagal memperbarui profil. Coba lagi.']);

        } catch (\Exception $e) {
            \Log::error('EditProfil update error: ' . $e->getMessage());
            return response()->json(['success' => false, 'message' => 'Terjadi kesalahan server.']);
        }
    }
}
