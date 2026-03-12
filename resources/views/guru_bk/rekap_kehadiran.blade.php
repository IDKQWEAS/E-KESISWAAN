<x-layout-app title="Rekap Kehadiran" :role="$role">

    {{-- REVISI PADDING: pt-0 agar selaras dengan halaman lainnya --}}
    <div class="flex-1 overflow-y-auto px-6 pb-6 pt-0 lg:px-8 lg:pb-8 custom-scroll bg-[#f8fafc]">
        <div class="w-full space-y-6">

            {{-- HEADER & ACTIONS --}}
            <div class="bg-white p-4 lg:p-5 rounded-[20px] shadow-sm border border-slate-200 flex flex-col md:flex-row justify-between items-start md:items-center gap-4 overflow-hidden">
                <div class="flex items-center gap-3 flex-shrink-0">
                    <div>
                        <h3 class="font-bold text-lg lg:text-[20px] text-slate-800">Rekapitulasi Kehadiran Siswa</h3>
                        <p class="text-[11px] lg:text-xs text-slate-500 mt-0.5">Akumulasi data absensi realtime & perizinan.</p>
                    </div>
                </div>

                <div class="flex items-center gap-2 overflow-x-auto custom-scroll pb-1 md:pb-0 w-full md:w-auto">
                    {{-- Filter Kelas --}}
                    <div class="relative flex-shrink-0">
                        {{-- UBAH: Gunakan URLSearchParams agar parameter id_tahun_ajaran tidak hilang saat ganti kelas --}}
                        <select onchange="const urlParams = new URLSearchParams(window.location.search); urlParams.set('kelas', this.value); urlParams.set('page', 1); window.location.search = urlParams.toString();"
                            class="appearance-none bg-white border border-slate-200 rounded-xl py-2 pl-4 pr-9 text-xs font-bold text-slate-700 outline-none focus:ring-2 focus:ring-blue-500/20 cursor-pointer transition-all">
                            <option value="">Semua Kelas</option>
                            @foreach(['7A','7B','7C','7D','7E','7F','7G','8A','8B','8C','8D','8E','8F','8G','9A','9B','9C','9D','9E','9F','9G'] as $k)
                                <option value="{{ $k }}" {{ $kelasAktif === $k ? 'selected' : '' }}>Kelas {{ $k }}</option>
                            @endforeach
                        </select>
                        <i class="fa-solid fa-chevron-down absolute right-3 top-2.5 text-[10px] text-slate-400 pointer-events-none"></i>
                    </div>

                    {{-- Download Excel (Ambil semua parameter query termasuk tahun ajaran) --}}
                    <a href="{{ route('bk.rekap_kehadiran.excel', request()->query()) }}"
                       class="flex-shrink-0 bg-[#16a34a] hover:bg-green-700 text-white px-3 py-2 rounded-xl text-xs font-bold shadow-md shadow-green-200 transition flex items-center gap-1.5">
                        <i class="fa-solid fa-file-excel"></i> Unduh Excel
                    </a>

                    {{-- Download PDF (Ambil semua parameter query termasuk tahun ajaran) --}}
                    <a href="{{ route('bk.rekap_kehadiran.pdf', request()->query()) }}"
                       class="flex-shrink-0 bg-[#ef4444] hover:bg-red-700 text-white px-3 py-2 rounded-xl text-xs font-bold shadow-md shadow-red-200 transition flex items-center gap-1.5">
                        <i class="fa-solid fa-file-pdf"></i> Unduh PDF
                    </a>
                </div>
            </div>

            {{-- Flash Messages --}}
            @if(session('success'))
                <div class="bg-green-50 border border-green-200 text-green-700 px-5 py-3 rounded-xl text-sm font-medium flex items-center gap-2">
                    <i class="fa-solid fa-circle-check"></i> {{ session('success') }}
                </div>
            @endif
            @if(session('error'))
                <div class="bg-red-50 border border-red-200 text-red-600 px-5 py-3 rounded-xl text-sm font-medium flex items-center gap-2">
                    <i class="fa-solid fa-circle-exclamation"></i> {{ session('error') }}
                </div>
            @endif

            <div class="flex items-center gap-2 text-[11px] text-blue-600 bg-blue-50 border border-blue-200 rounded-xl px-4 py-2.5">
                <i class="fa-solid fa-circle-info"></i>
                <span>File export (PDF/Excel) akan otomatis mengikuti filter <strong>{{ $kelasAktif ? 'Kelas ' . $kelasAktif : 'Semua Kelas' }}</strong>.</span>
            </div>

            {{-- TABEL DATA --}}
            <div class="bg-white rounded-[20px] shadow-sm border border-slate-200 overflow-hidden w-full">

                <div class="p-4 border-b bg-slate-50 flex justify-between items-center">
                    <h4 class="font-bold text-xs text-slate-700">
                        Menampilkan: <span class="text-[#2563eb]">{{ $kelasAktif ? 'Kelas ' . $kelasAktif : 'Semua Kelas' }}</span>
                    </h4>
                    @if(!empty($pagination))
                        <span class="text-[10px] text-slate-400 font-bold uppercase tracking-wider">
                            Total {{ $pagination['total'] ?? 0 }} siswa
                        </span>
                    @endif
                </div>

                <div class="overflow-x-auto custom-scroll pb-2">
                    <table class="w-full text-left border-collapse min-w-max">
                        <thead class="bg-white border-b border-slate-100">
                            <tr>
                                <th class="py-3 pl-5 pr-3 text-[9px] font-bold text-slate-400 uppercase tracking-widest text-left">NISN</th>
                                <th class="py-3 px-3 text-[9px] font-bold text-slate-400 uppercase tracking-widest text-left">NAMA</th>
                                <th class="py-3 px-3 text-[9px] font-bold text-slate-400 uppercase tracking-widest text-center">KELAS</th>
                                <th class="py-3 px-3 text-[9px] font-bold text-slate-400 uppercase tracking-widest text-center">L/P</th>
                                <th class="py-3 px-3 text-[9px] font-bold text-green-500 uppercase tracking-widest text-center">HADIR</th>
                                <th class="py-3 px-3 text-[9px] font-bold text-blue-400 uppercase tracking-widest text-center">SAKIT</th>
                                <th class="py-3 px-3 text-[9px] font-bold text-orange-400 uppercase tracking-widest text-center">IZIN</th>
                                <th class="py-3 px-3 text-[9px] font-bold text-red-500 uppercase tracking-widest text-center">ALPHA</th>
                                <th class="py-3 pr-5 pl-3 text-[9px] font-bold text-purple-500 uppercase tracking-widest text-center">TERLAMBAT</th>
                            </tr>
                        </thead>
                        <tbody class="divide-y divide-slate-50">
                            @forelse($absensi as $siswa)
                                <tr class="hover:bg-slate-50 transition group">
                                    <td class="py-3 pl-5 pr-3 font-mono text-xs text-slate-400">{{ $siswa['nisn'] ?? '-' }}</td>
                                    <td class="py-3 px-3 font-bold text-slate-800 text-xs group-hover:text-[#2563eb] transition whitespace-nowrap">{{ $siswa['nama'] ?? '-' }}</td>
                                    <td class="py-3 px-3 text-center">
                                        <span class="bg-slate-100 text-slate-600 px-2 py-1 rounded text-[10px] font-bold">{{ $siswa['kelas'] ?? '-' }}</span>
                                    </td>
                                    <td class="py-3 px-3 text-center font-bold text-[11px] {{ ($siswa['jenis_kelamin'] ?? '') === 'L' ? 'text-blue-500' : 'text-pink-500' }}">
                                        {{ $siswa['jenis_kelamin'] ?? '-' }}
                                    </td>
                                    <td class="py-3 px-3 text-center font-bold text-green-600 bg-green-50/30 text-xs">
                                        {{ intval($siswa['total_hadir'] ?? 0) }}
                                    </td>
                                    <td class="py-3 px-3 text-center text-blue-500 font-medium text-xs">
                                        {{ intval($siswa['total_sakit'] ?? 0) }}
                                    </td>
                                    <td class="py-3 px-3 text-center text-orange-500 font-medium text-xs">
                                        {{ intval($siswa['total_izin'] ?? 0) }}
                                    </td>
                                    <td class="py-3 px-3 text-center text-xs {{ intval($siswa['total_alpha'] ?? 0) > 0 ? 'font-bold text-red-600 bg-red-50/50' : 'text-red-300' }}">
                                        {{ intval($siswa['total_alpha'] ?? 0) }}
                                    </td>
                                    <td class="py-3 pr-5 pl-3 text-center text-xs {{ intval($siswa['total_terlambat'] ?? 0) > 0 ? 'font-bold text-purple-600' : 'text-purple-300' }}">
                                        {{ intval($siswa['total_terlambat'] ?? 0) }}
                                    </td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="9" class="text-center p-10 text-slate-400 text-sm">
                                        <i class="fa-regular fa-folder-open text-3xl mb-2 block"></i>
                                        Tidak ada data absensi untuk kelas ini.
                                    </td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>

                {{-- Pagination --}}
                @if(!empty($pagination) && ($pagination['totalPages'] ?? 1) > 1)
                    <div class="flex flex-col md:flex-row justify-between items-center p-4 border-t border-slate-100">
                        <p class="text-slate-500 text-xs mb-3 md:mb-0">
                            Halaman {{ $pagination['page'] ?? 1 }} dari {{ $pagination['totalPages'] ?? 1 }}
                        </p>
                        <div class="flex items-center gap-1.5">
                            @if(($pagination['page'] ?? 1) > 1)
                                <a href="{{ route('bk.rekap_kehadiran', array_merge(request()->query(), ['page' => $pagination['page'] - 1])) }}"
                                   class="px-3 py-1.5 border border-slate-200 rounded-lg text-slate-600 text-[11px] font-bold hover:bg-slate-50 transition">Prev</a>
                            @endif

                            @for($p = 1; $p <= ($pagination['totalPages'] ?? 1); $p++)
                                <a href="{{ route('bk.rekap_kehadiran', array_merge(request()->query(), ['page' => $p])) }}"
                                   class="w-7 h-7 rounded-lg border text-[11px] font-bold flex items-center justify-center transition
                                   {{ $p == ($pagination['page'] ?? 1) ? 'bg-[#2563eb] text-white border-blue-600 shadow-md shadow-blue-200' : 'bg-white border-slate-200 text-slate-600 hover:bg-slate-50' }}">
                                    {{ $p }}
                                </a>
                            @endfor

                            @if(($pagination['page'] ?? 1) < ($pagination['totalPages'] ?? 1))
                                <a href="{{ route('bk.rekap_kehadiran', array_merge(request()->query(), ['page' => $pagination['page'] + 1])) }}"
                                   class="px-3 py-1.5 border border-slate-200 rounded-lg text-slate-600 text-[11px] font-bold hover:bg-slate-50 transition">Next</a>
                            @endif
                        </div>
                    </div>
                @endif

            </div>
        </div>
    </div>

</x-layout-app>
