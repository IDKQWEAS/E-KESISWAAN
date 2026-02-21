<x-layout-app title="Input Pelanggaran" role="bk">
    
    <header class="h-16 flex-none px-8 flex items-center justify-between bg-white border-b border-slate-200 sticky top-0 z-20">
        <h2 class="text-lg font-bold text-slate-800 tracking-tight">Input Pelanggaran</h2>
        <div class="flex items-center gap-2">
            <div class="w-7 h-7 rounded-full bg-blue-600 flex items-center justify-center text-white font-bold text-[10px]">BK</div>
            <span class="text-xs font-bold text-slate-600">Guru BK</span>
        </div>
    </header>

    <div class="flex-1 overflow-y-auto p-8 custom-scroll bg-[#f8fafc]">
        
        <div class="w-full space-y-6">

            <div class="flex gap-6 mb-6 border-b border-slate-200">
                <button onclick="switchInputTab('data')" id="tab-btn-data" class="pb-3 text-sm font-bold border-b-2 border-[#2563eb] text-[#2563eb] transition-all">
                    Data Pelanggaran
                </button>
                <button onclick="switchInputTab('panduan')" id="tab-btn-panduan" class="pb-3 text-sm font-bold border-b-2 border-transparent text-slate-500 hover:text-slate-800 transition-all">
                    Panduan Poin
                </button>
            </div>

            <div id="view-pelanggaran-data" class="space-y-6">
                
                <div class="bg-white p-6 rounded-[20px] shadow-sm border border-slate-200 flex flex-col md:flex-row justify-between items-center gap-4">
                    <div>
                        <h3 class="font-bold text-xl text-slate-800 flex items-center gap-3">
                            <i class="fa-solid fa-gavel text-red-500"></i> Manajemen Pelanggaran
                        </h3>
                        <p class="text-sm text-slate-500 mt-1">Kelola dan input data pelanggaran siswa terbaru.</p>
                    </div>
                    <button onclick="toggleModal('modalInputPelanggaran')" class="bg-[#2563eb] hover:bg-blue-700 text-white py-2.5 px-6 rounded-xl text-sm font-bold shadow-md shadow-blue-200 transition flex items-center gap-2">
                        <i class="fa-solid fa-plus text-xs"></i> Tambah Pelanggaran
                    </button>
                </div>

                <div class="bg-white rounded-[20px] border border-slate-200 shadow-sm overflow-hidden">
                    
                    <div class="p-6 border-b border-slate-100 bg-white">
                        <h4 class="font-bold text-slate-800 text-base">Riwayat Pelanggaran Hari Ini</h4>
                    </div>

                    <div class="overflow-x-auto">
                        <table class="w-full text-left border-collapse">
                            <thead class="border-b border-slate-100 bg-white">
                                <tr>
                                    <th class="py-5 pl-8 pr-4 text-[11px] font-bold text-slate-400 uppercase tracking-widest">TANGGAL/WAKTU</th>
                                    <th class="py-5 px-4 text-[11px] font-bold text-slate-400 uppercase tracking-widest w-32">SISWA</th>
                                    <th class="py-5 px-4 text-[11px] font-bold text-slate-400 uppercase tracking-widest text-center">PELANGGARAN</th>
                                    <th class="py-5 px-4 text-[11px] font-bold text-slate-400 uppercase tracking-widest w-[35%]">KRONOLOGI</th>
                                    <th class="py-5 px-4 text-[11px] font-bold text-slate-400 uppercase tracking-widest text-center">POIN</th>
                                    <th class="py-5 pr-8 pl-4 text-[11px] font-bold text-slate-400 uppercase tracking-widest text-center">AKSI</th>
                                </tr>
                            </thead>
                            <tbody class="divide-y divide-slate-50">
                                
                                <tr class="hover:bg-slate-50 transition group">
                                    <td class="py-6 pl-8 pr-4 align-top">
                                        <div class="flex flex-col gap-1">
                                            <span class="text-sm font-mono text-slate-500">10 Jan 2026</span>
                                            <span class="text-xs font-mono text-slate-400">09:30 WIB</span>
                                        </div>
                                    </td>
                                    
                                    <td class="py-6 px-4 align-top">
                                        <span class="text-base font-bold text-slate-800 block pr-4">Doni Tata (9A)</span>
                                    </td>
                                    
                                    <td class="py-6 px-4 align-top text-center">
                                        <span class="bg-red-50 text-[#ef4444] border border-red-100 px-4 py-1.5 rounded-lg text-[13px] font-bold inline-block">
                                            Merokok
                                        </span>
                                    </td>
                                    
                                    <td class="py-6 px-4 align-top">
                                        <p class="text-sm text-slate-500 italic leading-relaxed pr-8">
                                            Siswa ditemukan merokok di belakang kanti...
                                        </p>
                                    </td>
                                    
                                    <td class="py-6 px-4 align-top text-center">
                                        <span class="text-base font-bold text-[#ef4444]">+25</span>
                                    </td>
                                    
                                    <td class="py-6 pr-8 pl-4 align-top">
                                        <div class="flex flex-col items-center gap-4">
                                            <button onclick="toggleModal('modalEditPelanggaran')" class="text-slate-400 hover:text-[#2563eb] transition text-lg" title="Edit">
                                                <i class="fa-regular fa-pen-to-square"></i>
                                            </button>
                                            <button onclick="confirmDelete()" class="text-slate-400 hover:text-[#ef4444] transition text-lg" title="Hapus">
                                                <i class="fa-regular fa-trash-can"></i>
                                            </button>
                                        </div>
                                    </td>
                                </tr>

                            </tbody>
                        </table>
                    </div>

                    <div class="flex flex-col md:flex-row justify-between items-center p-6 border-t border-slate-100 bg-white">
                        <p class="text-slate-500 text-sm mb-4 md:mb-0">
                            Menampilkan 1–10 dari 50 data
                        </p>
                        
                        <div class="flex items-center gap-2">
                            <button class="px-4 py-2 border border-slate-200 rounded-xl text-slate-600 text-sm hover:bg-slate-50 transition">
                                Prev
                            </button>
                            <button class="w-10 h-10 bg-[#2563eb] text-white rounded-xl text-sm font-bold shadow-md shadow-blue-200 transition">
                                1
                            </button>
                            <button class="w-10 h-10 bg-white border border-slate-200 text-slate-600 rounded-xl text-sm hover:bg-slate-50 transition">
                                2
                            </button>
                            <button class="px-4 py-2 border border-slate-200 rounded-xl text-slate-600 text-sm hover:bg-slate-50 transition">
                                Next
                            </button>
                        </div>
                    </div>

                </div>
            </div>

            <div id="view-pelanggaran-panduan" class="hidden">
                <div class="bg-blue-50 border border-blue-100 rounded-[20px] p-8 h-fit">
                    <div class="flex justify-between items-center mb-6">
                        <h3 class="font-bold text-blue-800 text-lg"><i class="fa-solid fa-book-open mr-2"></i>Panduan Poin Pelanggaran</h3>
                        <button onclick="toggleModalRule('add')" class="bg-blue-200 text-blue-800 px-4 py-2.5 rounded-xl hover:bg-blue-300 font-bold text-sm transition">
                            <i class="fa-solid fa-plus mr-1"></i> Tambah Aturan
                        </button>
                    </div>
                    <ul class="text-sm space-y-4 text-blue-900">
                        <li class="flex justify-between items-center border-b border-blue-200 pb-4 group">
                            <span class="font-medium text-base">Terlambat Masuk Sekolah <b class="ml-3 px-3 py-1 bg-white rounded-lg text-blue-700">5 Poin</b></span>
                            <div class="hidden group-hover:flex gap-3">
                                <button class="text-blue-600 hover:text-blue-800 transition p-2" onclick="toggleModalRule('edit', 'Terlambat', 5)">
                                    <i class="fa-regular fa-pen-to-square text-lg"></i>
                                </button>
                                <button class="text-red-400 hover:text-red-600 transition p-2" onclick="confirmDelete()">
                                    <i class="fa-regular fa-trash-can text-lg"></i>
                                </button>
                            </div>
                        </li>
                        <li class="flex justify-between items-center border-b border-blue-200 pb-4 group">
                            <span class="font-medium text-base">Bolos Saat Jam Pelajaran <b class="ml-3 px-3 py-1 bg-white rounded-lg text-blue-700">10 Poin</b></span>
                            <div class="hidden group-hover:flex gap-3">
                                <button class="text-blue-600 hover:text-blue-800 transition p-2" onclick="toggleModalRule('edit', 'Bolos', 10)">
                                    <i class="fa-regular fa-pen-to-square text-lg"></i>
                                </button>
                                <button class="text-red-400 hover:text-red-600 transition p-2" onclick="confirmDelete()">
                                    <i class="fa-regular fa-trash-can text-lg"></i>
                                </button>
                            </div>
                        </li>
                    </ul>
                    <div class="mt-8 pt-6 border-t border-blue-200 text-center">
                        <p class="text-sm font-bold text-red-600 uppercase tracking-widest">
                            Catatan: 100 Poin = Siswa Dikembalikan ke Orang Tua
                        </p>
                    </div>
                </div>
            </div>

        </div> </div>

    <div id="modalInputPelanggaran" class="fixed inset-0 bg-navy-900/50 hidden items-center justify-center z-50 backdrop-blur-sm transition-opacity duration-300">
        <div class="bg-white rounded-[20px] w-full max-w-lg shadow-2xl transform scale-100 transition-all duration-300">
            <div class="flex justify-between items-center p-6 border-b border-slate-100">
                <h3 class="font-bold text-slate-800 text-xl">Input Pelanggaran Baru</h3>
                <button onclick="toggleModal('modalInputPelanggaran')" class="text-slate-400 hover:text-red-500 transition">
                    <i class="fa-solid fa-xmark text-xl"></i>
                </button>
            </div>
            <div class="p-6 space-y-5">
                <div class="grid grid-cols-2 gap-4">
                    <div>
                        <label class="block text-xs font-bold text-slate-700 uppercase mb-2">Kelas</label>
                        <select class="w-full border border-slate-300 rounded-xl px-4 py-3 text-sm focus:ring-2 focus:ring-[#2563eb]/50 outline-none bg-white">
                            <option>-- Pilih --</option>
                            <option>9A</option>
                        </select>
                    </div>
                    <div>
                        <label class="block text-xs font-bold text-slate-700 uppercase mb-2">Siswa</label>
                        <select class="w-full border border-slate-300 rounded-xl px-4 py-3 text-sm focus:ring-2 focus:ring-[#2563eb]/50 outline-none bg-white">
                            <option>-- Pilih --</option>
                            <option>Doni Tata</option>
                        </select>
                    </div>
                </div>
                <div class="grid grid-cols-2 gap-4">
                    <div>
                        <label class="block text-xs font-bold text-slate-700 uppercase mb-2">Tanggal</label>
                        <input type="date" class="w-full border border-slate-300 rounded-xl px-4 py-3 text-sm focus:ring-2 focus:ring-[#2563eb]/50 outline-none">
                    </div>
                    <div>
                        <label class="block text-xs font-bold text-slate-700 uppercase mb-2">Waktu</label>
                        <input type="time" class="w-full border border-slate-300 rounded-xl px-4 py-3 text-sm focus:ring-2 focus:ring-[#2563eb]/50 outline-none">
                    </div>
                </div>
                <div>
                    <label class="block text-xs font-bold text-slate-700 uppercase mb-2">Jenis Pelanggaran</label>
                    <select class="w-full border border-slate-300 rounded-xl px-4 py-3 text-sm focus:ring-2 focus:ring-[#2563eb]/50 outline-none bg-white">
                        <option>Merokok (+25)</option>
                        <option>Bolos (+10)</option>
                    </select>
                </div>
                <div>
                    <label class="block text-xs font-bold text-slate-700 uppercase mb-2">Kronologi</label>
                    <textarea rows="3" class="w-full border border-slate-300 rounded-xl px-4 py-3 text-sm focus:ring-2 focus:ring-[#2563eb]/50 outline-none" placeholder="Tuliskan detail kejadian..."></textarea>
                </div>
            </div>
            <div class="p-6 border-t border-slate-100 flex justify-end gap-3 bg-slate-50/50 rounded-b-[20px]">
                <button onclick="toggleModal('modalInputPelanggaran')" class="px-6 py-2.5 border border-slate-300 bg-white text-slate-700 rounded-xl text-sm font-bold hover:bg-slate-50 transition">Batal</button>
                <button onclick="Swal.fire('Sukses', 'Pelanggaran tercatat', 'success'); toggleModal('modalInputPelanggaran');" class="px-6 py-2.5 bg-[#ef4444] text-white rounded-xl text-sm font-bold hover:bg-red-600 shadow-lg transition">Simpan Data</button>
            </div>
        </div>
    </div>

    <div id="modalEditPelanggaran" class="fixed inset-0 bg-navy-900/50 hidden items-center justify-center z-50 backdrop-blur-sm">
        <div class="bg-white rounded-[20px] p-8 w-full max-w-lg shadow-2xl">
            <div class="flex justify-between items-center mb-6 pb-4 border-b border-slate-100">
                <h3 class="font-bold text-xl text-slate-800">Edit Pelanggaran</h3>
                <button onclick="toggleModal('modalEditPelanggaran')" class="text-slate-400 hover:text-red-500 transition"><i class="fa-solid fa-xmark text-xl"></i></button>
            </div>
            <div class="space-y-5">
                <div class="grid grid-cols-2 gap-4">
                    <div><label class="block text-xs font-bold text-slate-700 uppercase mb-2">Kelas</label><select class="w-full border border-slate-300 rounded-xl px-4 py-3 text-sm bg-slate-50 outline-none" disabled><option>9A</option></select></div>
                    <div><label class="block text-xs font-bold text-slate-700 uppercase mb-2">Siswa</label><select class="w-full border border-slate-300 rounded-xl px-4 py-3 text-sm bg-slate-50 outline-none" disabled><option>Doni Tata</option></select></div>
                </div>
                <div class="grid grid-cols-2 gap-4">
                    <div><label class="block text-xs font-bold text-slate-700 uppercase mb-2">Tanggal Kejadian</label><input type="date" value="2026-01-10" class="w-full border border-slate-300 rounded-xl px-4 py-3 text-sm focus:ring-2 focus:ring-[#2563eb]/50 outline-none"></div>
                    <div><label class="block text-xs font-bold text-slate-700 uppercase mb-2">Waktu Kejadian</label><input type="time" value="09:30" class="w-full border border-slate-300 rounded-xl px-4 py-3 text-sm focus:ring-2 focus:ring-[#2563eb]/50 outline-none"></div>
                </div>
                <div><label class="block text-xs font-bold text-slate-700 uppercase mb-2">Jenis Pelanggaran</label><select class="w-full border border-slate-300 rounded-xl px-4 py-3 text-sm focus:ring-2 focus:ring-[#2563eb]/50 outline-none bg-white"><option selected>Merokok (+25)</option><option>Bolos (+10)</option></select></div>
                <div><label class="block text-xs font-bold text-slate-700 uppercase mb-2">Kronologi</label><textarea rows="3" class="w-full border border-slate-300 rounded-xl px-4 py-3 text-sm focus:ring-2 focus:ring-[#2563eb]/50 outline-none">Siswa ditemukan merokok di belakang kantin sekolah pada jam istirahat pertama.</textarea></div>
            </div>
            <div class="flex gap-3 mt-8 pt-4 justify-end">
                <button onclick="toggleModal('modalEditPelanggaran')" class="px-6 py-2.5 border border-slate-300 rounded-xl font-bold text-sm text-slate-600 hover:bg-slate-50 transition">Batal</button>
                <button onclick="toggleModal('modalEditPelanggaran'); Swal.fire('Updated', 'Data diperbarui', 'success');" class="px-8 py-2.5 bg-[#2563eb] text-white rounded-xl font-bold text-sm hover:bg-blue-700 shadow-lg transition">Update Data</button>
            </div>
        </div>
    </div>

    @push('scripts')
    <script>
        function toggleModal(id) {
            const el = document.getElementById(id);
            if (el.classList.contains('hidden')) {
                el.classList.remove('hidden'); el.classList.add('flex');
            } else {
                el.classList.add('hidden'); el.classList.remove('flex');
            }
        }
        
        function confirmDelete() {
            Swal.fire({
                title: "Hapus Data Ini?",
                text: "Tindakan ini tidak dapat dibatalkan!",
                icon: "warning",
                showCancelButton: true,
                confirmButtonColor: "#ef4444",
                cancelButtonColor: "#64748b",
                confirmButtonText: "Ya, Hapus",
                cancelButtonText: "Batal"
            }).then((result) => {
                if (result.isConfirmed) {
                    Swal.fire('Terhapus!', 'Data berhasil dihapus.', 'success');
                }
            });
        }

        function switchInputTab(tab) {
            if (tab === "data") {
                document.getElementById("view-pelanggaran-data").classList.remove("hidden");
                document.getElementById("view-pelanggaran-panduan").classList.add("hidden");
                
                document.getElementById("tab-btn-data").classList.add("border-[#2563eb]", "text-[#2563eb]");
                document.getElementById("tab-btn-data").classList.remove("border-transparent", "text-slate-500");
                
                document.getElementById("tab-btn-panduan").classList.add("border-transparent", "text-slate-500");
                document.getElementById("tab-btn-panduan").classList.remove("border-[#2563eb]", "text-[#2563eb]");
            } else {
                document.getElementById("view-pelanggaran-data").classList.add("hidden");
                document.getElementById("view-pelanggaran-panduan").classList.remove("hidden");
                
                document.getElementById("tab-btn-panduan").classList.add("border-[#2563eb]", "text-[#2563eb]");
                document.getElementById("tab-btn-panduan").classList.remove("border-transparent", "text-slate-500");
                
                document.getElementById("tab-btn-data").classList.add("border-transparent", "text-slate-500");
                document.getElementById("tab-btn-data").classList.remove("border-[#2563eb]", "text-[#2563eb]");
            }
        }
    </script>
    @endpush
</x-layout-app>