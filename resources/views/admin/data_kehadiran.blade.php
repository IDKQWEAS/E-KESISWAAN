<x-layout-app title="Data Kehadiran" role="admin">
    {{-- Padding dikurangi dari p-8 ke p-6 --}}
    <div class="flex-1 overflow-y-auto p-6 bg-slate-50 custom-scroll">
        {{-- Border radius dikurangi dari [20px] ke [16px]/xl, padding p-8 ke p-6 --}}
        <div class="bg-white rounded-2xl border border-slate-200 shadow-sm p-6">

            {{-- Header & Filter Section (Gap 6 ke 5, MB 8 ke 6) --}}
            <div class="flex flex-col xl:flex-row justify-between items-start xl:items-center gap-5 mb-6">
                <div>
                    {{-- Text 2xl ke xl --}}
                    <h1 class="text-xl font-bold text-slate-800 mb-1">Rekap Data Kehadiran</h1>
                    {{-- Text sm ke xs --}}
                    <p class="text-slate-500 text-xs font-medium">Tahun Ajaran: {{ $taAktif['tahun_ajaran'] ?? '-' }} ({{ $taAktif['semester'] ?? '-' }})</p>
                </div>

                <form action="{{ route('admin.kehadiran') }}" method="GET" class="flex flex-wrap items-center gap-2.5">
                 

                    {{-- Filter Tanggal --}}
                    <input type="date" name="tanggal" value="{{ request('tanggal', now()->format('Y-m-d')) }}" onchange="this.form.submit()" class="bg-white border border-slate-200 rounded-lg py-2 px-3 text-xs font-bold text-slate-600 outline-none shadow-sm">

                    {{-- Filter Kelas --}}
                    <select name="kelas" onchange="this.form.submit()" class="bg-white border border-slate-200 rounded-lg py-2 px-3 text-xs font-bold text-slate-600 outline-none shadow-sm cursor-pointer">
                       <option value="">Semua Kelas</option>

                        @foreach([7,8,9] as $tingkat)
                            @foreach(range('A', 'G') as $huruf)
                                @php $kls = $tingkat . $huruf; @endphp

                                <option value="{{ $kls }}" {{ request('kelas') == $kls ? 'selected' : '' }}>
                                    Kelas {{ $kls }}
                                </option>
                            @endforeach
                        @endforeach
                    </select>

                    {{-- Filter Status --}}
                    <select name="status" onchange="this.form.submit()" class="bg-white border border-slate-200 rounded-lg py-2 px-3 text-xs font-bold text-slate-600 outline-none shadow-sm cursor-pointer">
                        <option value="">Semua Status</option>
                        <option value="tepat_waktu" {{ request('status') == 'tepat_waktu' ? 'selected' : '' }}>Tepat Waktu</option>
                        <option value="terlambat" {{ request('status') == 'terlambat' ? 'selected' : '' }}>Terlambat</option>
                    </select>
                </form>
            </div>

            {{-- Table Section --}}
            <div class="overflow-x-auto">
                <table class="w-full text-left">
                    <thead>
                        {{-- Text [11px] ke [9px] --}}
                        <tr class="text-slate-400 text-[9px] font-black uppercase tracking-widest border-b border-slate-100">
                            <th class="pb-3 pl-3">No</th>
                            <th class="pb-3">Nama Siswa</th>
                            <th class="pb-3">Kelas</th>
                            <th class="pb-3">Jam Absen</th>
                            <th class="pb-3">Status</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-slate-50">
                        @forelse($dataKehadiran as $index => $item)
                        <tr class="hover:bg-slate-50 transition">
                            {{-- Text sm ke xs, Padding py-4 ke py-3 --}}
                            <td class="py-3 pl-3 text-xs font-bold text-slate-400">{{ $index + 1 }}</td>
                            
                            <td class="py-3 text-xs font-bold text-slate-700 uppercase">
                                {{ $item['nama'] ?? '-' }}
                            </td>
                            
                            <td class="py-3">
                                {{-- Text [10px] ke [8px] --}}
                                <span class="px-1.5 py-0.5 bg-slate-100 text-slate-600 rounded text-[8px] font-black uppercase tracking-wider">
                                    {{ $item['kelas'] ?? '-' }}
                                </span>
                            </td>

                            <td class="py-3 text-xs font-bold text-slate-500 font-mono">
                                {{ isset($item['created_at']) ? \Carbon\Carbon::parse($item['created_at'])->timezone('Asia/Jakarta')->format('H:i') : '-' }}
                            </td>
                            <td class="py-3">
                                {{-- Text [10px] ke [9px] --}}
                                <span class="px-2.5 py-1 rounded-full text-[9px] font-black uppercase tracking-wide {{ ($item['status'] ?? '') == 'tepat waktu' ? 'bg-green-50 text-green-600 border border-green-100' : 'bg-red-50 text-red-600 border border-red-100' }}">
                                    {{ $item['status'] ?? 'Terlambat' }}
                                </span>
                            </td>
                        </tr>
                        @empty
                        <tr>
                            <td colspan="5" class="py-16 text-center text-slate-400 italic text-[10px] uppercase font-bold tracking-widest opacity-50">
                                Data kehadiran tidak ditemukan.
                            </td>
                        </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</x-layout-app>