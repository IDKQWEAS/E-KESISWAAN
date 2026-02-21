<x-layout-app title="Monitoring Home Visit" role="kepsek">
    
    <header class="h-16 flex-none px-8 flex items-center justify-between bg-white border-b border-slate-200 sticky top-0 z-20">
        <h2 class="text-lg font-bold text-slate-800 tracking-tight">Monitoring Home Visit</h2>
        <div class="flex items-center gap-2">
            <div class="w-7 h-7 rounded-full bg-indigo-600 flex items-center justify-center text-white font-bold text-[10px]">KS</div>
            <span class="text-xs font-bold text-slate-600">Kepala Sekolah</span>
        </div>
    </header>

    <div class="flex-1 overflow-y-auto p-6 lg:p-8 custom-scroll bg-[#f8fafc]">
        
        <div class="w-full space-y-6">
            
            <div class="bg-white p-6 lg:p-8 rounded-[24px] shadow-sm border border-slate-200">
                <h3 class="font-bold text-2xl text-slate-800 mb-1">Monitoring Home Visit</h3>
                <p class="text-sm text-slate-500">Jadwal dan status kunjungan rumah siswa.</p>
            </div>

            <div class="grid grid-cols-1 lg:grid-cols-2 gap-6">
                
                <div class="bg-white rounded-[24px] shadow-sm border border-slate-200 overflow-hidden flex flex-col">
                    <div class="p-5 lg:p-6 bg-[#f0fdf4] border-b border-[#dcfce7] flex justify-between items-center">
                        <h4 class="font-bold text-green-800 text-sm">Sudah Terlaksana</h4>
                        <span class="bg-green-200 text-green-800 px-2 py-0.5 rounded text-[10px] font-bold">2 Selesai</span>
                    </div>
                    
                    <div class="p-4 space-y-3">
                        <div class="p-3 border border-slate-100 rounded-xl flex justify-between items-center">
                            <div class="flex items-center gap-4">
                                <div class="w-8 h-8 rounded-full bg-slate-100 flex items-center justify-center text-xs font-bold text-slate-500">
                                    AS
                                </div>
                                <div>
                                    <p class="text-sm font-bold text-slate-700">Andi Saputra</p>
                                    <p class="text-[10px] text-slate-400">10 Jan 2026</p>
                                </div>
                            </div>
                            <i class="fa-solid fa-circle-check text-[#16a34a] text-xl"></i>
                        </div>
                        
                        <div class="p-4 border border-slate-100 rounded-[16px] flex justify-between items-center bg-white shadow-sm hover:border-slate-300 transition cursor-pointer">
                            <div class="flex items-center gap-4">
                                <div class="w-8 h-8 rounded-full bg-slate-100 flex items-center justify-center text-xs font-bold text-slate-500">
                                    AS
                                </div>
                                <div>
                                    <p class="text-sm font-bold text-slate-700">Andi Saputra</p>
                                    <p class="text-[10px] text-slate-400">10 Jan 2026</p>
                                </div>
                            </div>
                            <i class="fa-solid fa-circle-check text-[#16a34a] text-xl"></i>
                        </div>
                    </div>
                </div>

                <div class="bg-white rounded-[24px] shadow-sm border border-slate-200 overflow-hidden flex flex-col">
                    <div class="p-4 bg-orange-50 border-b border-orange-100 flex justify-between items-center">
                        <h4 class="font-bold text-orange-800 text-sm">Rencana Kunjungan</h4>
                        <span class="bg-orange-200 text-orange-800 px-2 py-0.5 rounded text-[10px] font-bold">1 Pending</span>
                    </div>
                    
                    <div class="p-4 space-y-3">
                        <div class="p-3 border border-orange-100 bg-orange-50/30 rounded-xl flex justify-between items-center">
                            <div class="flex gap-3 items-center">
                                <div class="w-8 h-8 rounded-full bg-red-100 flex items-center justify-center text-xs font-bold text-red-600">
                                    DT
                                </div>
                                <div>
                                    <p class="text-sm font-bold text-slate-700">Doni Tata</p>
                                    <p class="text-[10px] text-red-500">Kasus: Sering Bolos</p>
                                </div>
                            </div>
                            <span class="text-[13px] font-medium text-slate-500">Besok, 09:00</span>
                        </div>
                    </div>
                </div>

            </div>

        </div>
    </div>
</x-layout-app>