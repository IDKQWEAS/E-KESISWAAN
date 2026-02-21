<x-layout-app title="Rekap Kehadiran" role="bk">
    <header class="h-20 flex-none px-8 flex items-center justify-between bg-white border-b border-slate-200 sticky top-0 z-20">
        <h2 class="text-xl font-bold text-slate-800 tracking-tight">Rekapitulasi Absensi</h2>
    </header>

    <div class="flex-1 overflow-y-auto p-8 custom-scroll bg-surface">
        <div class="bg-white p-6 rounded-3xl shadow-card border border-slate-200">
            <div class="flex justify-between items-center mb-6">
                <div>
                    <h3 class="font-bold text-lg text-slate-800">Rekapitulasi Kehadiran Siswa</h3>
                    <p class="text-xs text-slate-500 mt-1">Akumulasi data absensi realtime & perizinan.</p>
                </div>
                <div class="flex gap-3">
                    <select class="border border-slate-200 p-2 rounded-lg text-xs font-bold text-slate-600 outline-none cursor-pointer"><option>Semua Kelas</option><optgroup label="Kelas 7"><option>7A</option></optgroup></select>
                    <button class="bg-red-500 text-white px-4 py-2 rounded-lg text-xs font-bold shadow hover:bg-red-600 transition"><i class="fa-solid fa-file-pdf mr-2"></i> Download PDF</button>
                </div>
            </div>
            <div class="border border-slate-200 rounded-2xl overflow-hidden">
                <table class="w-full text-left text-sm">
                    <thead class="bg-slate-50 text-slate-400 text-[10px] uppercase font-bold border-b border-slate-100">
                        <tr>
                            <th class="px-6 py-4">NISN</th>
                            <th class="px-6 py-4">Nama Siswa</th>
                            <th class="px-6 py-4">L/P</th>
                            <th class="px-6 py-4 text-center">Hadir</th>
                            <th class="px-6 py-4 text-center">Sakit</th>
                            <th class="px-6 py-4 text-center">Izin</th>
                            <th class="px-6 py-4 text-center">Alpha</th>
                            <th class="px-6 py-4 text-center">Terlambat</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-slate-50">
                        <tr class="hover:bg-slate-50 transition">
                            <td class="px-6 py-4 font-mono text-xs">12345678</td>
                            <td class="px-6 py-4 font-bold text-slate-800">Andi Saputra</td>
                            <td class="px-6 py-4">L</td>
                            <td class="px-6 py-4 text-center font-bold text-green-600">20</td>
                            <td class="px-6 py-4 text-center text-slate-500">1</td>
                            <td class="px-6 py-4 text-center text-slate-500">0</td>
                            <td class="px-6 py-4 text-center text-slate-500">0</td>
                            <td class="px-6 py-4 text-center text-red-500 font-bold">2</td>
                        </tr>
                        <tr>
                            <td class="px-6 py-4 font-mono text-xs">12349999</td>
                            <td class="px-6 py-4 font-bold text-slate-700">Doni Tata</td>
                            <td class="px-6 py-4">L</td>
                            <td class="px-6 py-4 text-center font-bold text-green-600">18</td>
                            <td class="px-6 py-4 text-center">2</td>
                            <td class="px-6 py-4 text-center">1</td>
                            <td class="px-6 py-4 text-center text-red-500 font-bold">3</td>
                            <td class="px-6 py-4 text-center">0</td>
                        </tr>
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</x-layout-app>