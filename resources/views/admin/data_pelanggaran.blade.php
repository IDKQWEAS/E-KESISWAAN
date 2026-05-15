<x-layout-app title="Data Pelanggaran" role="admin">
 

    <div class="flex-1 overflow-y-auto p-6 custom-scroll bg-[#f8fafc]">
        <div class="mx-auto space-y-5">

            {{-- FILTER PANEL --}}
            <div class="bg-white rounded-[16px] border border-slate-200 shadow-sm p-5 flex flex-col xl:flex-row justify-between items-center gap-4">
                <div>
                    <h1 class="text-lg font-bold text-slate-800">Database Pelanggaran</h1>
                    <p class="text-slate-500 text-[11px] mt-0.5 uppercase font-bold tracking-wider">
                        Periode: <span class="text-blue-600">{{ $taAktif['tahun_ajaran'] ?? '-' }} {{ $taAktif['semester'] ?? '' }}</span>
                    </p>
                </div>

                <div class="flex items-center gap-3 w-full xl:w-auto">
                    <div class="relative flex-1 xl:w-64">
                        <i class="fa-solid fa-magnifying-glass absolute left-3.5 top-1/2 -translate-y-1/2 text-slate-400 text-xs"></i>
                        <input type="text" id="liveSearchInput" placeholder="Cari Nama Siswa..." 
                            class="w-full pl-10 pr-4 h-11 bg-slate-50 border border-slate-200 rounded-xl text-xs font-bold outline-none focus:ring-4 focus:ring-blue-500/10 focus:border-blue-500 transition-all">
                    </div>
                    <select id="filterKelas" class="h-11 px-4 bg-slate-50 border border-slate-200 rounded-xl text-xs font-bold outline-none focus:border-blue-500 cursor-pointer">
                        <option value="">Semua Kelas</option>
                        @foreach(['7A','7B','7C','7D','7E','7F','7G','8A','8B','8C','8D','8E','8F','8G','9A','9B','9C','9D','9E','9F','9G'] as $k)
                            <option value="{{ $k }}">{{ $k }}</option>
                        @endforeach
                    </select>
                </div>
            </div>

            {{-- TABEL DATA --}}
            <div class="bg-white rounded-[16px] border border-slate-200 shadow-sm overflow-hidden">
                <div class="overflow-x-auto">
                    <table class="w-full text-left border-collapse">
                        <thead>
                            <tr class="bg-slate-50/50 border-b border-slate-100">
                                <th class="py-4 px-6 text-[10px] font-bold text-slate-400 uppercase tracking-widest">Siswa</th>
                                <th class="py-4 px-4 text-[10px] font-bold text-slate-400 uppercase tracking-widest text-center">Kelas</th>
                                <th class="py-4 px-4 text-[10px] font-bold text-slate-400 uppercase tracking-widest">Jenis Pelanggaran</th>
                                <th class="py-4 px-4 text-[10px] font-bold text-slate-400 uppercase tracking-widest">Kronologi</th>
                                <th class="py-4 px-4 text-[10px] font-bold text-slate-400 uppercase tracking-widest text-center">Poin</th>
                                <th class="py-4 px-6 text-[10px] font-bold text-slate-400 uppercase tracking-widest text-right">Tanggal</th>
                            </tr>
                        </thead>
                        <tbody id="tableBody">
                            @forelse($dataPelanggaran as $p)
                                <tr class="hover:bg-slate-50 transition-colors border-b border-slate-50 last:border-0 group table-row-item" 
                                    data-kelas="{{ $p['kelas'] ?? '' }}">
                                    
                                    <td class="px-6 py-4">
                                        <div class="flex flex-col">
                                            <span class="text-sm font-bold text-slate-800 searchable-name">{{ $p['nama_siswa'] ?? 'Siswa' }}</span>
                                            <span class="text-[10px] font-semibold text-slate-400 uppercase tracking-tighter">NISN: {{ $p['nisn'] ?? '-' }}</span>
                                        </div>
                                    </td>

                                    <td class="px-4 py-4 text-center">
                                        <span class="px-2 py-1 bg-slate-100 text-slate-600 rounded text-[10px] font-black tracking-tighter">{{ $p['kelas'] ?? '-' }}</span>
                                    </td>

                                    <td class="px-4 py-4">
                                        <span class="text-xs font-bold text-slate-700">{{ $p['pelanggaran'] ?? '-' }}</span>
                                    </td>

                                    <td class="px-4 py-4 max-w-[250px]">
                                        <p class="text-[11px] text-slate-500 italic truncate">{{ $p['keterangan'] ?? '-' }}</p>
                                    </td>

                                    <td class="px-4 py-4 text-center">
                                        <span class="inline-flex items-center px-2.5 py-1 rounded-full bg-red-50 text-red-600 text-xs font-black border border-red-100">
                                            +{{ $p['poin'] ?? '0' }}
                                        </span>
                                    </td>

                                    <td class="px-6 py-4 text-right">
                                        <div class="flex flex-col">
                                            <span class="text-xs font-bold text-slate-700">
                                                {{ isset($p['tanggal']) ? \Carbon\Carbon::parse($p['tanggal'])->format('d M Y') : '-' }}
                                            </span>
                                            <span class="text-[10px] font-medium text-slate-400 uppercase">Terpantau</span>
                                        </div>
                                    </td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="6" class="py-20 text-center">
                                        <i class="fa-solid fa-clipboard-check text-slate-200 text-5xl mb-4"></i>
                                        <p class="text-slate-400 text-sm font-bold uppercase tracking-widest">Belum ada catatan pelanggaran.</p>
                                    </td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>

    @push('scripts')
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            const searchInput = document.getElementById('liveSearchInput');
            const filterKelas = document.getElementById('filterKelas');
            const rows = document.querySelectorAll('.table-row-item');

            function filter() {
                const term = searchInput.value.toLowerCase();
                const selectedKelas = filterKelas.value;

                rows.forEach(row => {
                    const name = row.querySelector('.searchable-name').textContent.toLowerCase();
                    const rowKelas = row.getAttribute('data-kelas');
                    
                    const matchName = name.includes(term);
                    const matchKelas = selectedKelas === "" || rowKelas === selectedKelas;

                    row.style.display = (matchName && matchKelas) ? '' : 'none';
                });
            }

            searchInput.addEventListener('keyup', filter);
            filterKelas.addEventListener('change', filter);
        });
    </script>
    @endpush
</x-layout-app>