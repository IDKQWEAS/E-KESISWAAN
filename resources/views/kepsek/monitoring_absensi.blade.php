<x-layout-app title="Monitoring Absensi" :role="$role">

    <div class="w-full space-y-6 fade-in">

        <div class="mb-2">
            <h3 class="font-bold text-2xl text-slate-800 mb-1">Monitoring Absensi</h3>
            <p class="text-sm text-slate-500">Rekapitulasi kehadiran seluruh siswa.</p>
        </div>

        <div class="bg-white p-5 rounded-2xl shadow-sm border border-slate-200 flex flex-col md:flex-row justify-between gap-4">
            <div>
                <h3 class="font-bold text-sm uppercase text-slate-700">Filter Rekapitulasi</h3>
                <p class="text-xs text-slate-500 mt-0.5">Tentukan parameter untuk menampilkan data.</p>
            </div>

            <form method="GET" action="{{ route('kepsek.absensi') }}" class="flex flex-wrap items-center gap-3">
                <select
                    name="kelas"
                    id="selectKelas"
                    class="border border-slate-200 p-2 rounded-xl text-xs font-bold text-slate-600 bg-slate-50 outline-none cursor-pointer"
                >
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

                <button
                    type="submit"
                    class="bg-blue-600 text-white px-4 py-2 rounded-xl text-xs font-bold hover:bg-blue-700 transition"
                >
                    <i class="fa-solid fa-filter mr-1"></i> Terapkan
                </button>

                <button
                    type="button"
                    id="btnReset"
                    onclick="window.location='{{ route('kepsek.absensi') }}'"
                    class="text-xs text-slate-500 hover:text-slate-700 underline {{ $kelasAktif ? '' : 'hidden' }}"
                >
                    Reset
                </button>
            </form>
        </div>

        <div class="bg-white rounded-2xl border border-slate-200 shadow-sm overflow-hidden">

            <div class="p-4 border-b bg-slate-50 flex justify-between items-center">
                <h4 class="font-bold text-sm text-slate-700">
                    Menampilkan:
                    <span class="text-blue-600">{{ $kelasAktif ? 'Kelas ' . $kelasAktif : 'Semua Kelas' }}</span>
                </h4>
                @if(!empty($pagination))
                    <span class="text-xs text-slate-400">
                        Total {{ $pagination['total'] ?? 0 }} siswa
                    </span>
                @endif
            </div>

            <div class="overflow-x-auto">
                <table class="w-full text-sm">
                    <thead class="bg-white border-b text-xs uppercase text-slate-400">
                        <tr>
                            <th class="p-3 text-left">NISN</th>
                            <th class="p-3 text-left">Nama</th>
                            <th class="p-3 text-center">Kelas</th>
                            <th class="p-3 text-center">L/P</th>
                            <th class="p-3 text-center text-green-600">Hadir</th>
                            <th class="p-3 text-center text-blue-500">Sakit</th>
                            <th class="p-3 text-center text-orange-500">Izin</th>
                            <th class="p-3 text-center text-red-500">Alpha</th>
                            <th class="p-3 text-center text-purple-500">Terlambat</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-slate-100">

                        @forelse($absensi as $siswa)
                            <tr class="hover:bg-slate-50 transition">
                                <td class="p-3 font-mono text-xs text-slate-500">
                                    {{ $siswa['nisn'] ?? '-' }}
                                </td>
                                <td class="p-3 font-semibold text-slate-800">
                                    {{ $siswa['nama'] ?? '-' }}
                                </td>
                                <td class="p-3 text-center text-xs font-bold text-slate-500">
                                    {{ $siswa['kelas'] ?? '-' }}
                                </td>
                                <td class="p-3 text-center font-bold text-xs {{ ($siswa['jenis_kelamin'] ?? '') === 'L' ? 'text-blue-500' : 'text-pink-500' }}">
                                    {{ $siswa['jenis_kelamin'] ?? '-' }}
                                </td>
                                <td class="p-3 text-center font-bold text-green-600">
                                    {{ intval($siswa['total_hadir'] ?? 0) }}
                                </td>
                                <td class="p-3 text-center text-blue-500">
                                    {{ intval($siswa['total_sakit'] ?? 0) }}
                                </td>
                                <td class="p-3 text-center text-orange-500">
                                    {{ intval($siswa['total_izin'] ?? 0) }}
                                </td>
                                <td class="p-3 text-center {{ intval($siswa['total_alpha'] ?? 0) > 0 ? 'font-bold text-red-600' : 'text-red-300' }}">
                                    {{ intval($siswa['total_alpha'] ?? 0) }}
                                </td>
                                <td class="p-3 text-center {{ intval($siswa['total_terlambat'] ?? 0) > 0 ? 'font-bold text-purple-600' : 'text-purple-300' }}">
                                    {{ intval($siswa['total_terlambat'] ?? 0) }}
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="9" class="text-center p-8 text-slate-400 text-sm">
                                    Tidak ada data absensi.
                                </td>
                            </tr>
                        @endforelse

                    </tbody>
                </table>
            </div>

            @if(!empty($pagination) && ($pagination['totalPages'] ?? 1) > 1)
                <div class="p-4 border-t border-slate-100 flex justify-between items-center text-xs text-slate-500">
                    <span>
                        Halaman {{ $pagination['page'] ?? 1 }} dari {{ $pagination['totalPages'] ?? 1 }}
                    </span>
                    <div class="flex gap-1">
                        @if(($pagination['page'] ?? 1) > 1)
                            <a href="{{ route('kepsek.absensi', array_merge(request()->query(), ['page' => $pagination['page'] - 1])) }}"
                               class="px-3 py-1 bg-white border border-slate-200 rounded-lg hover:bg-slate-50">Prev</a>
                        @endif

                        @for($p = 1; $p <= ($pagination['totalPages'] ?? 1); $p++)
                            <a href="{{ route('kepsek.absensi', array_merge(request()->query(), ['page' => $p])) }}"
                               class="px-3 py-1 rounded-lg border {{ $p == ($pagination['page'] ?? 1) ? 'bg-primary text-white border-primary' : 'bg-white border-slate-200 hover:bg-slate-50' }}">
                                {{ $p }}
                            </a>
                        @endfor

                        @if(($pagination['page'] ?? 1) < ($pagination['totalPages'] ?? 1))
                            <a href="{{ route('kepsek.absensi', array_merge(request()->query(), ['page' => $pagination['page'] + 1])) }}"
                               class="px-3 py-1 bg-white border border-slate-200 rounded-lg hover:bg-slate-50">Next</a>
                        @endif
                    </div>
                </div>
            @endif

        </div>
    </div>

</x-layout-app>
