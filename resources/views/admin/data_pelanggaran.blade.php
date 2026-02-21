<x-layout-app title="Data Pelanggaran" role="admin">
    
    <header class="h-16 flex-none px-8 flex items-center justify-between bg-white border-b border-slate-200 sticky top-0 z-20">
        <h2 class="text-lg font-bold text-slate-800 tracking-tight">Data Pelanggaran</h2>
        <div class="flex items-center gap-2">
            <div class="w-7 h-7 rounded-full bg-blue-100 flex items-center justify-center text-blue-600 font-bold text-[10px]">A</div>
            <span class="text-xs font-bold text-slate-600">Admin</span>
        </div>
    </header>

    <div class="flex-1 overflow-y-auto p-6 custom-scroll bg-[#f8fafc]">
        
        <div class="w mx-auto space-y-5">

            <div class="bg-white rounded-[16px] border border-slate-200 shadow-sm p-5">
                <div class="flex flex-col xl:flex-row justify-between items-start xl:items-center gap-4">
                    
                    <div>
                        <h1 class="text-lg font-bold text-slate-800">Data Pelanggaran Siswa</h1>
                        <p class="text-slate-500 text-xs mt-0.5">
                            Monitoring catatan pelanggaran (Read Only).
                        </p>
                    </div>

                    <div class="flex flex-wrap items-center gap-3 w-full xl:w-auto">
                        
                        <div class="relative group">
                            <select class="appearance-none bg-white border border-slate-200 hover:border-slate-300 rounded-lg py-2 pl-3 pr-8 text-xs font-bold text-slate-600 outline-none focus:border-blue-500 cursor-pointer min-w-[130px] transition-all">
                                <option>Semua Kelas</option>
                                <option>Kelas 7</option>
                                <option>Kelas 8</option>
                                <option>Kelas 9</option>
                            </select>
                            <i class="fa-solid fa-chevron-down absolute right-3 top-3 text-[10px] text-slate-400 pointer-events-none"></i>
                        </div>

                        <div class="relative">
                            <input type="date" 
                                   class="bg-white border border-slate-200 hover:border-slate-300 rounded-lg py-2 px-3 text-xs font-bold text-slate-600 outline-none focus:border-blue-500 cursor-pointer transition-all"
                                   placeholder="mm/dd/yyyy">
                        </div>

                        <div class="relative flex-1 xl:w-[220px]">
                            <i class="fa-solid fa-magnifying-glass absolute left-3 top-2.5 text-slate-400 text-xs"></i>
                            <input type="text" 
                                   placeholder="Cari Nama..." 
                                   class="w-full bg-white border border-slate-200 hover:border-slate-300 rounded-lg py-2 pl-9 pr-3 text-xs font-bold text-slate-600 placeholder:text-slate-400 outline-none focus:border-blue-500 transition-all">
                        </div>

                    </div>
                </div>
            </div>

            <div class="bg-white rounded-[16px] border border-slate-200 shadow-sm p-0 overflow-hidden">
                
                <div class="overflow-x-auto">
                    <table class="w-full text-left border-collapse">
                        <thead class="border-b border-slate-100 bg-[#fbfcfd]">
                            <tr>
                                <th class="py-4 pl-6 pr-4 text-[10px] font-bold text-slate-400 uppercase tracking-widest">TANGGAL/WAKTU</th>
                                <th class="py-4 px-4 text-[10px] font-bold text-slate-400 uppercase tracking-widest">SISWA</th>
                                <th class="py-4 px-4 text-[10px] font-bold text-slate-400 uppercase tracking-widest">PELANGGARAN</th>
                                <th class="py-4 px-4 text-[10px] font-bold text-slate-400 uppercase tracking-widest w-[35%]">KRONOLOGI</th>
                                <th class="py-4 pr-6 pl-4 text-[10px] font-bold text-slate-400 uppercase tracking-widest text-right">POIN</th>
                            </tr>
                        </thead>
                        
                        <tbody class="divide-y divide-slate-50">
                            
                            <tr class="hover:bg-slate-50 transition group">
                                <td class="py-4 pl-6 pr-4 align-middle">
                                    <div class="flex flex-col">
                                        <span class="text-xs font-bold text-slate-600 font-mono">10 Jan 2026</span>
                                        <span class="text-[10px] font-bold text-slate-400 font-mono">09:30 WIB</span>
                                    </div>
                                </td>
                                
                                <td class="py-4 px-4 align-middle">
                                    <span class="text-sm font-bold text-slate-800">Doni Tata (9A)</span>
                                </td>
                                
                                <td class="py-4 px-4 align-middle">
                                    <span class="bg-red-50 text-red-600 border border-red-100 px-3 py-1 rounded-md text-xs font-bold inline-block">
                                        Merokok
                                    </span>
                                </td>
                                
                                <td class="py-4 px-4 align-middle">
                                    <p class="text-xs text-slate-500 italic leading-relaxed line-clamp-2">
                                        Siswa ditemukan merokok di belakang kantin saat jam istirahat.
                                    </p>
                                </td>
                                
                                <td class="py-4 pr-6 pl-4 align-middle text-right">
                                    <span class="text-sm font-bold text-red-600">+25</span>
                                </td>
                            </tr>

                            <tr class="hover:bg-slate-50 transition group">
                                <td class="py-4 pl-6 pr-4 align-middle">
                                    <div class="flex flex-col">
                                        <span class="text-xs font-bold text-slate-600 font-mono">12 Jan 2026</span>
                                        <span class="text-[10px] font-bold text-slate-400 font-mono">07:20 WIB</span>
                                    </div>
                                </td>
                                <td class="py-4 px-4 align-middle">
                                    <span class="text-sm font-bold text-slate-800">Budi Santoso (8B)</span>
                                </td>
                                <td class="py-4 px-4 align-middle">
                                    <span class="bg-orange-50 text-orange-600 border border-orange-100 px-3 py-1 rounded-md text-xs font-bold inline-block">
                                        Terlambat
                                    </span>
                                </td>
                                <td class="py-4 px-4 align-middle">
                                    <p class="text-xs text-slate-500 italic leading-relaxed line-clamp-2">
                                        Datang terlambat lebih dari 15 menit tanpa keterangan jelas.
                                    </p>
                                </td>
                                <td class="py-4 pr-6 pl-4 align-middle text-right">
                                    <span class="text-sm font-bold text-red-600">+5</span>
                                </td>
                            </tr>

                            <tr class="hover:bg-slate-50 transition group">
                                <td class="py-4 pl-6 pr-4 align-middle">
                                    <div class="flex flex-col">
                                        <span class="text-xs font-bold text-slate-600 font-mono">14 Jan 2026</span>
                                        <span class="text-[10px] font-bold text-slate-400 font-mono">07:00 WIB</span>
                                    </div>
                                </td>
                                <td class="py-4 px-4 align-middle">
                                    <span class="text-sm font-bold text-slate-800">Citra Kirana (7A)</span>
                                </td>
                                <td class="py-4 px-4 align-middle">
                                    <span class="bg-yellow-50 text-yellow-600 border border-yellow-100 px-3 py-1 rounded-md text-xs font-bold inline-block">
                                        Atribut
                                    </span>
                                </td>
                                <td class="py-4 px-4 align-middle">
                                    <p class="text-xs text-slate-500 italic leading-relaxed line-clamp-2">
                                        Tidak memakai dasi dan topi saat upacara bendera.
                                    </p>
                                </td>
                                <td class="py-4 pr-6 pl-4 align-middle text-right">
                                    <span class="text-sm font-bold text-red-600">+3</span>
                                </td>
                            </tr>

                        </tbody>
                    </table>
                </div>

                <div class="flex flex-col md:flex-row justify-between items-center p-4 border-t border-slate-100 bg-white">
                    <p class="text-slate-500 text-[10px] mb-3 md:mb-0 font-bold">
                        Menampilkan 1–10 dari 50 data
                    </p>
                    
                    <div class="flex items-center gap-1.5">
                        <button class="px-3 py-1 border border-slate-200 rounded-lg text-slate-600 text-[10px] font-bold hover:bg-slate-50 hover:border-slate-300 transition">
                            Prev
                        </button>
                        <button class="w-7 h-7 bg-[#2563eb] text-white rounded-lg text-[10px] font-bold shadow-md shadow-blue-200 transition">
                            1
                        </button>
                        <button class="w-7 h-7 bg-white border border-slate-200 text-slate-600 rounded-lg text-[10px] font-bold hover:bg-slate-50 hover:border-slate-300 transition">
                            2
                        </button>
                        <button class="px-3 py-1 border border-slate-200 rounded-lg text-slate-600 text-[10px] font-bold hover:bg-slate-50 hover:border-slate-300 transition">
                            Next
                        </button>
                    </div>
                </div>

            </div>

        </div> </div>

</x-layout-app>