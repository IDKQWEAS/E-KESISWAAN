<x-layout-app title="Monitoring Absensi" :role="$role">

    <div class="w-full space-y-4 fade-in pb-8">

        {{-- Filter --}}
        <div class="bg-white p-4 rounded-2xl shadow-sm border border-slate-200 flex flex-col md:flex-row justify-between items-start md:items-center gap-4">
            <div>
                <h3 class="font-bold text-sm uppercase text-slate-700">Filter Rekapitulasi</h3>
                <p class="text-[11px] text-slate-500 mt-0.5">Tentukan parameter untuk menampilkan data.</p>
            </div>

            <form id="filterFormAbsensi" method="GET" action="{{ route('kepsek.absensi') }}" class="flex flex-wrap items-center gap-2">

                {{-- Preserve id_tahun_ajaran dari header global --}}
                @if($reqIdTa)
                    <input type="hidden" name="id_tahun_ajaran" value="{{ $reqIdTa }}">
                @endif

                <div class="relative">
                    <select name="kelas"
                        class="auto-submit appearance-none border border-slate-200 pl-3 pr-8 py-1.5 rounded-lg text-[11px] font-bold text-slate-600 bg-slate-50 outline-none cursor-pointer focus:ring-2 focus:ring-blue-500/20 h-[32px]">
                        <option value="">Semua Kelas</option>
                        @foreach(['7','8','9'] as $tingkat)
                            @foreach(['A','B','C','D','E','F','G'] as $huruf)
                                @php $kls = $tingkat . $huruf; @endphp
                                <option value="{{ $kls }}" {{ $kelasAktif === $kls ? 'selected' : '' }}>
                                    Kelas {{ $kls }}
                                </option>
                            @endforeach
                        @endforeach
                    </select>
                    <i class="fa-solid fa-chevron-down absolute right-3 top-1/2 -translate-y-1/2 text-[9px] text-slate-400 pointer-events-none"></i>
                </div>

                @if($kelasAktif)
                    <a href="{{ route('kepsek.absensi', $reqIdTa ? ['id_tahun_ajaran' => $reqIdTa] : []) }}"
                       class="px-3 py-1.5 text-[11px] text-slate-500 bg-white border border-slate-200 rounded-lg hover:bg-slate-50 font-bold h-[32px] flex items-center">
                        Reset
                    </a>
                @endif
            </form>
        </div>

        {{-- BUNGKUS DENGAN AJAX ID --}}
        <div id="table-container" class="transition-opacity duration-300">

            {{-- Tabel --}}
            <div class="bg-white rounded-2xl border border-slate-200 shadow-sm overflow-hidden">

                <div class="p-3 lg:p-4 border-b bg-slate-50 flex justify-between items-center">
                    <h4 class="font-bold text-[13px] text-slate-700">
                        Menampilkan:
                        <span class="text-[#2563eb]">{{ $kelasAktif ? 'Kelas ' . $kelasAktif : 'Semua Kelas' }}</span>
                        @if($taString)
                            &mdash; <span class="text-slate-400 font-normal">TA {{ $taString }}</span>
                        @endif
                    </h4>
                    @if(!empty($pagination))
                        <span class="text-[10px] text-slate-400 font-bold uppercase tracking-wider">
                            Total {{ $pagination['total'] ?? 0 }} siswa
                        </span>
                    @endif
                </div>

                <div class="overflow-x-auto custom-scroll pb-1">
                    <table class="w-full text-left whitespace-nowrap min-w-max">
                        <thead class="bg-white border-b text-[9px] uppercase text-slate-400 font-bold">
                            <tr>
                                <th class="py-2.5 pl-5 pr-3 text-left tracking-widest">NISN</th>
                                <th class="py-2.5 px-3 text-left tracking-widest">Nama</th>
                                <th class="py-2.5 px-3 text-center tracking-widest">Kelas</th>
                                <th class="py-2.5 px-3 text-center tracking-widest">L/P</th>
                                <th class="py-2.5 px-3 text-center text-green-600 tracking-widest">Hadir</th>
                                <th class="py-2.5 px-3 text-center text-blue-500 tracking-widest">Sakit</th>
                                <th class="py-2.5 px-3 text-center text-orange-500 tracking-widest">Izin</th>
                                <th class="py-2.5 px-3 text-center text-red-500 tracking-widest">Alpha</th>
                                <th class="py-2.5 pr-5 pl-3 text-center text-purple-500 tracking-widest">Terlambat</th>
                            </tr>
                        </thead>
                        <tbody class="divide-y divide-slate-50">

                            @forelse($absensi as $siswa)
                                <tr class="hover:bg-slate-50 transition group">
                                    <td class="py-2.5 pl-5 pr-3 font-mono text-[11px] text-slate-400">
                                        {{ $siswa['nisn'] ?? '-' }}
                                    </td>
                                    <td class="py-2.5 px-3 font-bold text-slate-800 text-[11px] group-hover:text-[#2563eb] transition whitespace-nowrap">
                                        {{ $siswa['nama'] ?? '-' }}
                                    </td>
                                    <td class="py-2.5 px-3 text-center">
                                        <span class="bg-slate-100 text-slate-600 px-1.5 py-0.5 rounded text-[9px] font-bold">{{ $siswa['kelas'] ?? '-' }}</span>
                                    </td>
                                    <td class="py-2.5 px-3 text-center font-bold text-[10px] {{ ($siswa['jenis_kelamin'] ?? '') === 'L' ? 'text-blue-500' : 'text-pink-500' }}">
                                        {{ $siswa['jenis_kelamin'] ?? '-' }}
                                    </td>
                                    <td class="py-2.5 px-3 text-center font-bold text-green-600 bg-green-50/30 text-[11px]">
                                        {{ intval($siswa['total_hadir'] ?? 0) }}
                                    </td>
                                    <td class="py-2.5 px-3 text-center text-blue-500 font-medium text-[11px]">
                                        {{ intval($siswa['total_sakit'] ?? 0) }}
                                    </td>
                                    <td class="py-2.5 px-3 text-center text-orange-500 font-medium text-[11px]">
                                        {{ intval($siswa['total_izin'] ?? 0) }}
                                    </td>
                                    <td class="py-2.5 px-3 text-center text-[11px] {{ intval($siswa['total_alpha'] ?? 0) > 0 ? 'font-bold text-red-600 bg-red-50/50' : 'text-red-300' }}">
                                        {{ intval($siswa['total_alpha'] ?? 0) }}
                                    </td>
                                    <td class="py-2.5 pr-5 pl-3 text-center text-[11px] {{ intval($siswa['total_terlambat'] ?? 0) > 0 ? 'font-bold text-purple-600' : 'text-purple-300' }}">
                                        {{ intval($siswa['total_terlambat'] ?? 0) }}
                                    </td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="9" class="text-center p-8 text-slate-400 text-[11px]">
                                        <i class="fa-regular fa-folder-open text-3xl mb-2 block text-slate-300"></i>
                                        Tidak ada data absensi untuk filter ini.
                                    </td>
                                </tr>
                            @endforelse

                        </tbody>
                    </table>
                </div>

                {{-- Pagination (SUDAH DIREVISI) --}}
                @if(!empty($pagination) && ($pagination['totalPages'] ?? 1) > 1)
                    <div class="p-3 lg:p-4 border-t border-slate-100 flex flex-col md:flex-row justify-between items-center text-[10px] text-slate-500 bg-white">
                        <span class="mb-2 md:mb-0">
                            Halaman <span class="font-bold">{{ $page }}</span> dari
                            <span class="font-bold">{{ $pagination['totalPages'] ?? 1 }}</span>
                            &mdash; Total <span class="font-bold">{{ $pagination['total'] ?? 0 }}</span> siswa
                            @if($kelasAktif)
                                &mdash; Kelas <span class="font-bold text-[#2563eb]">{{ $kelasAktif }}</span>
                            @endif
                        </span>

                        <div class="flex gap-1">
                            @if($page > 1)
                                <a href="{{ route('kepsek.absensi', array_merge(request()->query(), ['page' => $page - 1])) }}"
                                   class="ajax-link px-2.5 py-1.5 bg-white border border-slate-200 rounded-lg hover:bg-slate-50 font-bold transition text-slate-600">Prev</a>
                            @else
                                <button disabled class="px-2.5 py-1.5 bg-white border border-slate-200 rounded-lg opacity-40 cursor-not-allowed font-bold">Prev</button>
                            @endif

                            @for($p = max(1, $page - 2); $p <= min($pagination['totalPages'] ?? 1, $page + 2); $p++)
                                <a href="{{ route('kepsek.absensi', array_merge(request()->query(), ['page' => $p])) }}"
                                   class="ajax-link px-2.5 py-1.5 rounded-lg border font-bold transition {{ $p === $page ? 'bg-[#2563eb] text-white border-blue-600 shadow-sm' : 'bg-white border-slate-200 hover:bg-slate-50 text-slate-600' }}">
                                    {{ $p }}
                                </a>
                            @endfor

                            @if($page < ($pagination['totalPages'] ?? 1))
                                <a href="{{ route('kepsek.absensi', array_merge(request()->query(), ['page' => $page + 1])) }}"
                                   class="ajax-link px-2.5 py-1.5 bg-white border border-slate-200 rounded-lg hover:bg-slate-50 font-bold transition text-slate-600">Next</a>
                            @else
                                <button disabled class="px-2.5 py-1.5 bg-white border border-slate-200 rounded-lg opacity-40 cursor-not-allowed font-bold">Next</button>
                            @endif
                        </div>
                    </div>
                @endif

            </div>
        </div>

    </div>

    @push('scripts')
    <script>
        // ── SCRIPT AJAX EVENT DELEGATION ────────────────────────
        document.addEventListener('DOMContentLoaded', () => {

            // Tangkap Ganti Filter Dropdown
            document.addEventListener('change', e => {
                if (e.target.matches('.auto-submit')) {
                    const form = e.target.closest('form');
                    if (form) executeAjaxFilter(form);
                }
            });

            // Tangkap Pagination Links
            document.addEventListener('click', e => {
                const link = e.target.closest('.ajax-link');
                if (link) {
                    e.preventDefault();
                    reloadContainerData(link.href);
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
            params.set('page', 1); // Reset page ke 1 saat filter diganti

            reloadContainerData(url.pathname + '?' + params.toString());
        }

        async function reloadContainerData(url) {
            const container = document.getElementById('table-container');
            container.style.opacity = '0.5';
            container.style.pointerEvents = 'none';

            try {
                const res = await fetch(url, { headers: { 'X-Requested-With': 'XMLHttpRequest' } });
                const html = await res.text();

                const doc = new DOMParser().parseFromString(html, 'text/html');
                const newContainer = doc.getElementById('table-container');

                if (newContainer) {
                    container.innerHTML = newContainer.innerHTML;
                    window.history.pushState({}, '', url);
                } else {
                    window.location.href = url; // Fallback
                }
            } catch (e) {
                console.error("Gagal reload data:", e);
            } finally {
                container.style.opacity = '1';
                container.style.pointerEvents = 'auto';
            }
        }
    </script>
    @endpush

</x-layout-app>
