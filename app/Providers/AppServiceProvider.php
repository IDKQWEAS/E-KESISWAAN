<?php
namespace App\Providers;

use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Session;
use Illuminate\Support\Facades\View;
use Illuminate\Support\ServiceProvider;

class AppServiceProvider extends ServiceProvider
{
    public function boot(): void
    {
        View::composer('components.layout-app', function ($view) {
            $token            = Session::get('token');
            $listTahunAjaran  = [];
            $tahunAjaranAktif = null;

            if ($token) {
                try {
                    $response = Http::withHeaders([
                        'Cookie' => 'token=' . $token,
                    ])->get(env('API_BASE_URL') . '/tahun_ajaran');

                    if ($response->successful()) {
                        $listTahunAjaran = $response->json();

                        // Cari yang aktif
                        $tahunAjaranAktif = collect($listTahunAjaran)->first(function ($ta) {
                            return ! empty($ta['is_aktif'])
                            || ! empty($ta['aktif'])
                                || ($ta['status'] ?? '') === 'aktif'
                                || ($ta['is_active'] ?? false) === true;
                        }) ?? ($listTahunAjaran[0] ?? null);
                    }
                } catch (\Exception $e) {
                    // Biarkan kosong jika API gagal
                }
            }

            $view->with([
                'listTahunAjaran'  => $listTahunAjaran,
                'tahunAjaranAktif' => $tahunAjaranAktif,
            ]);
        });
    }
}
