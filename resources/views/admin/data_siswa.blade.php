<x-layout-app title="Database Siswa" role="admin">
    
    <header class="h-16 flex-none px-8 flex items-center justify-between bg-white border-b border-slate-200 sticky top-0 z-20">
        <h2 class="text-lg font-bold text-slate-800 tracking-tight">Manajemen Siswa</h2>
        <div class="flex items-center gap-2">
            <div class="w-7 h-7 rounded-full bg-blue-100 flex items-center justify-center text-blue-600 font-bold text-[10px]">A</div>
            <span class="text-xs font-bold text-slate-600">Admin</span>
        </div>
    </header>

    <div class="flex-1 overflow-y-auto p-6 custom-scroll bg-[#f8fafc]">
        
        <div class="bg-white rounded-[16px] border border-slate-200 shadow-sm p-5 mb-5">
            <div class="flex flex-col xl:flex-row justify-between items-start xl:items-center gap-4">
                
                <div>
                    <h1 class="text-lg font-bold text-slate-800">Database Siswa</h1>
                    <p class="text-slate-500 text-xs mt-0.5">
                        Total: 320 Siswa Aktif
                    </p>
                </div>

                <div class="flex flex-wrap items-center gap-3 w-full xl:w-auto">
                    
                    <div class="relative group">
                        <select class="appearance-none bg-slate-50 border border-transparent hover:border-slate-200 rounded-lg py-2 pl-3 pr-8 text-xs font-bold text-slate-700 outline-none focus:ring-2 focus:ring-blue-500/20 cursor-pointer min-w-[160px] transition-all">
                            <option>2025/2026 Ganjil</option>
                            <option>2024/2025 Genap</option>
                        </select>
                        <i class="fa-solid fa-chevron-down absolute right-3 top-3 text-[10px] text-slate-400 pointer-events-none"></i>
                    </div>

                    <div class="relative flex-1 xl:w-[240px]">
                        <i class="fa-solid fa-magnifying-glass absolute left-3 top-2.5 text-slate-400 text-xs"></i>
                        <input type="text" 
                               placeholder="Cari Nama / NISN..." 
                               class="w-full bg-slate-50 border border-transparent hover:border-slate-200 rounded-lg py-2 pl-9 pr-3 text-xs font-bold text-slate-700 placeholder:text-slate-400 outline-none focus:ring-2 focus:ring-blue-500/20 transition-all">
                    </div>

                    <div class="relative group">
                        <select class="appearance-none bg-slate-50 border border-transparent hover:border-slate-200 rounded-lg py-2 pl-3 pr-8 text-xs font-bold text-slate-700 outline-none focus:ring-2 focus:ring-blue-500/20 cursor-pointer min-w-[120px] transition-all">
                            <option>Semua Kelas</option>
                            <option>7A</option>
                            <option>7B</option>
                            <option>9A</option>
                        </select>
                        <i class="fa-solid fa-chevron-down absolute right-3 top-3 text-[10px] text-slate-400 pointer-events-none"></i>
                    </div>

                </div>
            </div>
        </div>

        <div class="bg-white rounded-[16px] border border-slate-200 shadow-sm p-0 overflow-hidden">
            
            <div class="overflow-x-auto">
                <table class="w-full text-left border-collapse">
                    <thead class="border-b border-slate-100 bg-white">
                        <tr>
                            <th class="py-4 pl-6 pr-4 text-[10px] font-bold text-slate-400 uppercase tracking-widest">SISWA</th>
                            <th class="py-4 px-4 text-[10px] font-bold text-slate-400 uppercase tracking-widest text-center">KELAS</th>
                            <th class="py-4 px-4 text-[10px] font-bold text-slate-400 uppercase tracking-widest text-center">GENDER</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-slate-50">
                        
                        <tr class="hover:bg-slate-50 transition group cursor-pointer">
                            <td class="py-3 pl-6 pr-4"> <div class="flex items-center gap-3">
                                    <div class="w-9 h-9 rounded-full bg-[#dbeafe] text-[#2563eb] flex items-center justify-center font-bold text-[10px] shadow-sm border border-white group-hover:border-blue-100 transition">
                                        AS
                                    </div>
                                    <div>
                                        <h4 class="text-sm font-bold text-slate-800 mb-0 group-hover:text-blue-600 transition">Andi Saputra</h4>
                                        <p class="text-[10px] font-medium text-slate-400">1234567890</p>
                                    </div>
                                </div>
                            </td>
                            <td class="py-3 px-4 text-center">
                                <span class="bg-slate-100 text-slate-600 border border-slate-200 px-2.5 py-1 rounded-md text-xs font-bold inline-block min-w-[40px]">
                                    7A
                                </span>
                            </td>
                            <td class="py-3 px-4 text-center">
                                <span class="bg-[#eff6ff] text-[#2563eb] border border-blue-100 px-3 py-1 rounded-md text-[10px] font-bold inline-flex items-center gap-1.5">
                                    <i class="fa-solid fa-mars text-[9px]"></i> Laki-laki
                                </span>
                            </td>
                        </tr>

                        <tr class="hover:bg-slate-50 transition group cursor-pointer">
                            <td class="py-3 pl-6 pr-4">
                                <div class="flex items-center gap-3">
                                    <div class="w-9 h-9 rounded-full bg-slate-100 text-slate-500 flex items-center justify-center font-bold text-[10px] shadow-sm border border-white group-hover:border-slate-200 transition">
                                        BS
                                    </div>
                                    <div>
                                        <h4 class="text-sm font-bold text-slate-800 mb-0 group-hover:text-blue-600 transition">Budi Santoso</h4>
                                        <p class="text-[10px] font-medium text-slate-400">1234567891</p>
                                    </div>
                                </div>
                            </td>
                            <td class="py-3 px-4 text-center">
                                <span class="bg-slate-100 text-slate-600 border border-slate-200 px-2.5 py-1 rounded-md text-xs font-bold inline-block min-w-[40px]">
                                    7B
                                </span>
                            </td>
                            <td class="py-3 px-4 text-center">
                                <span class="bg-[#eff6ff] text-[#2563eb] border border-blue-100 px-3 py-1 rounded-md text-[10px] font-bold inline-flex items-center gap-1.5">
                                    <i class="fa-solid fa-mars text-[9px]"></i> Laki-laki
                                </span>
                            </td>
                        </tr>

                        <tr class="hover:bg-slate-50 transition group cursor-pointer">
                            <td class="py-3 pl-6 pr-4">
                                <div class="flex items-center gap-3">
                                    <div class="w-9 h-9 rounded-full bg-pink-100 text-pink-600 flex items-center justify-center font-bold text-[10px] shadow-sm border border-white group-hover:border-pink-200 transition">
                                        CK
                                    </div>
                                    <div>
                                        <h4 class="text-sm font-bold text-slate-800 mb-0 group-hover:text-blue-600 transition">Citra Kirana</h4>
                                        <p class="text-[10px] font-medium text-slate-400">1234567892</p>
                                    </div>
                                </div>
                            </td>
                            <td class="py-3 px-4 text-center">
                                <span class="bg-slate-100 text-slate-600 border border-slate-200 px-2.5 py-1 rounded-md text-xs font-bold inline-block min-w-[40px]">
                                    8A
                                </span>
                            </td>
                            <td class="py-3 px-4 text-center">
                                <span class="bg-pink-50 text-pink-600 border border-pink-100 px-3 py-1 rounded-md text-[10px] font-bold inline-flex items-center gap-1.5">
                                    <i class="fa-solid fa-venus text-[9px]"></i> Perempuan
                                </span>
                            </td>
                        </tr>

                    </tbody>
                </table>
            </div>

            <div class="flex flex-col md:flex-row justify-between items-center p-4 border-t border-slate-100 bg-white">
                <p class="text-slate-500 text-[10px] mb-3 md:mb-0 font-bold">
                    Menampilkan 1–10 dari 320 data
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
    </div>

</x-layout-app>