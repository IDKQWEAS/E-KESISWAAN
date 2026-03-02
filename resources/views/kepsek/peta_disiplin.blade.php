<x-layout-app title="Peta Kedisiplinan" :role="$role">

    <div class="page-section space-y-8 fade-in">
        <div class="bg-white rounded-3xl border border-slate-200 shadow-card overflow-hidden">

            <div class="p-6 bg-white border-b border-slate-100 flex flex-wrap justify-between items-center gap-3">
                <div>
                    <h3 class="font-bold text-slate-800 text-sm uppercase tracking-wide">Data Pelanggaran Siswa</h3>
                    <p class="text-xs text-slate-500 mt-1">Diurutkan dari poin tertinggi berdasarkan data API.</p>
                </div>

                <form method="GET" action="{{ route('kepsek.disiplin') }}" id="formFilterKelas">
                    <select
                        id="selectKelas"
                        name="kelas"
                        class="border border-slate-200 p-2 rounded-xl text-xs bg-slate-50 outline-none font-bold text-slate-600 cursor-pointer"
                    >
                        <option value="">Semua Kelas</option>
                        @foreach(['7A','7B','7C','7D','7E','7F','7G','8A','8B','8C','8D','8E','8F','8G','9A','9B','9C','9D','9E','9F','9G'] as $k)
                            <option value="{{ $k }}" {{ ($filterKelas ?? '') === $k ? 'selected' : '' }}>
                                Kelas {{ $k }}
                            </option>
                        @endforeach
                    </select>
                </form>
            </div>

            <table class="w-full text-sm text-left">
                <thead class="bg-slate-50 text-slate-500 uppercase text-xs font-bold">
                    <tr>
                        <th class="p-4 pl-8">Rank</th>
                        <th class="p-4">Nama Siswa</th>
                        <th class="p-4">Kelas</th>
                        <th class="p-4">Total Poin</th>
                        <th class="p-4 text-right pr-8">Status</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-slate-100">

                    @forelse($dataPelanggaran as $index => $siswa)
                        @php
                            $poin = (int) ($siswa['total_poin'] ?? 0);

                            if ($poin >= 100) {
                                $statusLabel = 'Dikembalikan ke Ortu';
                                $statusClass = 'bg-red-100 text-red-800';
                                $rowClass    = 'hover:bg-red-50/50';
                            } elseif ($poin >= 75) {
                                $statusLabel = 'SP 3';
                                $statusClass = 'bg-orange-100 text-orange-800';
                                $rowClass    = 'hover:bg-orange-50/50';
                            } elseif ($poin >= 50) {
                                $statusLabel = 'SP 2';
                                $statusClass = 'bg-amber-100 text-amber-800';
                                $rowClass    = 'hover:bg-amber-50/50';
                            } elseif ($poin >= 30) {
                                $statusLabel = 'SP 1';
                                $statusClass = 'bg-yellow-100 text-yellow-800';
                                $rowClass    = 'hover:bg-yellow-50/50';
                            } else {
                                $statusLabel = 'Teguran';
                                $statusClass = 'bg-blue-100 text-blue-800';
                                $rowClass    = 'hover:bg-blue-50/50';
                            }
                        @endphp
                        <tr class="{{ $rowClass }} transition">
                            <td class="p-4 pl-8 font-bold text-slate-400">#{{ $index + 1 }}</td>
                            <td class="p-4 font-bold text-slate-800">{{ $siswa['nama_siswa'] ?? 'Tidak diketahui' }}</td>
                            <td class="p-4 text-slate-500">{{ $siswa['kelas'] ?? '-' }}</td>
                            <td class="p-4 font-bold text-red-600">{{ $poin }}</td>
                            <td class="p-4 text-right pr-8">
                                <span class="{{ $statusClass }} px-3 py-1 rounded-lg text-xs font-bold uppercase">
                                    {{ $statusLabel }}
                                </span>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="5" class="p-8 text-center text-slate-400 text-sm">
                                Tidak ada data pelanggaran untuk filter ini.
                            </td>
                        </tr>
                    @endforelse

                </tbody>
            </table>

            <div class="p-4 border-t border-slate-100 flex justify-between items-center text-xs text-slate-500 bg-white">
                <span>
                    Menampilkan {{ count($dataPelanggaran) }} siswa
                    @if($filterKelas)
                        &mdash; Filter: Kelas <span class="font-bold text-primary">{{ $filterKelas }}</span>
                    @endif
                </span>
                <div class="flex gap-1">
                    <button class="px-3 py-1 bg-white border border-slate-200 rounded-lg hover:bg-slate-50 disabled:opacity-50">Prev</button>
                    <button class="px-3 py-1 bg-primary text-white rounded-lg">1</button>
                    <button class="px-3 py-1 bg-white border border-slate-200 rounded-lg hover:bg-slate-50">Next</button>
                </div>
            </div>
        </div>

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

    <script>
        document.getElementById('selectKelas').addEventListener('change', function () {
            document.getElementById('formFilterKelas').submit();
        });
    </script>

</x-layout-app>
