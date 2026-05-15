@props(['role'])

<aside id="sidebar" class="w-72 bg-navy-900 text-white flex flex-col z-50 fixed inset-y-0 left-0 transform -translate-x-full lg:translate-x-0 transition-transform duration-300 shadow-2xl lg:shadow-none border-r border-navy-800">

    {{-- HEADER SIDEBAR (LOGO SMP - UKURAN BESAR) --}}
    <div class="h-20 lg:h-24 flex items-center px-5 lg:px-6 border-b border-navy-800 bg-navy-900 justify-between flex-shrink-0">
        <div class="flex items-center gap-3.5">

            {{-- Menampilkan Logo SMP --}}
            <div class="w-12 h-12 lg:w-14 lg:h-14 bg-white/5 rounded-xl flex items-center justify-center shadow-lg p-1.5 flex-shrink-0 border border-white/10">
    <img src="{{ asset('smp.png') }}" 
         alt="Logo SMPN 1 Polanharjo" 
         class="w-full h-full object-contain drop-shadow-lg transform transition hover:scale-105">
</div>

            <div class="flex flex-col justify-center mt-1">
                <h1 class="font-extrabold text-base lg:text-lg tracking-tight leading-none text-white">E-KESISWAAN</h1>
                <p class="text-[9px] lg:text-[10px] text-blue-400 font-bold tracking-widest uppercase mt-1">SMPN 1 POLANHARJO</p>
            </div>
        </div>

        {{-- Tombol Close untuk versi Mobile --}}
        <button onclick="toggleSidebar()" class="lg:hidden text-slate-400 hover:text-white p-2 focus:outline-none">
            <i class="fa-solid fa-xmark text-2xl"></i>
        </button>
    </div>

    <div class="px-5 pt-6 pb-3 flex-shrink-0">
        <a href="{{ $role == 'kepala_sekolah' ? route('kepsek.profile') : ($role == 'guru_bk' ? route('bk.profile') : '#') }}"
           class="p-3 bg-navy-800/60 rounded-2xl border border-navy-700/50 flex items-center gap-3 cursor-pointer hover:bg-navy-800 transition group block">
            <div class="relative flex-shrink-0">
                <div class="w-10 h-10 rounded-full {{ $role == 'kepala_sekolah' ? 'bg-indigo-600' : ($role == 'guru_bk' ? 'bg-blue-600' : 'bg-orange-500') }} flex items-center justify-center font-bold text-white shadow-lg border-2 border-navy-900 text-sm">
                    {{ strtoupper(substr($role, 0, 2)) }}
                </div>
                <div class="absolute bottom-0 right-0 w-2.5 h-2.5 bg-green-500 border-2 border-navy-900 rounded-full"></div>
            </div>
            <div class="overflow-hidden text-ellipsis flex-1">
                <p class="text-[13px] font-bold text-white group-hover:text-indigo-200 transition truncate leading-tight">
                    @if($role == 'kepala_sekolah') Kepala Sekolah
                    @elseif($role == 'guru_bk') Guru BK
                    @elseif($role == 'absensi') Petugas Absen
                    @else Admin Operator
                    @endif
                </p>
                <p class="text-[10px] text-slate-400 uppercase tracking-wide mt-0.5">Edit Profil</p>
            </div>
        </a>
    </div>

    <nav class="flex-1 overflow-y-auto px-4 pt-2 pb-6 custom-scroll">

        @if($role == 'kepala_sekolah')
            <div class="mb-6">
                <p class="px-3 text-[10px] font-bold text-slate-500 uppercase tracking-widest mb-2.5">Executive Menu</p>
                <div class="flex flex-col gap-1.5">
                    <x-nav-link href="{{ route('kepsek.dashboard') }}" icon="fa-chart-line" :active="request()->routeIs('kepsek.dashboard')">Dashboard Statistik</x-nav-link>
                    <x-nav-link href="{{ route('kepsek.disiplin') }}" icon="fa-map-location-dot" :active="request()->routeIs('kepsek.disiplin')">Peta Kedisiplinan</x-nav-link>
                    <x-nav-link href="{{ route('kepsek.prestasi') }}" icon="fa-trophy" :active="request()->routeIs('kepsek.prestasi')">Monitoring Prestasi</x-nav-link>
                    <x-nav-link href="{{ route('kepsek.absensi') }}" icon="fa-calendar-check" :active="request()->routeIs('kepsek.absensi')">Monitoring Absensi</x-nav-link>
                    <x-nav-link href="{{ route('kepsek.visit') }}" icon="fa-house-user" :active="request()->routeIs('kepsek.visit')">Monitoring Home Visit</x-nav-link>
                </div>
            </div>

        @elseif($role == 'guru_bk')
            <div class="mb-6">
                <p class="px-3 text-[10px] font-bold text-slate-500 uppercase tracking-widest mb-2.5">Menu Utama BK</p>
                <div class="flex flex-col gap-1.5">
                    <x-nav-link href="{{ route('bk.dashboard') }}" icon="fa-chart-line" :active="request()->routeIs('bk.dashboard')">Dashboard & Analisis</x-nav-link>
                    <x-nav-link href="{{ route('bk.pelanggaran') }}" icon="fa-gavel" :active="request()->routeIs('bk.pelanggaran')">Input Pelanggaran</x-nav-link>
                    <x-nav-link href="{{ route('bk.pemantauan') }}" icon="fa-eye" :active="request()->routeIs('bk.pemantauan')">Pemantauan Siswa</x-nav-link>
                    <x-nav-link href="{{ route('bk.biodata') }}" icon="fa-address-card" :active="request()->routeIs('bk.biodata')">Biodata Lengkap</x-nav-link>
                </div>
            </div>

            <div class="mb-6">
                <p class="px-3 text-[10px] font-bold text-slate-500 uppercase tracking-widest mb-2.5">Layanan</p>
                <div class="flex flex-col gap-1.5">
                    <x-nav-link href="{{ route('bk.visit') }}" icon="fa-house-user" :active="request()->routeIs('bk.visit')">Data Home Visit</x-nav-link>
                    <x-nav-link href="{{ route('bk.perizinan') }}" icon="fa-clipboard-check" :active="request()->routeIs('bk.perizinan')">Approval Perizinan</x-nav-link>
                    <x-nav-link href="{{ route('bk.rekap_kehadiran') }}" icon="fa-calendar-days" :active="request()->routeIs('bk.rekap')">Rekap Kehadiran</x-nav-link>
                </div>
            </div>

        @elseif($role == 'absensi')
            <div class="mb-6">
                <p class="px-3 text-[10px] font-bold text-slate-500 uppercase tracking-widest mb-2.5">Menu Petugas</p>
                <div class="flex flex-col gap-1.5">
                    <x-nav-link href="{{ route('absen.dashboard') }}" icon="fa-chart-line" :active="request()->routeIs('absen.dashboard')">Dashboard</x-nav-link>
                    <x-nav-link href="{{ route('absensi.index') }}" icon="fa-calendar-check" :active="request()->routeIs('absensi.index')">Input Kehadiran</x-nav-link>
                </div>
            </div>

        @else
            <div class="mb-6">
                <p class="px-3 text-[10px] font-bold text-slate-500 uppercase tracking-widest mb-2.5">Menu Utama Admin</p>
                <div class="flex flex-col gap-1.5">
                    <x-nav-link href="{{ route('admin.dashboard') }}" icon="fa-chart-line" :active="request()->routeIs('admin.dashboard')">Dashboard</x-nav-link>
                    <x-nav-link href="{{ route('admin.kehadiran') }}" icon="fa-calendar-check" :active="request()->routeIs('admin.kehadiran')">Data Kehadiran</x-nav-link>
                    <x-nav-link href="{{ route('admin.siswa') }}" icon="fa-users" :active="request()->routeIs('admin.siswa')">Data Siswa</x-nav-link>                    <x-nav-link href="{{ route('admin.prestasi') }}" icon="fa-trophy" :active="request()->routeIs('admin.prestasi')">Data Prestasi</x-nav-link>
                    <x-nav-link href="{{ route('admin.pelanggaran') }}" icon="fa-gavel" :active="request()->routeIs('admin.pelanggaran')">Data Pelanggaran</x-nav-link>
                    <x-nav-link href="{{ route('admin.laporan') }}" icon="fa-file-lines" :active="request()->routeIs('admin.laporan')">Cetak Laporan</x-nav-link>
                </div>
            </div>

            <div class="mb-6">
                <p class="px-3 text-[10px] font-bold text-slate-500 uppercase tracking-widest mb-2.5 border-t border-navy-800 pt-5">System</p>
                <div class="flex flex-col gap-1.5">
                    <x-nav-link href="{{ route('admin.users') }}" icon="fa-user-gear" :active="request()->routeIs('admin.users')">Manajemen User</x-nav-link>
                    <x-nav-link href="{{ route('admin.pengaturan') }}" icon="fa-gear" :active="request()->routeIs('admin.pengaturan')">Pengaturan</x-nav-link>
                </div>
            </div>
        @endif
    </nav>

    <div class="p-5 border-t border-navy-800 flex-shrink-0 bg-navy-900">
        <form action="{{ route('logout') }}" method="POST">
            @csrf
            <button type="submit" class="w-full flex items-center justify-center gap-2 text-slate-400 hover:text-red-400 hover:bg-navy-800 py-3 rounded-xl transition-all text-xs font-bold uppercase tracking-wider group">
                <i class="fa-solid fa-power-off group-hover:animate-pulse"></i> Keluar Sistem
            </button>
        </form>
    </div>
</aside>
