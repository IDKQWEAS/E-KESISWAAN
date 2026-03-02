<x-layout-app title="Pemantauan Siswa" role="bk">
    <header class="h-20 flex-none px-8 flex items-center justify-between bg-white border-b border-slate-200 sticky top-0 z-20">
        <h2 class="text-xl font-bold text-slate-800 tracking-tight">Pemantauan Siswa</h2>
    </header>

    <div class="flex-1 overflow-y-auto p-8 custom-scroll bg-surface">
        
        <div class="bg-white p-6 rounded-3xl shadow-card border border-slate-200 flex justify-between items-center mb-6">
            <div><h3 class="font-bold text-lg text-slate-800">Daftar Siswa Dalam Pantauan</h3></div>
            <div class="relative w-64">
                <i class="fa-solid fa-search absolute left-4 top-3 text-slate-400 text-xs"></i>
                <input type="text" placeholder="Cari Siswa..." class="pl-10 pr-4 py-2.5 bg-slate-50 border-none rounded-xl text-xs w-full focus:ring-2 focus:ring-primary/50 outline-none transition font-medium">
            </div>
        </div>
        
        <div class="space-y-4">
            <div class="bg-white p-5 rounded-2xl border border-slate-200 shadow-sm hover:shadow-md transition cursor-pointer group flex items-center justify-between" onclick="toggleModal('modalDetailSiswa')">
                <div class="flex items-center gap-4">
                    <div class="w-1.5 h-12 bg-red-500 rounded-full"></div>
                    <div>
                        <h4 class="font-bold text-slate-800 text-lg group-hover:text-red-600 transition">Doni Tata <span class="ml-2 bg-slate-100 text-slate-500 text-[10px] px-2 py-0.5 rounded font-bold align-middle">9A</span></h4>
                        <p class="text-xs text-slate-500 line-clamp-1">Pelanggaran berat: Berkelahi di kantin, Merokok di area sekolah.</p>
                    </div>
                </div>
                <div class="flex items-center gap-4">
                    <span class="bg-red-100 text-red-600 font-bold px-3 py-1 rounded-lg text-xs border border-red-200">100 Poin</span>
                    <button class="w-8 h-8 rounded-full bg-slate-50 text-slate-400 hover:bg-slate-100 flex items-center justify-center"><i class="fa-solid fa-chevron-right"></i></button>
                </div>
            </div>
        </div>

        <div class="bg-white p-4 rounded-2xl border border-slate-200 shadow-sm flex justify-between items-center text-xs text-slate-500 mt-6">
            <span class="font-medium">Menampilkan 1–5 dari 15 data</span>
            <div class="flex gap-1">
                <button class="px-3 py-1.5 bg-white border border-slate-200 rounded-lg hover:bg-slate-50 text-slate-600 transition disabled:opacity-50">Prev</button>
                <button class="px-3 py-1.5 bg-primary text-white rounded-lg shadow-md font-bold">1</button>
                <button class="px-3 py-1.5 bg-white border border-slate-200 rounded-lg hover:bg-slate-50 text-slate-600 transition">2</button>
                <button class="px-3 py-1.5 bg-white border border-slate-200 rounded-lg hover:bg-slate-50 text-slate-600 transition">Next</button>
            </div>
        </div>

    </div>

    <div id="modalDetailSiswa" class="fixed inset-0 bg-navy-900/40 hidden items-center justify-center z-50 backdrop-blur-sm transition-opacity">
        <div class="bg-white rounded-3xl w-full max-w-5xl shadow-2xl p-0 overflow-hidden">
            <div class="p-6 border-b border-slate-100 flex justify-between items-center bg-slate-50">
                <div class="flex items-center gap-4">
                    <div class="w-16 h-16 rounded-full bg-blue-600 text-white flex items-center justify-center text-2xl font-bold shadow-lg">DT</div>
                    <div>
                        <h2 class="text-2xl font-bold text-slate-800">Doni Tata</h2>
                        <div class="flex gap-2 mt-1">
                            <span class="bg-slate-200 text-slate-600 px-2 py-0.5 rounded text-xs font-bold">Kelas 9A</span>
                            <span class="bg-red-100 text-red-600 px-2 py-0.5 rounded text-xs font-bold">Total Poin: 100</span>
                        </div>
                    </div>
                </div>
                <button onclick="toggleModal('modalDetailSiswa')" class="text-slate-400 hover:text-slate-600"><i class="fa-solid fa-xmark text-2xl"></i></button>
            </div>
            <div class="p-8 grid grid-cols-1 md:grid-cols-2 gap-8">
                <div class="space-y-6">
                    <div class="bg-white border border-slate-200 rounded-2xl p-6 shadow-sm">
                        <h4 class="font-bold text-slate-800 mb-4 text-sm uppercase tracking-wide">Data Orang Tua / Wali</h4>
                        <div class="space-y-3 text-sm text-slate-600">
                            <div class="flex"><span class="w-24 font-bold shrink-0">Ayah:</span><span>Bpk. Agus Salim (TNI)</span></div>
                            <div class="flex"><span class="w-24 font-bold shrink-0">Kontak:</span><span class="bg-green-100 text-green-700 px-2 rounded text-xs font-mono font-bold">0812345678</span></div>
                        </div>
                    </div>
                </div>
                <div>
                    <h4 class="font-bold text-red-600 mb-3 flex items-center gap-2"><i class="fa-solid fa-triangle-exclamation"></i> Riwayat Pelanggaran</h4>
                    <div class="bg-red-50 border border-red-100 rounded-2xl overflow-hidden">
                        <table class="w-full text-left text-xs">
                            <thead class="bg-red-100 text-red-800"><tr><th class="p-3">Tanggal</th><th class="p-3">Kasus</th><th class="p-3 text-right">Poin</th></tr></thead>
                            <tbody class="divide-y divide-red-100 text-slate-600">
                                <tr><td class="p-3">2026-01-10</td><td class="p-3 font-bold">Merokok</td><td class="p-3 text-right font-bold text-red-600">+25</td></tr>
                            </tbody>
                        </table>
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