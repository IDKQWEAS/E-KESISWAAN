<x-layout-app title="Database Prestasi" role="admin">
    
    <header class="h-16 flex-none px-8 flex items-center justify-between bg-white border-b border-slate-200 sticky top-0 z-20">
        <h2 class="text-lg font-bold text-slate-800 tracking-tight">Manajemen Prestasi</h2>
        <div class="flex items-center gap-2">
            <div class="w-7 h-7 rounded-full bg-blue-100 flex items-center justify-center text-blue-600 font-bold text-[10px]">A</div>
            <span class="text-xs font-bold text-slate-600">Admin</span>
        </div>
    </header>

    <div class="flex-1 overflow-y-auto p-6 custom-scroll bg-[#f8fafc]">
        
        <div class="w mx-auto space-y-5">

            <div class="bg-white rounded-[16px] border border-slate-200 shadow-sm p-5 flex flex-col md:flex-row justify-between items-center gap-4">
                
                <div>
                    <h1 class="text-lg font-bold text-slate-800">Database Prestasi</h1>
                    <p class="text-slate-500 text-xs mt-0.5">
                        Kelola data prestasi siswa.
                    </p>
                </div>

                <div class="flex items-center gap-3">
                    
                    <div class="relative group">
                        <select class="appearance-none bg-slate-50 border border-transparent hover:border-slate-200 rounded-lg py-2 pl-3 pr-8 text-xs font-bold text-slate-700 outline-none focus:ring-2 focus:ring-blue-500/20 cursor-pointer min-w-[180px] transition-all">
                            <option>2025/2026 Ganjil (Aktif)</option>
                            <option>2024/2025 Genap</option>
                        </select>
                        <i class="fa-solid fa-chevron-down absolute right-3 top-3 text-[10px] text-slate-400 pointer-events-none group-hover:text-slate-600 transition"></i>
                    </div>

                    <button class="bg-[#2563eb] hover:bg-blue-700 text-white py-2 px-4 rounded-lg text-xs font-bold shadow-md shadow-blue-200 transition flex items-center gap-2">
                        <i class="fa-solid fa-plus text-[10px]"></i> Tambah Prestasi
                    </button>
                </div>
            </div>

            <div class="bg-white rounded-[16px] border border-slate-200 shadow-sm p-4 hover:shadow-md transition group">
                <div class="flex flex-col md:flex-row gap-5">
                    
                    <div class="w-full md:w-48 h-32 rounded-xl overflow-hidden relative flex-shrink-0 bg-slate-100">
                        <img src="https://images.unsplash.com/photo-1571260899304-425eee4c7efc?w=500&q=80" class="w-full h-full object-cover transform group-hover:scale-105 transition duration-500">
                    </div>

                    <div class="flex-1 py-1 flex flex-col justify-center">
                        
                        <div class="flex gap-2 mb-2">
                            <span class="bg-blue-50 text-blue-600 px-2 py-0.5 rounded text-[10px] font-bold uppercase tracking-wide border border-blue-100">Akademik</span>
                            <span class="bg-purple-50 text-purple-600 px-2 py-0.5 rounded text-[10px] font-bold uppercase tracking-wide border border-purple-100">Kabupaten</span>
                        </div>

                        <h3 class="text-base font-bold text-slate-800 mb-1 group-hover:text-[#2563eb] transition">
                            Juara 1 Olimpiade Matematika
                        </h3>

                        <div class="space-y-1">
                            <p class="text-xs text-slate-500 font-medium flex items-center gap-1.5">
                                <i class="fa-solid fa-user-graduate text-slate-400"></i> Andi Saputra (7A)
                            </p>
                            <div class="flex items-center gap-3 text-[11px] text-slate-400">
                                <span class="flex items-center gap-1.5">
                                    <i class="fa-regular fa-calendar"></i> 12 Januari 2026
                                </span>
                                <span class="w-1 h-1 rounded-full bg-slate-300"></span>
                                <span class="flex items-center gap-1.5">
                                    <i class="fa-solid fa-building-columns"></i> Dinas Pendidikan
                                </span>
                            </div>
                        </div>
                    </div>

                    <div class="flex md:flex-col justify-end items-end gap-2 pl-4 md:border-l border-slate-50">
                        <button class="w-8 h-8 rounded-lg text-slate-400 hover:text-blue-600 hover:bg-blue-50 flex items-center justify-center transition" title="Edit">
                            <i class="fa-regular fa-pen-to-square"></i>
                        </button>
                        <button class="w-8 h-8 rounded-lg text-slate-400 hover:text-red-600 hover:bg-red-50 flex items-center justify-center transition" title="Hapus">
                            <i class="fa-regular fa-trash-can"></i>
                        </button>
                    </div>

                </div>
            </div>

            <div class="bg-white rounded-[16px] border border-slate-200 shadow-sm p-4 hover:shadow-md transition group">
                <div class="flex flex-col md:flex-row gap-5">
                    
                    <div class="w-full md:w-48 h-32 rounded-xl overflow-hidden relative flex-shrink-0 bg-slate-100">
                        <img src="https://images.unsplash.com/photo-1517466787929-bc90951d6dbb?w=500&q=80" class="w-full h-full object-cover transform group-hover:scale-105 transition duration-500">
                    </div>

                    <div class="flex-1 py-1 flex flex-col justify-center">
                        <div class="flex gap-2 mb-2">
                            <span class="bg-orange-50 text-orange-600 px-2 py-0.5 rounded text-[10px] font-bold uppercase tracking-wide border border-orange-100">Non-Akademik</span>
                            <span class="bg-yellow-50 text-yellow-600 px-2 py-0.5 rounded text-[10px] font-bold uppercase tracking-wide border border-yellow-100">Provinsi</span>
                        </div>
                        <h3 class="text-base font-bold text-slate-800 mb-1 group-hover:text-[#2563eb] transition">
                            Juara 2 Futsal Pelajar
                        </h3>
                        <div class="space-y-1">
                            <p class="text-xs text-slate-500 font-medium flex items-center gap-1.5">
                                <i class="fa-solid fa-users text-slate-400"></i> Tim Futsal Sekolah
                            </p>
                            <div class="flex items-center gap-3 text-[11px] text-slate-400">
                                <span class="flex items-center gap-1.5">
                                    <i class="fa-regular fa-calendar"></i> 10 Januari 2026
                                </span>
                                <span class="w-1 h-1 rounded-full bg-slate-300"></span>
                                <span class="flex items-center gap-1.5">
                                    <i class="fa-solid fa-building-columns"></i> GOR Klaten
                                </span>
                            </div>
                        </div>
                    </div>

                    <div class="flex md:flex-col justify-end items-end gap-2 pl-4 md:border-l border-slate-50">
                        <button class="w-8 h-8 rounded-lg text-slate-400 hover:text-blue-600 hover:bg-blue-50 flex items-center justify-center transition">
                            <i class="fa-regular fa-pen-to-square"></i>
                        </button>
                        <button class="w-8 h-8 rounded-lg text-slate-400 hover:text-red-600 hover:bg-red-50 flex items-center justify-center transition">
                            <i class="fa-regular fa-trash-can"></i>
                        </button>
                    </div>
                </div>
            </div>

            <div class="bg-white rounded-[16px] border border-slate-200 shadow-sm p-4 flex flex-col md:flex-row justify-between items-center">
                <p class="text-slate-500 text-[10px] mb-3 md:mb-0 font-bold">
                    Menampilkan 1–5 dari 20 prestasi
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

        </div> </div>

</x-layout-app>