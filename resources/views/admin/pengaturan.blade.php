<x-layout-app title="Pengaturan Sistem" role="admin">
 

    <div class="flex-1 overflow-y-auto p-8 custom-scroll bg-[#f8fafc]">
        <div class="w-full flex justify-center">
            <div class="bg-white rounded-[24px] border border-slate-200 shadow-sm p-8 w-full max-w-3xl">

                <div class="flex items-center gap-3 mb-8">
                    <div class="w-10 h-10 bg-blue-50 rounded-xl flex items-center justify-center text-blue-600">
                        <i class="fa-solid fa-sliders text-lg"></i>
                    </div>
                    <div>
                        <h1 class="text-xl font-bold text-slate-800">Konfigurasi Operasional</h1>
                        <p class="text-xs text-slate-500">Atur batasan poin dan jam kehadiran siswa</p>
                    </div>
                </div>

                @if(session('success'))
                    <div class="mb-6 bg-emerald-50 border border-emerald-100 text-emerald-600 px-4 py-3 rounded-xl text-sm font-bold flex items-center gap-3">
                        <i class="fa-solid fa-circle-check"></i> {{ session('success') }}
                    </div>
                @endif

                <form action="{{ route('admin.pengaturan.update') }}" method="POST" class="space-y-6">
                    @csrf
                    
                    {{-- POIN --}}
                    <div class="space-y-2">
                        <label class="text-[11px] font-black text-slate-400 uppercase tracking-wider ml-1">Poin Pelanggaran Awal</label>
                        <input type="number" name="poin_awal" value="{{ $pengaturan['maks_poin_pelanggaran'] ?? 0 }}"
                            class="w-full bg-slate-50 border border-slate-200 rounded-xl px-4 py-3 text-sm font-bold outline-none focus:border-blue-500 focus:ring-4 focus:ring-blue-500/10 transition-all">
                    </div>

                    {{-- JAM MASUK --}}
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-5">
                        <div class="space-y-2">
                            <label class="text-[11px] font-black text-slate-400 uppercase tracking-wider ml-1">Jam Masuk</label>
                            <input type="time" name="jam_masuk" value="{{ isset($pengaturan['jam_masuk']) ? substr($pengaturan['jam_masuk'], 0, 5) : '07:00' }}"
                                class="w-full bg-slate-50 border border-slate-200 rounded-xl px-4 py-3 text-sm font-bold outline-none focus:border-blue-500 transition-all">
                        </div>
                        <div class="space-y-2">
                            <label class="text-[11px] font-black text-red-400 uppercase tracking-wider ml-1">Batas Jam Terlambat</label>
                            <input type="time" name="waktu_terlambat" value="{{ isset($pengaturan['jam_terlambat']) ? substr($pengaturan['jam_terlambat'], 0, 5) : '07:30' }}"
                                class="w-full bg-red-50/30 border border-red-100 rounded-xl px-4 py-3 text-sm font-bold text-red-600 outline-none focus:border-red-500 transition-all">
                        </div>
                    </div>

                    {{-- JAM PULANG --}}
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-5">
                        <div class="space-y-2">
                            <label class="text-[11px] font-black text-blue-400 uppercase tracking-wider ml-1">Jam Boleh Pulang</label>
                            <input type="time" name="jam_pulang_mulai" value="{{ isset($pengaturan['jam_boleh_pulang']) ? substr($pengaturan['jam_boleh_pulang'], 0, 5) : '14:00' }}"
                                class="w-full bg-blue-50/30 border border-blue-100 rounded-xl px-4 py-3 text-sm font-bold text-blue-600 outline-none focus:border-blue-500 transition-all">
                        </div>
                        <div class="space-y-2">
                            <label class="text-[11px] font-black text-slate-400 uppercase tracking-wider ml-1">Batas Akhir Pulang</label>
                            <input type="time" name="jam_pulang_akhir" value="{{ isset($pengaturan['batas_akhir_pulang']) ? substr($pengaturan['batas_akhir_pulang'], 0, 5) : '15:30' }}"
                                class="w-full bg-slate-50 border border-slate-200 rounded-xl px-4 py-3 text-sm font-bold outline-none focus:border-blue-500 transition-all">
                        </div>
                    </div>

                    <div class="pt-6 border-t border-slate-100 flex justify-end">
                        <button type="submit" class="bg-blue-600 hover:bg-blue-700 text-white py-3.5 px-10 rounded-xl text-xs font-black shadow-lg shadow-blue-100 transition-all active:scale-95 uppercase tracking-widest">
                            <i class="fa-solid fa-floppy-disk mr-2"></i> Simpan Perubahan
                        </button>
                    </div>
                </form>

            </div>
        </div>
    </div>
</x-layout-app> 