<x-layout-app title="Monitoring Prestasi" role="kepsek">
    <header class="h-16 flex-none px-8 flex items-center justify-between bg-white border-b border-slate-200 sticky top-0 z-20">
        <h2 class="text-lg font-bold text-slate-800 tracking-tight">Monitoring Prestasi</h2>
        <div class="flex items-center gap-2">
            <div class="w-7 h-7 rounded-full bg-indigo-600 flex items-center justify-center text-white font-bold text-[10px]">KS</div>
            <span class="text-xs font-bold text-slate-600">Kepala Sekolah</span>
        </div>
    </header>

    <div class="flex-1 overflow-y-auto p-6 lg:p-8 custom-scroll bg-[#f8fafc]">
        <div class="w-full space-y-6">
            <div class="flex justify-between items-end mb-4">
                <div>
                    <h2 class="text-2xl font-bold text-slate-800">Galeri Prestasi Siswa</h2>
                    <p class="text-slate-500 text-sm mt-1">Monitoring pencapaian akademik dan non-akademik.</p>
                </div>
                <select class="border border-slate-200 p-2.5 rounded-xl text-sm font-bold text-slate-600 bg-white outline-none cursor-pointer shadow-sm">
                    <option>Semua Kategori</option>
                    <option>Akademik</option>
                    <option>Non-Akademik</option>
                </select>
            </div>

            <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-8">
                <div class="group bg-white rounded-[20px] border border-slate-200 overflow-hidden hover:shadow-xl transition-all duration-300 cursor-pointer" onclick="toggleModal('modalDetailPrestasi')">
                    <div class="h-48 overflow-hidden relative bg-slate-100">
                        <img src="https://images.unsplash.com/photo-1571260899304-425eee4c7efc?w=500&q=80" class="w-full h-full object-cover group-hover:scale-105 transition duration-700">
                        <div class="absolute inset-0 bg-gradient-to-t from-black/70 to-transparent"></div>
                        <div class="absolute bottom-4 left-4 text-white">
                            <span class="bg-yellow-500 text-black text-[10px] font-bold px-2 py-0.5 rounded flex items-center w-fit mb-1 uppercase tracking-wide">Nasional</span>
                            <div class="text-xs font-bold opacity-90">Kategori: Akademik</div>
                        </div>
                    </div>
                    <div class="p-6">
                        <h3 class="font-bold text-lg text-slate-800 mb-2 leading-tight group-hover:text-[#2563eb] transition">Juara 1 Robotik Nasional</h3>
                        <p class="text-sm text-slate-600 font-medium mb-4 flex items-center gap-2">
                            <i class="fa-solid fa-user-graduate text-slate-400"></i> Andi Saputra (7A)
                        </p>
                        <div class="flex justify-between items-center border-t border-slate-100 pt-4">
                            <span class="text-xs text-slate-400 font-mono"><i class="fa-regular fa-calendar mr-1"></i> 12 Jan 2026</span>
                            <span class="text-[#2563eb] text-xs font-bold flex items-center gap-1 group-hover:translate-x-1 transition">Detail <i class="fa-solid fa-arrow-right"></i></span>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <div id="modalDetailPrestasi" class="fixed inset-0 bg-navy-900/60 hidden items-center justify-center z-50 backdrop-blur-sm transition-opacity">
        <div class="bg-white rounded-3xl w-full max-w-4xl h-[500px] shadow-2xl flex overflow-hidden transform scale-100 transition-all relative">
            <button onclick="toggleModal('modalDetailPrestasi')" class="absolute top-4 right-4 z-10 w-8 h-8 rounded-full bg-black/50 text-white flex items-center justify-center hover:bg-black/70 transition">
                <i class="fa-solid fa-xmark"></i>
            </button>
            <div class="w-1/2 bg-slate-100 relative hidden md:block">
                <img src="https://images.unsplash.com/photo-1571260899304-425eee4c7efc?w=500&q=80" class="w-full h-full object-cover">
            </div>
            <div class="w-full md:w-1/2 p-8 overflow-y-auto flex flex-col">
                <div class="mb-6">
                    <span class="bg-blue-100 text-blue-700 text-xs font-bold px-3 py-1 rounded-full uppercase tracking-wide mb-3 inline-block">Akademik</span>
                    <h2 class="text-2xl font-extrabold text-slate-900 leading-tight mb-2">Juara 1 Robotik Nasional</h2>
                    <p class="text-slate-500 font-medium flex items-center gap-2 text-sm"><i class="fa-regular fa-calendar text-blue-500"></i> 12 Januari 2026</p>
                </div>
                <div class="space-y-5 flex-1">
                    <div class="bg-slate-50 p-4 rounded-xl border border-slate-200">
                        <p class="text-[10px] text-slate-400 font-bold uppercase mb-2">Siswa Berprestasi</p>
                        <div class="flex items-center gap-3">
                            <div class="w-10 h-10 rounded-full bg-[#2563eb] text-white flex items-center justify-center font-bold shadow-md">AS</div>
                            <div>
                                <p class="font-bold text-slate-800 text-sm">Andi Saputra</p>
                                <p class="text-xs text-slate-500">Kelas 7A</p>
                            </div>
                        </div>
                    </div>
                    <div class="grid grid-cols-2 gap-4">
                        <div><p class="text-[10px] text-slate-400 font-bold uppercase">Tingkat</p><p class="text-slate-800 font-bold text-sm">Nasional</p></div>
                        <div><p class="text-[10px] text-slate-400 font-bold uppercase">Penyelenggara</p><p class="text-slate-800 font-bold text-sm">Kemenristekdikti</p></div>
                    </div>
                    <div>
                        <p class="text-[10px] text-slate-400 font-bold uppercase mb-1">Deskripsi Kegiatan</p>
                        <p class="text-slate-600 text-sm leading-relaxed">Siswa berhasil memenangkan kompetisi robotik dengan desain alat pemilah sampah otomatis tingkat nasional.</p>
                    </div>
                </div>
            </div>
        </div>
    </div>

    @push('scripts')
    <script>
        function toggleModal(id) {
            const el = document.getElementById(id);
            el.classList.toggle('hidden'); el.classList.toggle('flex');
        }
    </script>
    @endpush
</x-layout-app>