<x-layout-app title="Rekap Kehadiran" :role="$role">

    <div class="flex-1 overflow-y-auto px-4 pb-4 pt-0 lg:px-6 lg:pb-6 custom-scroll bg-[#f8fafc] fade-in">
        <div class="w-full space-y-4">

            {{-- ── HEADER & ACTIONS ────────────────────────────────────────── --}}
            <div class="bg-white p-4 lg:p-5 rounded-2xl shadow-sm border border-slate-200 flex flex-col md:flex-row justify-between items-start md:items-center gap-4 overflow-hidden">
                <div class="flex items-center gap-3 flex-shrink-0">
                    <div>
                        <h3 class="font-bold text-base text-slate-800">Rekapitulasi Kehadiran Siswa</h3>
                        <p class="text-[11px] text-slate-500 mt-0.5">Akumulasi data absensi realtime & perizinan.</p>
                    </div>
                </div>

                {{-- Container Kanan: Filter & Tombol Aksi --}}
                <div class="flex items-center gap-2 overflow-x-auto custom-scroll pb-1 md:pb-0 w-full md:w-auto">

                    {{-- Form Filter Ganda: Kelas & Bulan Berdampingan --}}
                    <form id="filterFormRekap" method="GET" action="{{ route('bk.rekap_kehadiran') }}" class="flex items-center gap-2 flex-shrink-0">
                        
                        {{-- Dropdown Pilihan Kelas --}}
                        <div class="relative group">
                            <select name="kelas" class="auto-submit appearance-none bg-white border border-slate-200 rounded-lg py-1.5 pl-3 pr-8 text-[11px] font-bold text-slate-700 outline-none focus:ring-2 focus:ring-blue-500/20 cursor-pointer transition-all h-[32px] min-w-[120px]">
                                <option value="">Semua Kelas</option>
                                @foreach(['7A','7B','7C','7D','7E','7F','7G','8A','8B','8C','8D','8E','8F','8G','9A','9B','9C','9D','9E','9F','9G'] as $k)
                                    <option value="{{ $k }}" {{ $kelasAktif === $k ? 'selected' : '' }}>Kelas {{ $k }}</option>
                                @endforeach
                            </select>
                            <i class="fa-solid fa-chevron-down absolute right-3 top-1/2 -translate-y-1/2 text-[9px] text-slate-400 pointer-events-none"></i>
                        </div>

                        {{-- Dropdown Pilihan Bulan (Di Sebelah Kanan Kelas) --}}
                        <div class="relative group">
                            <select name="bulan" class="auto-submit appearance-none bg-white border border-slate-200 rounded-lg py-1.5 pl-3 pr-8 text-[11px] font-bold text-slate-700 outline-none focus:ring-2 focus:ring-blue-500/20 cursor-pointer transition-all h-[32px] min-w-[120px]">
                                @foreach([
                                    1 => 'Januari', 2 => 'Februari', 3 => 'Maret', 4 => 'April', 5 => 'Mei', 6 => 'Juni',
                                    7 => 'Juli', 8 => 'Agustus', 9 => 'September', 10 => 'Oktober', 11 => 'November', 12 => 'Desember'
                                ] as $num => $name)
                                    <option value="{{ $num }}" {{ intval($bulanAktif) === $num ? 'selected' : '' }}>{{ $name }}</option>
                                @endforeach
                            </select>
                            <i class="fa-solid fa-chevron-down absolute right-3 top-1/2 -translate-y-1/2 text-[9px] text-slate-400 pointer-events-none"></i>
                        </div>

                    </form>

                    {{-- Tombol Download Excel --}}
                    <a id="btn-excel" href="{{ route('bk.rekap_kehadiran.excel', request()->query()) }}"
                       class="flex-shrink-0 bg-[#16a34a] hover:bg-green-700 text-white px-3 py-1.5 rounded-lg text-[11px] font-bold shadow-sm transition flex items-center justify-center gap-1.5 h-[32px]">
                        <i class="fa-solid fa-file-excel"></i> Unduh Excel
                    </a>

                    {{-- Tombol Download PDF --}}
                    <a id="btn-pdf" href="{{ route('bk.rekap_kehadiran.pdf', request()->query()) }}"
                       class="flex-shrink-0 bg-[#ef4444] hover:bg-red-700 text-white px-3 py-1.5 rounded-lg text-[11px] font-bold shadow-sm transition flex items-center justify-center gap-1.5 h-[32px]">
                        <i class="fa-solid fa-file-pdf"></i> Unduh PDF
                    </a>
                </div>
            </div>

            {{-- ── NOTIFIKASI FLASH MESSAGES ────────────────────────────────── --}}
            @if(session('success'))
                <div class="bg-green-50 border border-green-200 text-green-700 px-4 py-2.5 rounded-lg text-[11px] font-bold flex items-center gap-2">
                    <i class="fa-solid fa-circle-check"></i> {{ session('success') }}
                </div>
            @endif
            @if(session('error'))
                <div class="bg-red-50 border border-red-200 text-red-600 px-4 py-2.5 rounded-lg text-[11px] font-bold flex items-center gap-2">
                    <i class="fa-solid fa-circle-exclamation"></i> {{ session('error') }}
                </div>
            @endif

            <div class="flex items-center gap-2 text-[10px] text-blue-600 bg-blue-50 border border-blue-200 rounded-xl px-4 py-2.5">
                <i class="fa-solid fa-circle-info"></i>
                <span id="export-info">File export (PDF/Excel) otomatis mengikuti filter <strong>{{ $kelasAktif ? 'Kelas ' . $kelasAktif : 'Semua Kelas' }}</strong> pada bulan <strong>{{ [1=>'Januari',2=>'Februari',3=>'Maret',4=>'April',5=>'Mei',6=>'Juni',7=>'Juli',8=>'Agustus',9=>'September',10=>'Oktober',11=>'November',12=>'Desember'][intval($bulanAktif)] }}</strong>.</span>
            </div>

            {{-- ── TABEL DATA MATRIKS REKAPITULASI ──────────────────────────── --}}
            <div id="table-container" class="bg-white rounded-2xl shadow-sm border border-slate-200 overflow-hidden w-full transition-opacity duration-300">

                <div class="p-3 lg:p-4 border-b bg-slate-50 flex justify-between items-center">
                    <h4 class="font-bold text-[13px] text-slate-700">
                        Menampilkan: <span class="text-[#2563eb]">{{ $kelasAktif ? 'Kelas ' . $kelasAktif : 'Semua Kelas' }}</span>
                    </h4>
                    @if(!empty($pagination))
                        <span class="text-[10px] text-slate-400 font-bold uppercase tracking-wider">
                            Total {{ $pagination['total'] ?? 0 }} siswa
                        </span>
                    @endif
                </div>

                <div class="overflow-x-auto custom-scroll pb-1">
                    <table class="w-full text-left border-collapse min-w-max">
                        <thead class="bg-white border-b border-slate-100">
                            <tr>
                                <th class="py-2.5 pl-4 pr-3 text-[9px] font-bold text-slate-400 uppercase tracking-widest text-left">NISN</th>
                                <th class="py-2.5 px-3 text-[9px] font-bold text-slate-400 uppercase tracking-widest text-left">NAMA</th>
                                <th class="py-2.5 px-3 text-[9px] font-bold text-slate-400 uppercase tracking-widest text-center">KELAS</th>
                                <th class="py-2.5 px-3 text-[9px] font-bold text-slate-400 uppercase tracking-widest text-center">L/P</th>
                                
                                {{-- Looping Header Kalender Tanggal 1 - 31 --}}
                                @for($i = 1; $i <= 31; $i++)
                                    <th class="py-2.5 px-1 text-[9px] font-bold text-slate-400 uppercase text-center min-w-[28px] border-x border-slate-50">{{ $i }}</th>
                                @endfor

                                {{-- Header Ringkasan Akumulasi Total --}}
                                <th class="py-2.5 px-3 text-[9px] font-bold text-green-500 uppercase tracking-widest text-center">HADIR</th>
                                <th class="py-2.5 px-3 text-[9px] font-bold text-blue-400 uppercase tracking-widest text-center">SAKIT</th>
                                <th class="py-2.5 px-3 text-[9px] font-bold text-orange-400 uppercase tracking-widest text-center">IZIN</th>
                                <th class="py-2.5 px-3 text-[9px] font-bold text-red-500 uppercase tracking-widest text-center">ALPHA</th>
                                <th class="py-2.5 pr-4 pl-3 text-[9px] font-bold text-purple-500 uppercase tracking-widest text-center">TERLAMBAT</th>
                            </tr>
                        </thead>
                        <tbody class="divide-y divide-slate-50">
                            @forelse($absensi as $siswa)
                                <tr class="hover:bg-slate-50 transition group">
                                    <td class="py-2.5 pl-4 pr-3 font-mono text-[11px] text-slate-400">{{ $siswa['nisn'] ?? '-' }}</td>
                                    <td class="py-2.5 px-3 font-bold text-slate-800 text-[11px] group-hover:text-[#2563eb] transition whitespace-nowrap">{{ $siswa['nama'] ?? '-' }}</td>
                                    <td class="py-2.5 px-3 text-center">
                                        <span class="bg-slate-100 text-slate-600 px-1.5 py-0.5 rounded text-[9px] font-bold">{{ $siswa['kelas'] ?? '-' }}</span>
                                    </td>
                                    <td class="py-2.5 px-3 text-center font-bold text-[10px] {{ ($siswa['jenis_kelamin'] ?? '') === 'L' ? 'text-blue-500' : 'text-pink-500' }}">
                                        {{ $siswa['jenis_kelamin'] ?? '-' }}
                                    </td>

                                    {{-- OUTPUT MATRIKS STATUS HARIAN (H / I / S / A) CLEAN TEXT --}}
                                    @for($i = 1; $i <= 31; $i++)
                                        @php 
                                            $statusHari = trim(strtoupper($siswa['d' . $i] ?? '-')); 
                                        @endphp
                                        
                                        <td class="py-2.5 px-1 text-center text-[12px] font-extrabold border-x border-slate-50
                                            @if($statusHari === 'H') text-green-500
                                            @elseif($statusHari === 'S') text-blue-500
                                            @elseif($statusHari === 'I') text-orange-500
                                            @elseif($statusHari === 'A') text-red-500
                                            @else text-slate-300 font-normal @endif">
                                            
                                            {{ $statusHari === 'H' ? '✓' : $statusHari }}
                                        </td>
                                    @endfor
    

                                    {{-- Kolom Penghitungan Total Akumulasi --}}
                                    <td class="py-2.5 px-3 text-center font-bold text-green-600 bg-green-50/30 text-[11px]">{{ intval($siswa['total_hadir'] ?? 0) }}</td>
                                    <td class="py-2.5 px-3 text-center text-blue-500 font-medium text-[11px]">{{ intval($siswa['total_sakit'] ?? 0) }}</td>
                                    <td class="py-2.5 px-3 text-center text-orange-500 font-medium text-[11px]">{{ intval($siswa['total_izin'] ?? 0) }}</td>
                                    <td class="py-2.5 px-3 text-center text-[11px] {{ intval($siswa['total_alpha'] ?? 0) > 0 ? 'font-bold text-red-600 bg-red-50/50' : 'text-red-300' }}">{{ intval($siswa['total_alpha'] ?? 0) }}</td>
                                    <td class="py-2.5 pr-4 pl-3 text-center text-[11px] {{ intval($siswa['total_terlambat'] ?? 0) > 0 ? 'font-bold text-purple-600' : 'text-purple-300' }}">{{ intval($siswa['total_terlambat'] ?? 0) }}</td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="40" class="text-center p-8 text-slate-400 text-[11px]">
                                        <i class="fa-regular fa-folder-open text-3xl mb-2 block text-slate-300"></i>
                                        Tidak ada data rekapitulasi absensi pada kombinasi filter ini.
                                    </td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>

                {{-- ── LAYOUT NAVIGASI PAGINASI DATA ──────────────────────────── --}}
                @if(!empty($pagination) && ($pagination['totalPages'] ?? 1) > 1)
                    <div class="flex flex-col md:flex-row justify-between items-center p-3 lg:p-4 border-t border-slate-100 bg-white">
                        <p class="text-slate-500 text-[10px] mb-3 md:mb-0">
                            Halaman <span class="font-bold">{{ $pagination['page'] ?? 1 }}</span> dari <span class="font-bold">{{ $pagination['totalPages'] ?? 1 }}</span>
                            &mdash; Total <span class="font-bold">{{ $pagination['total'] ?? 0 }}</span> data
                        </p>
                        <div class="flex items-center gap-1 flex-wrap justify-center">
                            @if(($pagination['page'] ?? 1) > 1)
                                <a href="{{ route('bk.rekap_kehadiran', array_merge(request()->query(), ['page' => $pagination['page'] - 1])) }}"
                                   class="ajax-link px-2.5 py-1.5 border border-slate-200 rounded text-slate-600 text-[10px] font-bold hover:bg-slate-50 transition">Prev</a>
                            @else
                                <button disabled class="px-2.5 py-1.5 border border-slate-200 rounded text-slate-400 text-[10px] font-bold opacity-50 cursor-not-allowed">Prev</button>
                            @endif

                            @php
                                $currentPage = $pagination['page'] ?? 1;
                                $lastPage    = $pagination['totalPages'] ?? 1;
                                $start       = max(1, $currentPage - 2);
                                $end         = min($lastPage, $currentPage + 2);

                                if ($start === 1) { $end = min(5, $lastPage); } 
                                elseif ($end === $lastPage) { $start = max(1, $lastPage - 4); }
                            @endphp

                            @if($start > 1)
                                <a href="{{ route('bk.rekap_kehadiran', array_merge(request()->query(), ['page' => 1])) }}" class="ajax-link w-7 h-7 rounded border border-slate-200 text-slate-600 hover:bg-slate-50 text-[10px] font-bold flex items-center justify-center transition">1</a>
                                @if($start > 2) <span class="text-slate-400 text-[10px] px-1">...</span> @endif
                            @endif

                            @for($p = $start; $p <= $end; $p++)
                                <a href="{{ route('bk.rekap_kehadiran', array_merge(request()->query(), ['page' => $p])) }}"
                                   class="ajax-link w-7 h-7 rounded border text-[10px] font-bold flex items-center justify-center transition
                                   {{ $p == $currentPage ? 'bg-[#2563eb] text-white border-blue-600 shadow-sm' : 'bg-white border-slate-200 text-slate-600 hover:bg-slate-50' }}">
                                    {{ $p }}
                                </a>
                            @endfor

                            @if($end < $lastPage)
                                @if($end < $lastPage - 1) <span class="text-slate-400 text-[10px] px-1">...</span> @endif
                                <a href="{{ route('bk.rekap_kehadiran', array_merge(request()->query(), ['page' => $lastPage])) }}" class="ajax-link w-7 h-7 rounded border border-slate-200 text-slate-600 hover:bg-slate-50 text-[10px] font-bold flex items-center justify-center transition">{{ $lastPage }}</a>
                            @endif

                            @if($currentPage < $lastPage)
                                <a href="{{ route('bk.rekap_kehadiran', array_merge(request()->query(), ['page' => $currentPage + 1])) }}"
                                   class="ajax-link px-2.5 py-1.5 border border-slate-200 rounded text-slate-600 text-[10px] font-bold hover:bg-slate-50 transition">Next</a>
                            @else
                                <button disabled class="px-2.5 py-1.5 border border-slate-200 rounded text-slate-400 text-[10px] font-bold opacity-50 cursor-not-allowed">Next</button>
                            @endif
                        </div>
                    </div>
                @endif

            </div>
        </div>
    </div>

    @push('scripts')
    <script>
        document.addEventListener('DOMContentLoaded', () => {
            // Deteksi Perubahan Otomatis pada Dropdown (Kelas / Bulan)
            document.addEventListener('change', e => {
                if (e.target.matches('.auto-submit')) {
                    const form = e.target.closest('form');
                    if (form) executeAjaxFilter(form);
                }
            });

            // Antisipasi jika form disubmit manual
            document.addEventListener('submit', e => {
                const form = e.target;
                if (form.id === 'filterFormRekap') {
                    e.preventDefault();
                    executeAjaxFilter(form);
                }
            });

            // Interseptasi Klik Navigasi Pagination Links
            document.addEventListener('click', e => {
                const link = e.target.closest('.ajax-link');
                if (link) {
                    e.preventDefault();
                    reloadTableData(link.href);
                }
            });
        });

        function executeAjaxFilter(form) {
            const url = new URL(form.action);
            const params = new URLSearchParams(window.location.search);
            const formData = new FormData(form);

            for (const [key, value] of formData.entries()) {
                if (value) params.set(key, value);
                else params.delete(key);
            }
            params.set('page', 1); // Reset paksa ke halaman 1 setiap ganti filter

            reloadTableData(url.pathname + '?' + params.toString());
        }

        async function reloadTableData(url) {
            const container = document.getElementById('table-container');
            container.style.opacity = '0.5';
            container.style.pointerEvents = 'none';

            try {
                const res = await fetch(url, { headers: { 'X-Requested-With': 'XMLHttpRequest' } });
                const html = await res.text();
                const doc = new DOMParser().parseFromString(html, 'text/html');

                // 1. Sinkronisasi Konten Kontainer Tabel
                const newContainer = doc.getElementById('table-container');
                if (newContainer) {
                    container.innerHTML = newContainer.innerHTML;
                    window.history.pushState({}, '', url);
                } else {
                    window.location.href = url;
                }

                // 2. Sinkronisasi Target Rute URL Download File Ekspor (Excel, PDF, & Label)
                const newExcel = doc.getElementById('btn-excel');
                const newPdf   = doc.getElementById('btn-pdf');
                const newInfo  = doc.getElementById('export-info');

                if(newExcel) document.getElementById('btn-excel').href = newExcel.href;
                if(newPdf)   document.getElementById('btn-pdf').href   = newPdf.href;
                if(newInfo)  document.getElementById('export-info').innerHTML = newInfo.innerHTML;

            } catch (e) {
                console.error("Gagal melakukan reload parsial data:", e);
            } finally {
                container.style.opacity = '1';
                container.style.pointerEvents = 'auto';
            }
        }
    </script>
    @endpush
</x-layout-app>