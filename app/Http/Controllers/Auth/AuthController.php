<?php
namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Session;

class AuthController extends Controller
{
    public function index()
    {
        return view('auth.login');
    }

    public function login(Request $request)
    {
        $request->validate(['username' => 'required', 'password' => 'required']);

        try {
            $response = Http::post(env('API_BASE_URL') . '/login', [
                'username' => $request->username,
                'password' => $request->password,
            ]);

            if ($response->successful()) {
                $data     = $response->json();
                $userData = $data['user'] ?? null;

                $token      = null;
                $rawCookies = $response->toPsrResponse()->getHeader('Set-Cookie');

                foreach ($rawCookies as $cookie) {
                    if (preg_match('/token=([^;]+)/', $cookie, $matches)) {
                        // FIX: urldecode() wajib agar token tidak corrupt.
                        // Express/browser sering URL-encode nilai cookie,
                        // misal "=" jadi "%3D", "+" jadi "%2B".
                        // Kalau disimpan mentah, jwt.verify() di backend akan gagal → 401.
                        $token = urldecode($matches[1]);
                        break;
                    }
                }

                if (! $token || ! $userData) {
                    return back()
                        ->withErrors(['login_error' => 'Gagal mengambil sesi dari server.'])
                        ->withInput();
                }

                Session::put('user_data', $userData);
                Session::put('token', $token);
                Session::save();

                $role = $userData['role'] ?? '';

                return match ($role) {
                    'admin'          => redirect()->route('admin.dashboard'),
                    'kepala_sekolah' => redirect()->route('kepsek.dashboard'),
                    'guru_bk'        => redirect()->route('bk.dashboard'),
                    'absensi'        => redirect()->route('absen.dashboard'),
                    default          => redirect()->route('login')
                        ->withErrors(['login_error' => 'Role tidak dikenali: ' . $role])
                };
            }

            return back()->withErrors(['login_error' => 'Username atau Password salah!'])->withInput();

        } catch (\Exception $e) {
            return back()->withErrors(['login_error' => 'Gagal terhubung ke API.'])->withInput();
        }
    }

    public function logout()
    {
        Session::flush();
        return redirect()->route('login');
    }
}
