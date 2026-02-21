<x-layout-app title="Data Kehadiran" role="admin">
    
    <header class="h-20 flex-none px-8 flex items-center justify-between bg-white border-b border-slate-200 sticky top-0 z-20">
        <h2 class="text-xl font-bold text-slate-800 tracking-tight">Data Kehadiran</h2>
        <div class="flex items-center gap-2">
            <div class="w-8 h-8 rounded-full bg-blue-100 flex items-center justify-center text-blue-600 font-bold text-xs">A</div>
            <span class="text-sm font-bold text-slate-600">Admin</span>
        </div>
    </header>

    <div class="flex-1 overflow-y-auto p-8 custom-scroll bg-slate-50">
        
        <div class="bg-white rounded-[20px] border border-slate-200 shadow-sm p-8">
            
            <div class="flex flex-col xl:flex-row justify-between items-start xl:items-center gap-6 mb-8">
                
                <div>
                    <h1 class="text-2xl font-bold text-slate-800 mb-1">Rekap Data Kehadiran</h1>
                    <p class="text-slate-500 text-sm max-w-md leading-relaxed">
                        Monitoring harian untuk diteruskan ke Guru BK.
                    </p>
                </div>

                <div class="flex flex-wrap items-center gap-3">
                    
                    <div class="relative">
                        <select class="appearance-none bg-white border border-slate-200 rounded-xl py-2.5 pl-4 pr-10 text-sm font-bold text-slate-600 outline-none hover:border-slate-300 focus:border-blue-500 cursor-pointer min-w-[200px]">
                            <option>2025/2026 Ganjil (Aktif)</option>
                            <option>2024/2025 Genap</option>
                        </select>
                        <i class="fa-solid fa-chevron-down absolute right-4 top-3.5 text-xs text-slate-400 pointer-events-none"></i>
                    </div>

                    <div class="relative">
                        <input type="date" class="bg-white border border-slate-200 rounded-xl py-2.5 px-4 text-sm font-bold text-slate-600 outline-none hover:border-slate-300 focus:border-blue-500 cursor-pointer" placeholder="mm/dd/yyyy">
                    </div>

                    <div class="relative">
                        <select class="appearance-none bg-white border border-slate-200 rounded-xl py-2.5 pl-4 pr-10 text-sm font-bold text-slate-600 outline-none hover:border-slate-300 focus:border-blue-500 cursor-pointer min-w-[140px]">
                            <option>Semua Kelas</option>
                            <option>7A</option>
                            <option>7B</option>
                            <option>9A</option>
                        </select>
                        <i class="fa-solid fa-chevron-down absolute right-4 top-3.5 text-xs text-slate-400 pointer-events-none"></i>
                    </div>

                    <div class="relative">
                        <select class="appearance-none bg-white border border-slate-200 rounded-xl py-2.5 pl-4 pr-10 text-sm font-bold text-slate-600 outline-none hover:border-slate-300 focus:border-blue-500 cursor-pointer min-w-[150px]">
                            <option>Semua Status</option>
                            <option>Tepat Waktu</option>
                            <option>Terlambat</option>
                        </select>
                        <i class="fa-solid fa-chevron-down absolute right-4 top-3.5 text-xs text-slate-400 pointer-events-none"></i>
                    </div>

                    <button class="bg-[#2563eb] hover:bg-blue-700 text-white font-bold py-2.5 px-6 rounded-xl text-sm transition shadow-md shadow-blue-200">
                        Terapkan
                    </button>
                </div>
            </div>

            <div class="overflow-x-auto">
                <table class="w-full text-left border-collapse">
                    <thead class="border-b border-slate-100">
                        <tr>
                            <th class="py-4 pr-4 text-[11px] font-bold text-slate-400 uppercase tracking-wider">WAKTU DATANG</th>
                            <th class="py-4 px-4 text-[11px] font-bold text-slate-400 uppercase tracking-wider">NISN</th>
                            <th class="py-4 px-4 text-[11px] font-bold text-slate-400 uppercase tracking-wider">NAMA SISWA</th>
                            <th class="py-4 px-4 text-[11px] font-bold text-slate-400 uppercase tracking-wider">KELAS</th>
                            <th class="py-4 pl-4 text-[11px] font-bold text-slate-400 uppercase tracking-wider text-right">STATUS</th>
                        </tr>
                    </thead>
                    <tbody class="text-sm font-medium text-slate-600 divide-y divide-slate-50">
                        
                        <tr class="hover:bg-slate-50 transition group">
                            <td class="py-5 pr-4 text-slate-500 font-mono">06:30</td>
                            <td class="py-5 px-4">
                                <a href="#" class="text-[#2563eb] font-bold hover:underline">12345670</a>
                            </td>
                            <td class="py-5 px-4 font-bold text-slate-800">Siti Aminah</td>
                            <td class="py-5 px-4">
                                <span class="bg-slate-100 text-slate-600 px-3 py-1.5 rounded-lg text-xs font-bold">7B</span>
                            </td>
                            <td class="py-5 pl-4 text-right">
                                <span class="bg-blue-50 text-[#2563eb] px-4 py-2 rounded-lg text-[11px] font-bold uppercase">TEPAT WAKTU</span>
                            </td>
                        </tr>

                        <tr class="hover:bg-slate-50 transition group">
                            <td class="py-5 pr-4 text-red-600 font-bold font-mono">07:15</td>
                            <td class="py-5 px-4 text-slate-500 font-bold">12349999</td>
                            <td class="py-5 px-4 font-bold text-slate-800">Doni Tata</td>
                            <td class="py-5 px-4">
                                <span class="bg-red-50 text-red-500 border border-red-100 px-3 py-1.5 rounded-lg text-xs font-bold">9A</span>
                            </td>
                            <td class="py-5 pl-4 text-right">
                                <span class="bg-[#ef4444] text-white px-4 py-2 rounded-lg text-[11px] font-bold uppercase shadow-sm shadow-red-200">TERLAMBAT</span>
                            </td>
                        </tr>

                        <tr class="hover:bg-slate-50 transition group">
                            <td class="py-5 pr-4 text-slate-500 font-mono">06:45</td>
                            <td class="py-5 px-4">
                                <a href="#" class="text-[#2563eb] font-bold hover:underline">12345671</a>
                            </td>
                            <td class="py-5 px-4 font-bold text-slate-800">Budi Santoso</td>
                            <td class="py-5 px-4">
                                <span class="bg-slate-100 text-slate-600 px-3 py-1.5 rounded-lg text-xs font-bold">7B</span>
                            </td>
                            <td class="py-5 pl-4 text-right">
                                <span class="bg-blue-50 text-[#2563eb] px-4 py-2 rounded-lg text-[11px] font-bold uppercase">TEPAT WAKTU</span>
                            </td>
                        </tr>

                    </tbody>
                </table>
            </div>

            <div class="flex flex-col md:flex-row justify-between items-center mt-8 pt-6 border-t border-slate-100">
                <p class="text-slate-500 text-sm mb-4 md:mb-0">
                    Menampilkan 1–10 dari 150 data
                </p>
                
                <div class="flex items-center gap-2">
                    <button class="px-4 py-2 border border-slate-200 rounded-xl text-slate-600 text-sm font-bold hover:bg-slate-50 hover:border-slate-300 transition">
                        Prev
                    </button>
                    <button class="w-10 h-10 bg-[#2563eb] text-white rounded-xl text-sm font-bold shadow-md shadow-blue-200 transition">
                        1
                    </button>
                    <button class="w-10 h-10 bg-white border border-slate-200 text-slate-600 rounded-xl text-sm font-bold hover:bg-slate-50 hover:border-slate-300 transition">
                        2
                    </button>
                    <button class="px-4 py-2 border border-slate-200 rounded-xl text-slate-600 text-sm font-bold hover:bg-slate-50 hover:border-slate-300 transition">
                        Next
                    </button>
                </div>
            </div>

        </div>
    </div>

</x-layout-app>