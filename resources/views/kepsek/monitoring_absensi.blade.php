<x-layout-app title="Monitoring Absensi" role="kepsek">
    <header class="h-16 flex-none px-8 flex items-center justify-between bg-white border-b border-slate-200 sticky top-0 z-20">
        <h2 class="text-lg font-bold text-slate-800 tracking-tight">Monitoring Absensi</h2>
        <div class="flex items-center gap-2">
            <div class="w-7 h-7 rounded-full bg-indigo-600 flex items-center justify-center text-white font-bold text-[10px]">KS</div>
            <span class="text-xs font-bold text-slate-600">Kepala Sekolah</span>
        </div>
    </header>

    <div class="flex-1 overflow-y-auto p-6 lg:p-8 custom-scroll bg-[#f8fafc]">
        <div class="w-full space-y-6">
            
            <div class="bg-white p-6 rounded-[20px] shadow-sm border border-slate-200 flex flex-col md:flex-row justify-between items-start md:items-center gap-4">
                <div>
                    <h3 class="font-bold text-lg text-slate-800">Monitoring Rekap Absensi</h3>
                    <p class="text-sm text-slate-500 mt-1">Pantauan kehadiran siswa harian dari laporan BK/Operator.</p>
                </div>
                <div class="flex flex-wrap items-center gap-3">
                    <select class="border border-slate-200 p-2 rounded-lg text-xs font-bold text-slate-600 outline-none cursor-pointer">
                        <option>Semua Kelas</option>
                        <option>7A</option>
                        <option>9A</option>
                    </select>
                    <input type="date" class="border p-2 rounded-xl text-xs text-slate-600 outline-none" />
                    <button class="bg-primary text-white px-4 py-2 rounded-xl text-xs font-bold shadow hover:bg-blue-700 transition">
                        Terapkan Filter
                    </button>
                </div>
            </div>

            <div class="bg-white rounded-[20px] border border-slate-200 overflow-hidden shadow-sm w-full">
                <div class="p-5 bg-slate-50 border-b border-slate-100 flex items-center justify-between">
                    <h4 class="font-bold text-slate-800 text-sm">Rekapitulasi Kelas 9A</h4>
                </div>
                <div class="overflow-x-auto">
                    <table class="w-full text-left border-collapse">
                        <thead class="bg-white border-b border-slate-100 text-slate-400 text-[10px] font-bold uppercase tracking-widest">
                            <tr>
                                <th class="py-4 pl-6 pr-4">Nama Siswa</th>
                                <th class="py-4 px-4 text-center text-green-600">Hadir</th>
                                <th class="py-4 px-4 text-center text-blue-500">Sakit</th>
                                <th class="py-4 px-4 text-center text-orange-500">Izin</th>
                                <th class="py-4 pr-6 pl-4 text-center text-red-500">Alpha</th>
                            </tr>
                        </thead>
                        <tbody class="divide-y divide-slate-50 text-sm">
                            <tr class="hover:bg-slate-50 transition">
                                <td class="py-4 pl-6 pr-4 font-bold text-slate-800">Andi Saputra</td>
                                <td class="py-4 px-4 text-center font-bold text-green-600">22</td>
                                <td class="py-4 px-4 text-center font-medium text-blue-500">1</td>
                                <td class="py-4 px-4 text-center font-medium text-orange-500">0</td>
                                <td class="py-4 pr-6 pl-4 text-center font-medium text-red-500">0</td>
                            </tr>
                            <tr class="hover:bg-slate-50 transition">
                                <td class="py-4 pl-6 pr-4 font-bold text-slate-800">Doni Tata</td>
                                <td class="py-4 px-4 text-center font-bold text-green-600">18</td>
                                <td class="py-4 px-4 text-center font-medium text-blue-500">2</td>
                                <td class="py-4 px-4 text-center font-medium text-orange-500">1</td>
                                <td class="py-4 pr-6 pl-4 text-center font-bold text-red-500">3</td>
                            </tr>
                        </tbody>
                    </table>
                </div>
            </div>

        </div>
    </div>
</x-layout-app>