<?php
namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Cookie;
use Illuminate\Support\Facades\Http; //untuk memanggil API
use Illuminate\Support\Facades\Session;

// untuk menyimpan sesi login

class DashboardController extends Controller
{
    // === AUTH ===
    public function index()
    {
        return view('auth.login');
    }

    public function login(Request $request)
    {
        // 1. Ambil URL API dari file .env (Railway)
        $apiUrl = env('API_BASE_URL') . '/login';
        try {
            // 2. Tembak API milik temanmu dengan data dari form
            $response = Http::post($apiUrl, [
                'nip'      => $request->username,
                'password' => $request->password,
            ]);

            // 3. Jika API merespons sukses (berhasil login)
            if ($response->successful()) {
                $data = $response->json();
                // Ambil header Set-Cookie
                $setCookie = $response->header('Set-Cookie');

// Ambil value token dari header
                preg_match('/token=([^;]+)/', $setCookie, $matches);
                $token = $matches[1] ?? null;

                if ($token) {
                    Cookie::queue(
                        'token',
                        $token,
                        60, // expire (menit)
                        '/',
                        null,
                        false, // secure (ubah true kalau HTTPS)
                        true   // httpOnly (recommended)
                    );
                }

                // Ambil role yang dikembalikan oleh API backend
                $role = $data['user']['role'];

                // Simpan data pengguna ke session agar bisa dipanggil di halaman dashboard
                Session::put('user', $data['user']);
                Session::put('is_logged_in', true);

                // 4. Arahkan pengguna berdasarkan role dari API ke rute buatan temanmu
                if ($role === 'admin') {
                    return redirect('/admin/dashboard');
                }

                if ($role === 'guru_bk') {
                    return redirect('/bk/dashboard');
                }

                if ($role === 'kepala_sekolah') {
                    return redirect('/kepsek/dashboard');
                }

                if ($role === 'absensi') {
                    return redirect('/absensi');
                }

                return redirect('/');
            }

            // Jika gagal (username/password salah), kembalikan ke form dengan pesan error
            return back()->withErrors(['username' => 'Username atau password tidak valid.']);

        } catch (\Exception $e) {
            // Jika server API sedang bermasalah atau mati
            return back()->withErrors(['username' => 'Gagal terhubung ke API backend.']);
        }
    }

    // ADMIN PAGES ===
    public function adminDashboard()
    {return view('admin.dashboard');}
    public function adminKehadiran()
    {return view('admin.data_kehadiran');}
    public function adminSiswa()
    {return view('admin.data_siswa');}
    public function adminPrestasi()
    {return view('admin.data_prestasi');}
    public function adminPelanggaran()
    {return view('admin.data_pelanggaran');}
    public function adminLaporan()
    {return view('admin.laporan');}
    public function adminUsers()
    {return view('admin.users');}
    public function adminPengaturan()
    {return view('admin.pengaturan');}

    // BK PAGES ===
    public function bkDashboard()
    {return view('bk.dashboard');}
    public function bkPelanggaran()
    {return view('bk.input_pelanggaran');}
    public function bkPemantauan()
    {return view('bk.pemantauan');}
    public function bkBiodata()
    {return view('bk.biodata');}
    public function bkHomeVisit()
    {return view('bk.home_visit');}
    public function bkPerizinan()
    {return view('bk.perizinan');}
    public function bkRekap()
    {return view('bk.rekap_kehadiran');}

    // KEPSEK PAGES ===
    public function kepsekDashboard()
    {return view('kepsek.dashboard');}

    public function kepsekDisiplin(Request $request)
    {
        $apiUrl      = env('API_BASE_URL') . '/pelanggaran';
        $pelanggaran = [];

        try {
            // Ambil token dari cookie
            $token = $request->cookie('token');

            // Ambil kelas dari filter (jika ada)
            $kelas = $request->kelas ?? '';

            $response = Http::withoutVerifying()
                ->withHeaders([
                    'Accept' => 'application/json',
                    'Cookie' => 'token=' . $token,
                ])
                ->get($apiUrl, [
                    'page'         => 1,
                    'limit'        => 10,
                    'search'       => '',
                    'kelas'        => $kelas,
                    'tahun_ajaran' => '2025/2026',
                    'semester'     => 'Ganjil',
                ]);

            if ($response->successful()) {
                $pelanggaran = $response->json()['data'] ?? [];
            }

        } catch (\Exception $e) {
            // Bisa tambahkan Log::error($e->getMessage());
        }

        return view('kepsek.peta_disiplin', compact('pelanggaran'));
    }

    public function kepsekPrestasi(Request $request)
    {
        $apiUrl     = env('API_BASE_URL') . '/prestasi';
        $prestasi   = [];
        $pagination = [];

        try {
            $token = $request->cookie('token');

            // kategori dari dropdown (lowercase supaya cocok API)
            $kategori = strtolower($request->kategori ?? '');

            $response = Http::withoutVerifying()
                ->withHeaders([
                    'Accept' => 'application/json',
                    'Cookie' => 'token=' . $token,
                ])
                ->get($apiUrl, [
                    'page'         => 1,
                    'limit'        => 10,
                    'kategori'     => $kategori,
                    'tahun_ajaran' => '2025/2026',
                    'semester'     => 'Ganjil',
                ]);

            if ($response->successful()) {
                $json = $response->json();

                $prestasi   = $json['data'] ?? [];
                $pagination = $json['pagination'] ?? [];
            }

        } catch (\Exception $e) {
            // Log::error($e->getMessage());
        }

        return view('kepsek.monitoring_prestasi', compact('prestasi', 'pagination'));
    }

    public function kepsekAbsensi(Request $request)
    {

        $apiUrl     = env('API_BASE_URL') . '/rekap_kehadiran';
        $absensi    = [];
        $kelasAktif = $request->kelas ?? '7A';

        try {

            $response = Http::withoutVerifying()
                ->withHeaders([
                    'Accept' => 'application/json',
                ])
                ->get($apiUrl, [
                    'kelas' => $kelasAktif,
                ]);

            if ($response->successful()) {
                $absensi = $response->json()['data'] ?? [];
            }

        } catch (\Exception $e) {
            //Log::error($e->getMessage());
        }

        return view('kepsek.monitoring_absensi', compact('absensi', 'kelasAktif'));
    }

    public function kepsekVisit(Request $request)
    {
        $apiUrl     = env('API_BASE_URL') . '/home_visit';
        $homeVisits = [];

        $kelasAktif = $request->kelas ?? '7A';
        $page       = $request->page ?? 1;
        $limit      = $request->limit ?? 10;

        try {

            $$response = Http::withoutVerifying()
                ->withHeaders([
                    'Accept' => 'application/json',

                ])
                ->get($apiUrl, [
                    'page'         => $page,
                    'limit'        => $limit,
                    'kelas'        => $kelasAktif,
                    'tahun_ajaran' => '2025/2026',
                    'semester'     => 'Ganjil',
                ]);

            if ($response->successful()) {
                $homeVisits = $response->json()['data'] ?? [];
            }

        } catch (\Exception $e) {
            // Log::error($e->getMessage());
        }

        // Pisahkan berdasarkan status
        $visitSelesai = array_filter($homeVisits, function ($item) {
            return isset($item['status']) &&
            strtolower($item['status']) === 'sudah terlaksana';
        });

        $visitPending = array_filter($homeVisits, function ($item) {
            return isset($item['status']) &&
            strtolower($item['status']) !== 'sudah terlaksana';
        });

        return view('kepsek.monitoring_visit', compact(
            'visitSelesai',
            'visitPending',
            'kelasAktif'
        ));

    }

    public function kepsekProfile()
    {
        return view('kepsek.edit_profile');
    }

    // absensi
    public function halamanAbsensi()
    {return view('absensi.index');}
}
