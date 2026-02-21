<x-layout-app title="Cetak Laporan" role="admin">
    
    <header class="h-16 flex-none px-8 flex items-center justify-between bg-white border-b border-slate-200 sticky top-0 z-20">
        <h2 class="text-lg font-bold text-slate-800 tracking-tight">Pusat Laporan</h2>
        <div class="flex items-center gap-2">
            <div class="w-7 h-7 rounded-full bg-blue-100 flex items-center justify-center text-blue-600 font-bold text-[10px]">A</div>
            <span class="text-xs font-bold text-slate-600">Admin</span>
        </div>
    </header>

    <div class="flex-1 overflow-y-auto p-8 custom-scroll bg-[#f8fafc]">
        
        <div class=" mx-auto space-y-8">

            <div class="flex flex-col md:flex-row justify-between items-end gap-4">
                <div>
                    <h1 class="text-2xl font-bold text-slate-800 mb-1">Pusat Laporan</h1>
                    <p class="text-slate-500 text-sm">
                        Unduh rekapitulasi data arsip sekolah.
                    </p>
                </div>
                
                <div class="relative group">
                    <select class="appearance-none bg-white border border-slate-200 hover:border-slate-300 rounded-xl py-2.5 pl-4 pr-10 text-sm font-bold text-slate-700 outline-none focus:ring-2 focus:ring-blue-500/20 cursor-pointer min-w-[200px] transition-all shadow-sm">
                        <option>2025/2026 Ganjil (Aktif)</option>
                        <option>2024/2025 Genap</option>
                    </select>
                    <i class="fa-solid fa-chevron-down absolute right-4 top-3.5 text-xs text-slate-400 pointer-events-none group-hover:text-slate-600 transition"></i>
                </div>
            </div>

            <div class="grid grid-cols-1 md:grid-cols-3 gap-8">
                
                <div class="bg-white p-10 rounded-[2rem] shadow-sm border border-slate-200 text-center hover:shadow-xl hover:-translate-y-1 transition-all duration-300 cursor-pointer group relative overflow-hidden">
                    
                    <div class="absolute top-0 left-0 w-full h-1.5 bg-red-500 transform scale-x-0 group-hover:scale-x-100 transition-transform duration-500 origin-center"></div>
                    
                    <div class="w-24 h-24 bg-red-50 rounded-full flex items-center justify-center mx-auto mb-6 group-hover:bg-red-100 transition-colors duration-300">
                        <i class="fa-solid fa-file-pdf text-4xl text-red-500"></i>
                    </div>
                    
                    <h3 class="font-bold text-slate-800 text-xl mb-8">Laporan Absensi</h3>
                    
                    <div class="flex gap-3 justify-center">
                        <button class="px-6 py-2.5 border border-red-100 text-red-600 rounded-full text-[11px] font-bold uppercase tracking-widest hover:bg-red-50 transition">
                            PDF
                        </button>
                        <button class="px-6 py-2.5 border border-green-100 text-green-600 rounded-full text-[11px] font-bold uppercase tracking-widest hover:bg-green-50 transition">
                            EXCEL
                        </button>
                    </div>
                </div>

                <div class="bg-white p-10 rounded-[2rem] shadow-sm border border-slate-200 text-center hover:shadow-xl hover:-translate-y-1 transition-all duration-300 cursor-pointer group relative overflow-hidden">
                    
                    <div class="absolute top-0 left-0 w-full h-1.5 bg-green-500 transform scale-x-0 group-hover:scale-x-100 transition-transform duration-500 origin-center"></div>
                    
                    <div class="w-24 h-24 bg-green-50 rounded-full flex items-center justify-center mx-auto mb-6 group-hover:bg-green-100 transition-colors duration-300">
                        <i class="fa-solid fa-file-invoice text-4xl text-green-600"></i>
                    </div>
                    
                    <h3 class="font-bold text-slate-800 text-xl mb-8">Laporan Pelanggaran</h3>
                    
                    <div class="flex gap-3 justify-center">
                        <button class="px-6 py-2.5 border border-red-100 text-red-600 rounded-full text-[11px] font-bold uppercase tracking-widest hover:bg-red-50 transition">
                            PDF
                        </button>
                        <button class="px-6 py-2.5 border border-green-100 text-green-600 rounded-full text-[11px] font-bold uppercase tracking-widest hover:bg-green-50 transition">
                            EXCEL
                        </button>
                    </div>
                </div>

                <div class="bg-white p-10 rounded-[2rem] shadow-sm border border-slate-200 text-center hover:shadow-xl hover:-translate-y-1 transition-all duration-300 cursor-pointer group relative overflow-hidden">
                    
                    <div class="absolute top-0 left-0 w-full h-1.5 bg-yellow-500 transform scale-x-0 group-hover:scale-x-100 transition-transform duration-500 origin-center"></div>
                    
                    <div class="w-24 h-24 bg-yellow-50 rounded-full flex items-center justify-center mx-auto mb-6 group-hover:bg-yellow-100 transition-colors duration-300">
                        <i class="fa-solid fa-trophy text-4xl text-yellow-600"></i>
                    </div>
                    
                    <h3 class="font-bold text-slate-800 text-xl mb-8">Laporan Prestasi</h3>
                    
                    <div class="flex gap-3 justify-center">
                        <button class="px-6 py-2.5 border border-red-100 text-red-600 rounded-full text-[11px] font-bold uppercase tracking-widest hover:bg-red-50 transition">
                            PDF
                        </button>
                        <button class="px-6 py-2.5 border border-green-100 text-green-600 rounded-full text-[11px] font-bold uppercase tracking-widest hover:bg-green-50 transition">
                            EXCEL
                        </button>
                    </div>
                </div>

            </div>

        </div> </div>

</x-layout-app>