<x-layout-app title="Peta Kedisiplinan" role="kepsek">
    <header class="h-16 flex-none px-8 flex items-center justify-between bg-white border-b border-slate-200 sticky top-0 z-20">
        <h2 class="text-lg font-bold text-slate-800 tracking-tight">Peta Kedisiplinan</h2>
        <div class="flex items-center gap-2">
            <div class="w-7 h-7 rounded-full bg-indigo-600 flex items-center justify-center text-white font-bold text-[10px]">KS</div>
            <span class="text-xs font-bold text-slate-600">Kepala Sekolah</span>
        </div>
    </header>

    <div class="flex-1 overflow-y-auto p-6 lg:p-8 custom-scroll bg-[#f8fafc]">
        <div class="w-full space-y-8">
            <div class="bg-white rounded-3xl border border-slate-200 shadow-sm overflow-hidden">
                <div class="p-6 bg-white border-b border-slate-100 flex justify-between items-center">
                    <div>
                        <h3 class="font-bold text-slate-800 text-base uppercase tracking-wide">Data Pelanggaran Siswa</h3>
                        <p class="text-xs text-slate-500 mt-1">Diurutkan dari poin tertinggi (Paling Kritis).</p>
                    </div>
                    <div class="flex gap-3">
                        <select class="border border-slate-200 p-2.5 rounded-xl text-xs bg-slate-50 outline-none font-bold text-slate-600 cursor-pointer">
                            <option>Semua Tingkat</option>
                            <option>Kelas 7</option>
                            <option>Kelas 8</option>
                            <option>Kelas 9</option>
                        </select>
                    </div>
                </div>
                
                <div class="overflow-x-auto">
                    <table class="w-full text-sm text-left border-collapse">
                        <thead class="bg-slate-50 border-b border-slate-100 text-slate-500 uppercase text-[10px] font-bold tracking-widest">
                            <tr>
                                <th class="py-4 pl-8 pr-4">Rank</th>
                                <th class="py-4 px-4">Nama Siswa</th>
                                <th class="py-4 px-4">Kelas</th>
                                <th class="py-4 px-4">Total Poin</th>
                                <th class="py-4 pr-8 pl-4 text-right">Status Tindakan</th>
                            </tr>
                        </thead>
                        <tbody class="divide-y divide-slate-50">
                            <tr class="hover:bg-red-50/50 transition">
                                <td class="py-4 pl-8 pr-4 font-bold text-slate-400 text-lg">#1</td>
                                <td class="py-4 px-4 font-bold text-slate-800 text-base">Doni Tata</td>
                                <td class="py-4 px-4"><span class="bg-slate-100 text-slate-600 px-3 py-1 rounded-lg text-xs font-bold">9A</span></td>
                                <td class="py-4 px-4 font-bold text-red-600 text-lg">100</td>
                                <td class="py-4 pr-8 pl-4 text-right">
                                    <span class="bg-red-100 text-red-800 px-4 py-1.5 rounded-lg text-[10px] font-bold uppercase tracking-wider">Panggilan Ortu</span>
                                </td>
                            </tr>
                            <tr class="hover:bg-orange-50/50 transition">
                                <td class="py-4 pl-8 pr-4 font-bold text-slate-400 text-lg">#2</td>
                                <td class="py-4 px-4 font-bold text-slate-800 text-base">Candra W</td>
                                <td class="py-4 px-4"><span class="bg-slate-100 text-slate-600 px-3 py-1 rounded-lg text-xs font-bold">9C</span></td>
                                <td class="py-4 px-4 font-bold text-orange-600 text-lg">85</td>
                                <td class="py-4 pr-8 pl-4 text-right">
                                    <span class="bg-orange-100 text-orange-800 px-4 py-1.5 rounded-lg text-[10px] font-bold uppercase tracking-wider">SP 3</span>
                                </td>
                            </tr>
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
</x-layout-app>