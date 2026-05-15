<x-layout-app title="Peta Kedisiplinan" :role="$role">

    <div class="space-y-4 fade-in pb-8">

        <div class="bg-white rounded-2xl border border-slate-200 shadow-sm overflow-hidden">

            {{-- Header & Filter --}}
            <div class="p-4 lg:p-5 bg-white border-b border-slate-100 flex flex-wrap justify-between items-center gap-3">
                <div>
                    <h3 class="font-bold text-slate-800 text-[13px] uppercase tracking-wide">Peta Kedisiplinan Siswa</h3>
                    <p class="text-[11px] text-slate-500 mt-1">
                        Total <span class="font-bold text-slate-700">{{ $pagination['total'] }}</span> siswa memiliki catatan pelanggaran.
                        @if($taString)
                            &mdash; TA: <span class="font-bold text-[#2563eb]">{{ $taString }}</span>
                        @endif
                    </p>
                </div>

                {{-- Form AJAX Filter Kelas --}}
                <form id="filterFormDisiplin" method="GET" action="{{ route('kepsek.disiplin') }}" class="flex items-center gap-2">
                    @if($reqIdTa)
                        <input type="hidden" name="id_tahun_ajaran" value="{{ $reqIdTa }}">
                    @endif

                    <div class="relative">
                        <select name="kelas" class="auto-submit appearance-none border border-slate-200 pl-3 pr-8 py-1.5 rounded-lg text-[11px] bg-slate-50 outline-none font-bold text-slate-600 cursor-pointer focus:ring-2 focus:ring-blue-500/20 h-[32px]">
                            <option value="">Semua Kelas</option>
                            @foreach(['7A','7B','7C','7D','7E','7F','7G','8A','8B','8C','8D','8E','8F','8G','9A','9B','9C','9D','9E','9F','9G'] as $k)
                                <option value="{{ $k }}" {{ $filterKelas === $k ? 'selected' : '' }}>
                                    Kelas {{ $k }}
                                </option>
                            @endforeach
                        </select>
                        <i class="fa-solid fa-chevron-down absolute right-3 top-1/2 -translate-y-1/2 text-[9px] text-slate-400 pointer-events-none"></i>
                    </div>

                    @if($filterKelas)
                        <button type="button" onclick="window.location='{{ route('kepsek.disiplin') }}'"
                            class="px-3 py-1.5 text-[11px] text-slate-500 bg-white border border-slate-200 rounded-lg hover:bg-slate-50 font-bold h-[32px] flex items-center">
                            Reset
                        </button>
                    @endif
                </form>
            </div>

            {{-- BUNGKUS DENGAN AJAX ID --}}
            <div id="table-container" class="transition-opacity duration-300">

                {{-- Tabel --}}
                <div class="overflow-x-auto custom-scroll pb-1">
                    <table class="w-full text-left whitespace-nowrap min-w-max">
                        <thead class="bg-slate-50 text-slate-500 uppercase text-[9px] font-bold border-b border-slate-100">
                            <tr>
                                <th class="p-3 pl-5 w-12 tracking-widest">Rank</th>
                                <th class="p-3 tracking-widest">Nama Siswa</th>
                                <th class="p-3 tracking-widest">NISN</th>
                                <th class="p-3 tracking-widest">Kelas</th>
                                <th class="p-3 text-center tracking-widest">Total Poin</th>
                                <th class="p-3 text-center tracking-widest">Jml. Pelanggaran</th>
                                <th class="p-3 text-right tracking-widest">Status</th>
                                <th class="p-3 pr-5 text-right tracking-widest">Riwayat</th>
                            </tr>
                        </thead>
                        <tbody class="divide-y divide-slate-50">

                            @forelse($dataPelanggaran as $index => $siswa)
                                @php
                                    $poin    = (int) ($siswa['total_poin'] ?? 0);
                                    $riwayat = $siswa['riwayat_pelanggaran'] ?? [];
                                    $rowId   = 'row-detail-' . $index;

                                    if ($poin >= 100) {
                                        $statusLabel = 'Dikembalikan ke Ortu';
                                        $statusClass = 'bg-red-100 text-red-800 border border-red-200';
                                        $rowClass    = 'hover:bg-red-50/40';
                                        $barColor    = 'bg-red-500';
                                    } elseif ($poin >= 75) {
                                        $statusLabel = 'SP 3';
                                        $statusClass = 'bg-orange-100 text-orange-800 border border-orange-200';
                                        $rowClass    = 'hover:bg-orange-50/40';
                                        $barColor    = 'bg-orange-500';
                                    } elseif ($poin >= 50) {
                                        $statusLabel = 'SP 2';
                                        $statusClass = 'bg-amber-100 text-amber-800 border border-amber-200';
                                        $rowClass    = 'hover:bg-amber-50/40';
                                        $barColor    = 'bg-amber-500';
                                    } elseif ($poin >= 30) {
                                        $statusLabel = 'SP 1';
                                        $statusClass = 'bg-yellow-100 text-yellow-800 border border-yellow-200';
                                        $rowClass    = 'hover:bg-yellow-50/40';
                                        $barColor    = 'bg-yellow-400';
                                    } else {
                                        $statusLabel = 'Teguran';
                                        $statusClass = 'bg-blue-100 text-blue-800 border border-blue-200';
                                        $rowClass    = 'hover:bg-blue-50/40';
                                        $barColor    = 'bg-blue-400';
                                    }

                                    $rankOffset = (($page - 1) * $limit) + $index + 1;
                                @endphp

                                {{-- Baris Utama --}}
                                <tr class="{{ $rowClass }} transition cursor-pointer group" onclick="toggleDetail('{{ $rowId }}')">
                                    <td class="p-3 pl-5 font-bold text-slate-400 text-xs">#{{ $rankOffset }}</td>
                                    <td class="p-3 font-bold text-slate-800 text-[11px] group-hover:text-[#2563eb] transition">{{ $siswa['nama_siswa'] ?? 'Tidak diketahui' }}</td>
                                    <td class="p-3 text-slate-500 text-[11px] font-mono">{{ $siswa['nisn'] ?? '-' }}</td>
                                    <td class="p-3 text-slate-500 text-[11px]"><span class="bg-slate-100 px-2 py-1 rounded-md font-bold text-slate-600">{{ $siswa['kelas'] ?? '-' }}</span></td>
                                    <td class="p-3 text-center">
                                        <div class="flex flex-col items-center gap-1.5">
                                            <span class="font-bold text-red-600 text-xs">{{ $poin }}</span>
                                            <div class="w-12 h-1 bg-slate-100 rounded-full overflow-hidden">
                                                <div class="{{ $barColor }} h-full rounded-full" style="width: {{ min(100, $poin) }}%"></div>
                                            </div>
                                        </div>
                                    </td>
                                    <td class="p-3 text-center">
                                        <span class="inline-flex items-center justify-center w-6 h-6 rounded-full bg-slate-100 text-slate-600 text-[10px] font-bold">
                                            {{ count($riwayat) }}
                                        </span>
                                    </td>
                                    <td class="p-3 text-right">
                                        <span class="{{ $statusClass }} px-2.5 py-1 rounded-lg text-[9px] font-bold uppercase whitespace-nowrap">
                                            {{ $statusLabel }}
                                        </span>
                                    </td>
                                    <td class="p-3 pr-5 text-right">
                                        @if(count($riwayat) > 0)
                                            <button class="w-6 h-6 rounded-md bg-slate-50 text-slate-400 group-hover:text-[#2563eb] group-hover:bg-blue-50 transition flex items-center justify-center ml-auto" id="chevron-{{ $rowId }}">
                                                <i class="fa-solid fa-chevron-down text-[10px]"></i>
                                            </button>
                                        @else
                                            <span class="text-slate-300 text-[10px]">—</span>
                                        @endif
                                    </td>
                                </tr>

                                {{-- Baris Detail Riwayat --}}
                                @if(count($riwayat) > 0)
                                <tr id="{{ $rowId }}" class="hidden">
                                    <td colspan="8" class="p-0">
                                        <div class="bg-slate-50 border-t border-slate-100 px-5 py-3 shadow-inner">
                                            <p class="text-[9px] font-bold text-slate-400 uppercase tracking-wide mb-2 flex items-center gap-1.5">
                                                <i class="fa-solid fa-clock-rotate-left"></i> Riwayat Pelanggaran — {{ $siswa['nama_siswa'] ?? '' }}
                                            </p>
                                            <div class="space-y-1.5">
                                                @foreach($riwayat as $r)
                                                    <div class="flex items-center justify-between bg-white rounded-lg border border-slate-200 px-3 py-2 text-[11px] shadow-sm">
                                                        <div class="flex items-center gap-2 flex-wrap">
                                                            <span class="text-[10px] font-mono text-slate-400 whitespace-nowrap">
                                                                {{ \Carbon\Carbon::parse($r['tanggal'] ?? '')->format('d M Y') }}
                                                            </span>
                                                            <span class="font-bold text-slate-700">{{ $r['pelanggaran'] ?? '-' }}</span>
                                                            @if(!empty($r['keterangan']))
                                                                <span class="text-slate-400 text-[10px] italic">— {{ $r['keterangan'] }}</span>
                                                            @endif
                                                        </div>
                                                        <span class="ml-3 bg-red-50 text-red-600 border border-red-100 text-[10px] font-bold px-2 py-0.5 rounded-md whitespace-nowrap">
                                                            +{{ $r['poin'] ?? 0 }} poin
                                                        </span>
                                                    </div>
                                                @endforeach
                                            </div>
                                        </div>
                                    </td>
                                </tr>
                                @endif

                            @empty
                                <tr>
                                    <td colspan="8" class="p-10 text-center text-slate-400 text-[11px]">
                                        <i class="fa-solid fa-circle-check text-3xl text-green-300 mb-2 block"></i>
                                        Tidak ada data pelanggaran untuk filter ini.
                                    </td>
                                </tr>
                            @endforelse

                        </tbody>
                    </table>
                </div>

                {{-- Footer Pagination (SUDAH DIREVISI) --}}
                @if(!empty($pagination) && ($pagination['totalPages'] ?? 1) > 1)
                <div class="p-3 lg:p-4 border-t border-slate-100 flex flex-col md:flex-row justify-between items-center text-[10px] text-slate-500 bg-white">
                    <span class="mb-2 md:mb-0">
                        Halaman <span class="font-bold">{{ $page }}</span> dari
                        <span class="font-bold">{{ $pagination['totalPages'] ?? 1 }}</span>
                        &mdash; Total <span class="font-bold">{{ $pagination['total'] ?? 0 }}</span> siswa
                        @if($filterKelas)
                            &mdash; Kelas <span class="font-bold text-[#2563eb]">{{ $filterKelas }}</span>
                        @endif
                    </span>

                    <div class="flex gap-1">
                        @if($page > 1)
                            <a href="{{ route('kepsek.disiplin', array_merge(request()->query(), ['page' => $page - 1])) }}"
                               class="ajax-link px-2.5 py-1 bg-white border border-slate-200 rounded-md hover:bg-slate-50 font-bold transition text-slate-600">Prev</a>
                        @else
                            <button disabled class="px-2.5 py-1 bg-white border border-slate-200 rounded-md opacity-40 cursor-not-allowed font-bold">Prev</button>
                        @endif

                        @for($p = max(1, $page - 2); $p <= min($pagination['totalPages'] ?? 1, $page + 2); $p++)
                            <a href="{{ route('kepsek.disiplin', array_merge(request()->query(), ['page' => $p])) }}"
                               class="ajax-link px-2.5 py-1 rounded-md border font-bold transition {{ $p === $page ? 'bg-[#2563eb] text-white border-blue-600 shadow-sm' : 'bg-white border-slate-200 hover:bg-slate-50 text-slate-600' }}">
                                {{ $p }}
                            </a>
                        @endfor

                        @if($page < ($pagination['totalPages'] ?? 1))
                            <a href="{{ route('kepsek.disiplin', array_merge(request()->query(), ['page' => $page + 1])) }}"
                               class="ajax-link px-2.5 py-1 bg-white border border-slate-200 rounded-md hover:bg-slate-50 font-bold transition text-slate-600">Next</a>
                        @else
                            <button disabled class="px-2.5 py-1 bg-white border border-slate-200 rounded-md opacity-40 cursor-not-allowed font-bold">Next</button>
                        @endif
                    </div>
                </div>
                @endif

            </div>
        </div>

        {{-- Keterangan Status Poin --}}
        <div class="bg-white rounded-2xl border border-slate-200 shadow-sm p-4 lg:p-5">
            <h4 class="font-bold text-slate-700 text-[11px] uppercase tracking-wide mb-3 flex items-center gap-1.5">
                <i class="fa-solid fa-circle-info text-blue-500"></i> Keterangan Status Poin
            </h4>
            <div class="grid grid-cols-2 md:grid-cols-5 gap-2">
                <div class="flex flex-col lg:flex-row items-start lg:items-center gap-1 lg:gap-2 bg-blue-50 rounded-lg p-2.5 border border-blue-100">
                    <span class="bg-blue-100 text-blue-800 px-2 py-0.5 rounded text-[9px] font-bold border border-blue-200">Teguran</span>
                    <span class="text-[10px] text-slate-500 font-mono">&lt; 30 poin</span>
                </div>
                <div class="flex flex-col lg:flex-row items-start lg:items-center gap-1 lg:gap-2 bg-yellow-50 rounded-lg p-2.5 border border-yellow-100">
                    <span class="bg-yellow-100 text-yellow-800 px-2 py-0.5 rounded text-[9px] font-bold border border-yellow-200">SP 1</span>
                    <span class="text-[10px] text-slate-500 font-mono">30–49 poin</span>
                </div>
                <div class="flex flex-col lg:flex-row items-start lg:items-center gap-1 lg:gap-2 bg-amber-50 rounded-lg p-2.5 border border-amber-100">
                    <span class="bg-amber-100 text-amber-800 px-2 py-0.5 rounded text-[9px] font-bold border border-amber-200">SP 2</span>
                    <span class="text-[10px] text-slate-500 font-mono">50–74 poin</span>
                </div>
                <div class="flex flex-col lg:flex-row items-start lg:items-center gap-1 lg:gap-2 bg-orange-50 rounded-lg p-2.5 border border-orange-100">
                    <span class="bg-orange-100 text-orange-800 px-2 py-0.5 rounded text-[9px] font-bold border border-orange-200">SP 3</span>
                    <span class="text-[10px] text-slate-500 font-mono">75–99 poin</span>
                </div>
                <div class="flex flex-col lg:flex-row items-start lg:items-center gap-1 lg:gap-2 bg-red-50 rounded-lg p-2.5 border border-red-100">
                    <span class="bg-red-100 text-red-800 px-2 py-0.5 rounded text-[9px] font-bold whitespace-nowrap border border-red-200">Kembali ke Ortu</span>
                    <span class="text-[10px] text-slate-500 font-mono">≥ 100 poin</span>
                </div>
            </div>
        </div>

    </div>

    @push('scripts')
    <script>
        function toggleDetail(rowId) {
            const row     = document.getElementById(rowId);
            const chevron = document.getElementById('chevron-' + rowId);
            if (!row) return;

            const isHidden = row.classList.contains('hidden');
            row.classList.toggle('hidden', !isHidden);

            if (chevron) {
                chevron.querySelector('i').classList.toggle('fa-chevron-down', !isHidden);
                chevron.querySelector('i').classList.toggle('fa-chevron-up', isHidden);
                chevron.classList.toggle('bg-blue-50', isHidden);
                chevron.classList.toggle('text-[#2563eb]', isHidden);
            }
        }

        // ── SCRIPT AJAX EVENT DELEGATION (ANTI BERKEDIP) ─────────────────
        document.addEventListener('DOMContentLoaded', () => {
            document.addEventListener('change', e => {
                if (e.target.matches('.auto-submit')) {
                    const form = e.target.closest('form');
                    if (form) executeAjaxFilter(form);
                }
            });

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
            params.set('page', 1); // Reset page 1

            reloadTableData(url.pathname + '?' + params.toString());
        }

        async function reloadTableData(url) {
            const container = document.getElementById('table-container');
            container.style.opacity = '0.5';
            container.style.pointerEvents = 'none';

            try {
                const res = await fetch(url, { headers: { 'X-Requested-With': 'XMLHttpRequest' } });
                const html = await res.text();

                // Gunakan DOMParser murni tanpa embel-embel Document
                const doc = new DOMParser().parseFromString(html, 'text/html');
                const newContainer = doc.getElementById('table-container');

                if (newContainer) {
                    container.innerHTML = newContainer.innerHTML;
                    window.history.pushState({}, '', url);
                } else {
                    window.location.href = url; // Fallback darurat
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
