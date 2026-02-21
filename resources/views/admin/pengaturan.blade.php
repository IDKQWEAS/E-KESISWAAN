<x-layout-app title="Pengaturan Sistem" role="admin">
    
    <header class="h-16 flex-none px-8 flex items-center justify-between bg-white border-b border-slate-200 sticky top-0 z-20">
        <h2 class="text-lg font-bold text-slate-800 tracking-tight">Pengaturan</h2>
        <div class="flex items-center gap-2">
            <div class="w-7 h-7 rounded-full bg-blue-100 flex items-center justify-center text-blue-600 font-bold text-[10px]">A</div>
            <span class="text-xs font-bold text-slate-600">Admin</span>
        </div>
    </header>

    <div class="flex-1 overflow-y-auto p-8 custom-scroll bg-[#f8fafc]">
        
        <div class="w-full flex justify-center">

            <div class="bg-white rounded-[20px] border border-slate-200 shadow-sm p-8 w-full max-w-3xl">
                
                <div class="flex items-center gap-3 mb-8">
                    <i class="fa-solid fa-gear text-[#2563eb] text-xl"></i>
                    <h1 class="text-xl font-bold text-slate-800">Pengaturan Sistem</h1>
                </div>

                <form action="#" class="space-y-8">
                    
                    <div>
                        <label class="block text-sm font-bold text-slate-700 mb-1">Maksimal Poin Pelanggaran</label>
                        <p class="text-slate-500 text-xs mb-3">Batas poin sebelum siswa dikembalikan ke orang tua.</p>
                        
                        <div class="flex items-center gap-3">
                            <input type="number" value="100" class="w-32 border border-slate-300 rounded-xl px-4 py-2.5 text-sm font-bold text-slate-800 focus:border-blue-500 focus:ring-4 focus:ring-blue-500/10 outline-none transition text-center">
                            <span class="text-sm font-bold text-slate-600">Poin</span>
                        </div>
                    </div>

                    <div class="border-t border-slate-50"></div>

                    <div>
                        <label class="block text-sm font-bold text-slate-700 mb-1">Waktu Terlambat</label>
                        <p class="text-slate-500 text-xs mb-3">Siswa dianggap terlambat jika scan setelah waktu ini.</p>
                        
                        <div class="flex items-center gap-4">
                            <div class="relative w-32">
                                <input type="time" value="07:01" class="w-full border border-slate-300 rounded-xl px-4 py-2.5 text-sm font-bold text-red-600 focus:border-blue-500 focus:ring-4 focus:ring-blue-500/10 outline-none transition text-center appearance-none">
                                <i class="fa-regular fa-clock absolute right-3 top-3 text-slate-400 text-xs pointer-events-none"></i>
                            </div>
                            <span class="text-xs font-bold text-slate-400">(Otomatis dapat Poin Keterlambatan)</span>
                        </div>
                    </div>

                    <div class="pt-8 flex justify-end">
                        <button type="button" class="bg-[#2563eb] hover:bg-blue-700 text-white py-3 px-6 rounded-xl text-sm font-bold shadow-lg shadow-blue-200 transition">
                            Simpan Pengaturan
                        </button>
                    </div>

                </form>

            </div>

        </div> </div>

</x-layout-app>