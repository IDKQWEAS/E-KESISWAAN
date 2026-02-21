@props(['role'])

<aside id="sidebar" class="w-72 bg-navy-900 text-white flex flex-col z-50 shadow-2xl transition-all flex-shrink-0 fixed h-full lg:static">
    
    <div class="h-20 flex items-center px-8 border-b border-navy-800/50 bg-navy-900 justify-between">
        <div class="flex items-center gap-3">
            <div class="w-10 h-10 bg-primary rounded-xl flex items-center justify-center shadow-lg">
                @if($role == 'kepsek')
                    <i class="fa-solid fa-graduation-cap text-white text-lg"></i>
                @elseif($role == 'bk')
                    <i class="fa-solid fa-user-shield text-white text-lg"></i>
                @else
                    <i class="fa-solid fa-school text-white text-lg"></i>
                @endif
            </div>
            <div>
                <h1 class="font-bold text-lg tracking-tight">E-KESISWAAN</h1>
                <p class="text-[10px] text-slate-400 font-medium tracking-widest uppercase">SMPN 1 POLANHARJO</p>
            </div>
        </div>
        <button onclick="toggleSidebar()" class="lg:hidden text-slate-400 hover:text-white">
            <i class="fa-solid fa-xmark"></i>
        </button>
    </div>

    <div class="p-6">
        <div class="p-4 bg-navy-800/40 rounded-2xl border border-navy-700/50 flex items-center gap-3">
            <div class="relative">
                <div class="w-12 h-12 rounded-full {{ $role == 'kepsek' ? 'bg-indigo-600' : ($role == 'bk' ? 'bg-blue-600' : 'bg-orange-500') }} flex items-center justify-center font-bold text-white shadow-lg border-2 border-navy-900">
                    {{ strtoupper(substr($role, 0, 2)) }}
                </div>
                <div class="absolute bottom-0 right-0 w-3 h-3 bg-green-500 border-2 border-navy-900 rounded-full"></div>
            </div>
            <div class="overflow-hidden">
                <p class="text-sm font-bold text-white">
                    @if($role == 'kepsek') Kepala Sekolah
                    @elseif($role == 'bk') Guru BK
                    @else Admin Operator
                    @endif
                </p>
                <p class="text-[10px] text-slate-400 uppercase tracking-wide">{{ $role }} Console</p>
            </div>
        </div>
    </div>

    <nav class="flex-1 overflow-y-auto px-4 space-y-1 pb-4 custom-scroll">
        
        @if($role == 'kepsek')
            <p class="px-4 text-[10px] font-bold text-slate-500 uppercase tracking-widest mb-2 mt-2">Executive Menu</p>
            
            <x-nav-link href="/kepsek/dashboard" icon="fa-chart-line" :active="request()->is('kepsek/dashboard')">
                Dashboard Statistik
            </x-nav-link>
            
            <x-nav-link href="/kepsek/disiplin" icon="fa-map-location-dot" :active="request()->is('kepsek/disiplin')">
                Peta Kedisiplinan
            </x-nav-link>
            
            <x-nav-link href="/kepsek/prestasi" icon="fa-trophy" :active="request()->is('kepsek/prestasi')">
                Monitoring Prestasi
            </x-nav-link>
            
            <x-nav-link href="/kepsek/absensi" icon="fa-calendar-check" :active="request()->is('kepsek/absensi')">
                Monitoring Absensi
            </x-nav-link>
            
            <x-nav-link href="/kepsek/visit" icon="fa-house-user" :active="request()->is('kepsek/visit')">
                Monitoring Home Visit
            </x-nav-link>

        @elseif($role == 'bk')
            <p class="px-4 text-[10px] font-bold text-slate-500 uppercase tracking-widest mb-2 mt-2">Menu Utama</p>
            
            <x-nav-link href="/bk/dashboard" icon="fa-chart-line" :active="request()->is('bk/dashboard')">
                Dashboard & Analisis
            </x-nav-link>
            
            <x-nav-link href="/bk/input-pelanggaran" icon="fa-gavel" :active="request()->is('bk/input-pelanggaran')">
                Input Pelanggaran
            </x-nav-link>
            
            <x-nav-link href="/bk/pemantauan" icon="fa-eye" :active="request()->is('bk/pemantauan')">
                Pemantauan Siswa
            </x-nav-link>
            
            <x-nav-link href="/bk/biodata" icon="fa-address-card" :active="request()->is('bk/biodata')">
                Biodata Lengkap
            </x-nav-link>
            
            <p class="px-4 text-[10px] font-bold text-slate-500 uppercase tracking-widest mb-2 mt-4">Layanan & Absensi</p>
            
            <x-nav-link href="/bk/home-visit" icon="fa-house-user" :active="request()->is('bk/home-visit')">
                Data Home Visit
            </x-nav-link>
            
            <x-nav-link href="/bk/perizinan" icon="fa-clipboard-check" :active="request()->is('bk/perizinan')">
                Approval Perizinan
            </x-nav-link>

            <x-nav-link href="/bk/rekap-kehadiran" icon="fa-calendar-days" :active="request()->is('bk/rekap-kehadiran')">
                Rekap Kehadiran
            </x-nav-link>

        @else 
            <p class="px-4 text-[10px] font-bold text-slate-500 uppercase tracking-widest mb-2 mt-2">Menu Utama</p>

            <x-nav-link href="/admin/dashboard" icon="fa-chart-line" :active="request()->is('admin/dashboard')">
                Dashboard
            </x-nav-link>
            
            <x-nav-link href="/admin/kehadiran" icon="fa-calendar-check" :active="request()->is('admin/kehadiran')">
                Data Kehadiran
            </x-nav-link>
            
            <x-nav-link href="/admin/siswa" icon="fa-users" :active="request()->is('admin/siswa')">
                Data Siswa
            </x-nav-link>
            
            <x-nav-link href="/admin/prestasi" icon="fa-trophy" :active="request()->is('admin/prestasi')">
                Data Prestasi
            </x-nav-link>
            
            <x-nav-link href="/admin/pelanggaran" icon="fa-gavel" :active="request()->is('admin/pelanggaran')">
                Data Pelanggaran
            </x-nav-link>
            
            <x-nav-link href="/admin/laporan" icon="fa-file-lines" :active="request()->is('admin/laporan')">
                Cetak Laporan
            </x-nav-link>
            
            <div class="my-4 border-t border-navy-800 mx-4"></div>
            <p class="px-4 text-[10px] font-bold text-slate-500 uppercase tracking-widest mb-2">System</p>
            
            <x-nav-link href="/admin/users" icon="fa-user-gear" :active="request()->is('admin/users')">
                Manajemen User
            </x-nav-link>
            
            <x-nav-link href="/admin/pengaturan" icon="fa-gear" :active="request()->is('admin/pengaturan')">
                Pengaturan
            </x-nav-link>
        @endif
    </nav>

    <div class="p-6 border-t border-navy-800/50">
        <a href="/" class="flex items-center justify-center gap-2 text-slate-400 hover:text-red-400 hover:bg-navy-800 py-3 rounded-xl transition-all text-xs font-bold uppercase tracking-wider group">
            <i class="fa-solid fa-power-off group-hover:animate-pulse"></i> Keluar
        </a>
    </div>
</aside>