<x-layout-app title="Peta Kedisiplinan" :role="$role">

    <div class="page-section space-y-6 fade-in">

        <div class="bg-white rounded-3xl border border-slate-200 shadow-card overflow-hidden">

            {{-- Header & Filter --}}
            <div class="p-6 bg-white border-b border-slate-100 flex flex-wrap justify-between items-center gap-3">
                <div>
                    <h3 class="font-bold text-slate-800 text-sm uppercase tracking-wide">Peta Kedisiplinan Siswa</h3>
                    <p class="text-xs text-slate-500 mt-1">
                        Total <span class="font-bold text-slate-700">{{ $pagination['total'] }}</span> siswa memiliki catatan pelanggaran.
                        @if($taString)
                            &mdash; TA: <span class="font-bold text-primary">{{ $taString }}</span>
                        @endif
                    </p>
                </div>

                {{-- Hanya filter kelas — TA & semester sudah dari header global --}}
                <form method="GET" action="{{ route('kepsek.disiplin') }}" id="formFilter">
                    {{-- Preserve id_tahun_ajaran dari header global agar tidak hilang saat filter kelas --}}
                    @if($reqIdTa)
                        <input type="hidden" name="id_tahun_ajaran" value="{{ $reqIdTa }}">
                    @endif
                    <input type="hidden" name="page" value="1">

                    <select name="kelas" onchange="document.getElementById('formFilter').submit()"
                        class="border border-slate-200 p-2 rounded-xl text-xs bg-slate-50 outline-none font-bold text-slate-600 cursor-pointer">
                        <option value="">Semua Kelas</option>
                        @foreach(['7A','7B','7C','7D','7E','7F','7G','8A','8B','8C','8D','8E','8F','8G','9A','9B','9C','9D','9E','9F','9G'] as $k)
                            <option value="{{ $k }}" {{ $filterKelas === $k ? 'selected' : '' }}>
                                Kelas {{ $k }}
                            </option>
                        @endforeach
                    </select>
                </form>
            </div>

            {{-- Tabel --}}
            <table class="w-full text-sm text-left">
                <thead class="bg-slate-50 text-slate-500 uppercase text-xs font-bold">
                    <tr>
                        <th class="p-4 pl-8 w-12">Rank</th>
                        <th class="p-4">Nama Siswa</th>
                        <th class="p-4">NISN</th>
                        <th class="p-4">Kelas</th>
                        <th class="p-4 text-center">Total Poin</th>
                        <th class="p-4 text-center">Jml. Pelanggaran</th>
                        <th class="p-4 text-right">Status</th>
                        <th class="p-4 pr-8 text-right">Riwayat</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-slate-100">

                    @forelse($dataPelanggaran as $index => $siswa)
                        @php
                            $poin    = (int) ($siswa['total_poin'] ?? 0);
                            $riwayat = $siswa['riwayat_pelanggaran'] ?? [];
                            $rowId   = 'row-detail-' . $index;

                            if ($poin >= 100) {
                                $statusLabel = 'Dikembalikan ke Ortu';
                                $statusClass = 'bg-red-100 text-red-800';
                                $rowClass    = 'hover:bg-red-50/40';
                                $barColor    = 'bg-red-500';
                            } elseif ($poin >= 75) {
                                $statusLabel = 'SP 3';
                                $statusClass = 'bg-orange-100 text-orange-800';
                                $rowClass    = 'hover:bg-orange-50/40';
                                $barColor    = 'bg-orange-500';
                            } elseif ($poin >= 50) {
                                $statusLabel = 'SP 2';
                                $statusClass = 'bg-amber-100 text-amber-800';
                                $rowClass    = 'hover:bg-amber-50/40';
                                $barColor    = 'bg-amber-500';
                            } elseif ($poin >= 30) {
                                $statusLabel = 'SP 1';
                                $statusClass = 'bg-yellow-100 text-yellow-800';
                                $rowClass    = 'hover:bg-yellow-50/40';
                                $barColor    = 'bg-yellow-400';
                            } else {
                                $statusLabel = 'Teguran';
                                $statusClass = 'bg-blue-100 text-blue-800';
                                $rowClass    = 'hover:bg-blue-50/40';
                                $barColor    = 'bg-blue-400';
                            }

                            $rankOffset = (($page - 1) * $limit) + $index + 1;
                        @endphp

                        {{-- Baris Utama --}}
                        <tr class="{{ $rowClass }} transition cursor-pointer" onclick="toggleDetail('{{ $rowId }}')">
                            <td class="p-4 pl-8 font-bold text-slate-400 text-base">#{{ $rankOffset }}</td>
                            <td class="p-4 font-bold text-slate-800">{{ $siswa['nama_siswa'] ?? 'Tidak diketahui' }}</td>
                            <td class="p-4 text-slate-500 text-xs font-mono">{{ $siswa['nisn'] ?? '-' }}</td>
                            <td class="p-4 text-slate-500">{{ $siswa['kelas'] ?? '-' }}</td>
                            <td class="p-4 text-center">
                                <div class="flex flex-col items-center gap-1">
                                    <span class="font-bold text-red-600 text-base">{{ $poin }}</span>
                                    <div class="w-16 h-1.5 bg-slate-100 rounded-full overflow-hidden">
                                        <div class="{{ $barColor }} h-full rounded-full"
                                             style="width: {{ min(100, $poin) }}%"></div>
                                    </div>
                                </div>
                            </td>
                            <td class="p-4 text-center">
                                <span class="inline-flex items-center justify-center w-7 h-7 rounded-full bg-slate-100 text-slate-600 text-xs font-bold">
                                    {{ count($riwayat) }}
                                </span>
                            </td>
                            <td class="p-4 text-right">
                                <span class="{{ $statusClass }} px-3 py-1 rounded-lg text-xs font-bold uppercase whitespace-nowrap">
                                    {{ $statusLabel }}
                                </span>
                            </td>
                            <td class="p-4 pr-8 text-right">
                                @if(count($riwayat) > 0)
                                    <button class="text-slate-400 hover:text-slate-700 transition" id="chevron-{{ $rowId }}">
                                        <i class="fa-solid fa-chevron-down text-xs"></i>
                                    </button>
                                @else
                                    <span class="text-slate-300 text-xs">—</span>
                                @endif
                            </td>
                        </tr>

                        {{-- Baris Detail Riwayat --}}
                        @if(count($riwayat) > 0)
                        <tr id="{{ $rowId }}" class="hidden">
                            <td colspan="8" class="p-0">
                                <div class="bg-slate-50 border-t border-slate-100 px-8 py-4">
                                    <p class="text-xs font-bold text-slate-500 uppercase tracking-wide mb-3">
                                        Riwayat Pelanggaran — {{ $siswa['nama_siswa'] ?? '' }}
                                    </p>
                                    <div class="space-y-2">
                                        @foreach($riwayat as $r)
                                            <div class="flex items-center justify-between bg-white rounded-xl border border-slate-100 px-4 py-3 text-sm">
                                                <div class="flex items-center gap-3 flex-wrap">
                                                    <span class="text-xs font-mono text-slate-400 whitespace-nowrap">
                                                        {{ \Carbon\Carbon::parse($r['tanggal'] ?? '')->format('d M Y') }}
                                                    </span>
                                                    <span class="font-semibold text-slate-700">{{ $r['pelanggaran'] ?? '-' }}</span>
                                                    @if(!empty($r['keterangan']))
                                                        <span class="text-slate-400 text-xs">— {{ $r['keterangan'] }}</span>
                                                    @endif
                                                </div>
                                                <span class="ml-4 bg-red-50 text-red-600 border border-red-100 text-xs font-bold px-2 py-0.5 rounded-lg whitespace-nowrap">
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
                            <td colspan="8" class="p-10 text-center text-slate-400 text-sm">
                                <i class="fa-solid fa-circle-check text-2xl text-green-300 mb-2 block"></i>
                                Tidak ada data pelanggaran untuk filter ini.
                            </td>
                        </tr>
                    @endforelse

                </tbody>
            </table>

            {{-- Footer Pagination --}}
            <div class="p-4 border-t border-slate-100 flex justify-between items-center text-xs text-slate-500 bg-white">
                <span>
                    Halaman <span class="font-bold">{{ $page }}</span> dari
                    <span class="font-bold">{{ $pagination['totalPages'] ?? 1 }}</span>
                    &mdash; Total <span class="font-bold">{{ $pagination['total'] ?? 0 }}</span> siswa
                    @if($filterKelas)
                        &mdash; Kelas <span class="font-bold text-primary">{{ $filterKelas }}</span>
                    @endif
                </span>

                <div class="flex gap-1">
                    @if($page > 1)
                        <a href="{{ request()->fullUrlWithQuery(['page' => $page - 1]) }}"
                           class="px-3 py-1 bg-white border border-slate-200 rounded-lg hover:bg-slate-50">Prev</a>
                    @else
                        <button disabled class="px-3 py-1 bg-white border border-slate-200 rounded-lg opacity-40 cursor-not-allowed">Prev</button>
                    @endif

                    @for($p = max(1, $page - 2); $p <= min($pagination['totalPages'] ?? 1, $page + 2); $p++)
                        <a href="{{ request()->fullUrlWithQuery(['page' => $p]) }}"
                           class="px-3 py-1 rounded-lg {{ $p === $page ? 'bg-primary text-white' : 'bg-white border border-slate-200 hover:bg-slate-50' }}">
                            {{ $p }}
                        </a>
                    @endfor

                    @if($page < ($pagination['totalPages'] ?? 1))
                        <a href="{{ request()->fullUrlWithQuery(['page' => $page + 1]) }}"
                           class="px-3 py-1 bg-white border border-slate-200 rounded-lg hover:bg-slate-50">Next</a>
                    @else
                        <button disabled class="px-3 py-1 bg-white border border-slate-200 rounded-lg opacity-40 cursor-not-allowed">Next</button>
                    @endif
                </div>
            </div>
        </div>

        {{-- Keterangan Status Poin --}}
        <div class="bg-white rounded-3xl border border-slate-200 shadow-card p-6">
            <h4 class="font-bold text-slate-700 text-sm uppercase tracking-wide mb-4">Keterangan Status Poin</h4>
            <div class="grid grid-cols-2 md:grid-cols-5 gap-3">
                <div class="flex items-center gap-2 bg-blue-50 rounded-xl p-3">
                    <span class="bg-blue-100 text-blue-800 px-2 py-0.5 rounded text-xs font-bold">Teguran</span>
                    <span class="text-xs text-slate-500">&lt; 30 poin</span>
                </div>
                <div class="flex items-center gap-2 bg-yellow-50 rounded-xl p-3">
                    <span class="bg-yellow-100 text-yellow-800 px-2 py-0.5 rounded text-xs font-bold">SP 1</span>
                    <span class="text-xs text-slate-500">30–49 poin</span>
                </div>
                <div class="flex items-center gap-2 bg-amber-50 rounded-xl p-3">
                    <span class="bg-amber-100 text-amber-800 px-2 py-0.5 rounded text-xs font-bold">SP 2</span>
                    <span class="text-xs text-slate-500">50–74 poin</span>
                </div>
                <div class="flex items-center gap-2 bg-orange-50 rounded-xl p-3">
                    <span class="bg-orange-100 text-orange-800 px-2 py-0.5 rounded text-xs font-bold">SP 3</span>
                    <span class="text-xs text-slate-500">75–99 poin</span>
                </div>
                <div class="flex items-center gap-2 bg-red-50 rounded-xl p-3">
                    <span class="bg-red-100 text-red-800 px-2 py-0.5 rounded text-xs font-bold whitespace-nowrap">Kembali ke Ortu</span>
                    <span class="text-xs text-slate-500">≥ 100 poin</span>
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
            }
        }
    </script>
    @endpush

</x-layout-app>
