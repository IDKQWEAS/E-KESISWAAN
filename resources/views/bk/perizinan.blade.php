<x-layout-app title="Approval Perizinan" role="bk">
    
    <header class="h-16 flex-none px-8 flex items-center justify-between bg-white border-b border-slate-200 sticky top-0 z-20">
        <h2 class="text-lg font-bold text-slate-800 tracking-tight">Perizinan Siswa</h2>
        <div class="flex items-center gap-2">
            <div class="w-7 h-7 rounded-full bg-blue-600 flex items-center justify-center text-white font-bold text-[10px]">BK</div>
            <span class="text-xs font-bold text-slate-600">Guru BK</span>
        </div>
    </header>

    <div class="flex-1 overflow-y-auto p-6 lg:p-8 custom-scroll bg-[#f8fafc]">
        
        <div class="w-full space-y-6">

            <div class="bg-white p-6 lg:p-8 rounded-[24px] shadow-sm border border-slate-200">
                
                <div class="flex flex-col xl:flex-row justify-between items-start xl:items-center gap-6 mb-8">
                    <div>
                        <h3 class="font-bold text-xl text-slate-800">Pusat Approval Izin</h3>
                        <p class="text-sm text-slate-500 mt-1">Verifikasi izin keluar/pulang siswa.</p>
                    </div>

                    <div class="flex flex-wrap items-center gap-3 w-full xl:w-auto">
                        <div class="relative group flex-1 sm:flex-none">
                            <select class="w-full appearance-none bg-white border border-slate-200 hover:border-slate-300 rounded-xl py-2.5 pl-4 pr-10 text-sm font-medium text-slate-600 outline-none focus:ring-2 focus:ring-[#2563eb]/20 cursor-pointer transition-all min-w-[150px]">
                                <option>Semua Kelas</option>
                                <option>7A</option>
                                <option>8A</option>
                                <option>9A</option>
                            </select>
                            <i class="fa-solid fa-chevron-down absolute right-4 top-3.5 text-[10px] text-slate-400 pointer-events-none"></i>
                        </div>

                        <div class="flex-1 sm:flex-none">
                            <input type="date" class="w-full bg-white border border-slate-200 hover:border-slate-300 rounded-xl py-2.5 px-4 text-sm font-medium text-slate-600 outline-none focus:ring-2 focus:ring-[#2563eb]/20 cursor-pointer transition-all" />
                        </div>

                        <button onclick="toggleModal('modalAddIzin')" class="bg-[#2563eb] hover:bg-blue-700 text-white px-5 py-2.5 rounded-xl text-sm font-bold shadow-md shadow-blue-200 transition flex items-center justify-center gap-2 flex-shrink-0 w-full sm:w-auto">
                            <i class="fa-solid fa-plus text-[12px]"></i> Buat Izin Baru
                        </button>
                    </div>
                </div>

                <div class="border border-slate-200 rounded-[20px] overflow-hidden">
                    <div class="overflow-x-auto custom-scroll">
                        <table class="w-full text-left text-sm whitespace-nowrap min-w-max">
                            <thead class="bg-white border-b border-slate-100">
                                <tr>
                                    <th class="py-5 pl-6 pr-4 text-[10px] font-bold text-slate-400 uppercase tracking-widest">Tanggal</th>
                                    <th class="py-5 px-4 text-[10px] font-bold text-slate-400 uppercase tracking-widest">Siswa</th>
                                    <th class="py-5 px-4 text-[10px] font-bold text-slate-400 uppercase tracking-widest">Alasan</th>
                                    <th class="py-5 px-4 text-[10px] font-bold text-slate-400 uppercase tracking-widest">Bukti</th>
                                    <th class="py-5 px-4 text-[10px] font-bold text-slate-400 uppercase tracking-widest">Status</th>
                                    <th class="py-5 pr-6 pl-4 text-[10px] font-bold text-slate-400 uppercase tracking-widest text-right">Aksi</th>
                                </tr>
                            </thead>
                            <tbody class="divide-y divide-slate-50">
                                
                                <tr class="hover:bg-slate-50 transition group">
                                    <td class="py-5 pl-6 pr-4 text-[13px] text-slate-500 font-medium">
                                        12 Jan 2026
                                    </td>
                                    <td class="py-5 px-4 font-bold text-slate-800 text-[15px]">
                                        Doni Tata (9A)
                                    </td>
                                    <td class="py-5 px-4 text-[14px] text-slate-600">
                                        Sakit Demam
                                    </td>
                                    <td class="py-5 px-4">
                                        <a href="#" class="text-[#2563eb] text-[13px] font-bold underline hover:text-blue-800 flex items-center gap-1.5 transition">
                                            <i class="fa-solid fa-paperclip"></i> Lihat Surat
                                        </a>
                                    </td>
                                    <td class="py-5 px-4">
                                        <span class="bg-[#fee2e2] text-[#ef4444] px-4 py-1.5 rounded-full text-[10px] font-bold uppercase tracking-wider inline-block">
                                            Ditolak
                                        </span>
                                    </td>
                                    <td class="py-5 pr-6 pl-4">
                                        <div class="flex justify-end gap-2 text-base">
                                            <button onclick="toggleModal('modalEditIzin')" class="w-9 h-9 rounded-xl bg-slate-100 text-slate-400 hover:bg-slate-200 hover:text-[#2563eb] transition flex items-center justify-center" title="Edit">
                                                <i class="fa-regular fa-pen-to-square"></i>
                                            </button>
                                            <button onclick="confirmDelete()" class="w-9 h-9 rounded-xl bg-slate-100 text-slate-400 hover:bg-slate-200 hover:text-[#ef4444] transition flex items-center justify-center" title="Hapus">
                                                <i class="fa-regular fa-trash-can"></i>
                                            </button>
                                        </div>
                                    </td>
                                </tr>

                                <tr class="hover:bg-slate-50 transition group">
                                    <td class="py-5 pl-6 pr-4 text-[13px] text-slate-500 font-medium">
                                        10 Jan 2026
                                    </td>
                                    <td class="py-5 px-4 font-bold text-slate-800 text-[15px]">
                                        Budi Santoso (8B)
                                    </td>
                                    <td class="py-5 px-4 text-[14px] text-slate-600">
                                        Acara Keluarga
                                    </td>
                                    <td class="py-5 px-4">
                                        <a href="#" class="text-[#2563eb] text-[13px] font-bold underline hover:text-blue-800 flex items-center gap-1.5 transition">
                                            <i class="fa-solid fa-paperclip"></i> Lihat Surat
                                        </a>
                                    </td>
                                    <td class="py-5 px-4">
                                        <span class="bg-[#dcfce7] text-[#16a34a] px-4 py-1.5 rounded-full text-[10px] font-bold uppercase tracking-wider inline-block">
                                            Disetujui
                                        </span>
                                    </td>
                                    <td class="py-5 pr-6 pl-4">
                                        <div class="flex justify-end gap-2 text-base">
                                            <button onclick="toggleModal('modalEditIzin')" class="w-9 h-9 rounded-xl bg-slate-100 text-slate-400 hover:bg-slate-200 hover:text-[#2563eb] transition flex items-center justify-center" title="Edit">
                                                <i class="fa-regular fa-pen-to-square"></i>
                                            </button>
                                            <button onclick="confirmDelete()" class="w-9 h-9 rounded-xl bg-slate-100 text-slate-400 hover:bg-slate-200 hover:text-[#ef4444] transition flex items-center justify-center" title="Hapus">
                                                <i class="fa-regular fa-trash-can"></i>
                                            </button>
                                        </div>
                                    </td>
                                </tr>

                            </tbody>
                        </table>
                    </div>
                </div>

            </div>

        </div> </div>

    <div id="modalAddIzin" class="fixed inset-0 bg-navy-900/50 hidden items-center justify-center z-50 backdrop-blur-sm transition-opacity duration-300">
        <div class="bg-white rounded-[20px] w-full max-w-lg shadow-2xl p-6 lg:p-8 transform scale-100 transition-all">
            <div class="flex justify-between items-center mb-6 pb-4 border-b border-slate-100">
                <h3 class="font-bold text-xl text-slate-800">Formulir Izin Siswa</h3>
                <button onclick="toggleModal('modalAddIzin')" class="text-slate-400 hover:text-red-500 transition">
                    <i class="fa-solid fa-xmark text-xl"></i>
                </button>
            </div>
            
            <div class="space-y-5">
                <div>
                    <label class="block text-xs font-bold text-slate-500 uppercase tracking-wide mb-2">Tanggal Izin</label>
                    <input type="date" class="w-full border border-slate-200 rounded-xl px-4 py-3 text-sm focus:ring-2 focus:ring-[#2563eb]/50 outline-none transition text-slate-700">
                </div>
                <div class="grid grid-cols-2 gap-4">
                    <div>
                        <label class="block text-xs font-bold text-slate-500 uppercase tracking-wide mb-2">Kelas</label>
                        <select class="w-full border border-slate-200 rounded-xl px-4 py-3 text-sm focus:ring-2 focus:ring-[#2563eb]/50 outline-none transition bg-white">
                            <option>Pilih Kelas</option>
                            <option>7A</option>
                            <option>9A</option>
                        </select>
                    </div>
                    <div>
                        <label class="block text-xs font-bold text-slate-500 uppercase tracking-wide mb-2">Siswa</label>
                        <select class="w-full border border-slate-200 rounded-xl px-4 py-3 text-sm focus:ring-2 focus:ring-[#2563eb]/50 outline-none transition bg-white">
                            <option>Pilih Siswa</option>
                        </select>
                    </div>
                </div>
                <div>
                    <label class="block text-xs font-bold text-slate-500 uppercase tracking-wide mb-2">Alasan / Keterangan</label>
                    <textarea rows="2" class="w-full border border-slate-200 rounded-xl px-4 py-3 text-sm focus:ring-2 focus:ring-[#2563eb]/50 outline-none transition" placeholder="Tuliskan alasan izin..."></textarea>
                </div>
                <div>
                    <label class="block text-xs font-bold text-slate-500 uppercase tracking-wide mb-2">Upload Bukti Surat/Foto</label>
                    <div class="border-2 border-dashed border-slate-300 rounded-xl p-6 text-center hover:bg-slate-50 hover:border-blue-400 transition cursor-pointer group">
                        <i class="fa-solid fa-cloud-arrow-up text-3xl text-slate-300 mb-2 group-hover:text-blue-500 transition-colors"></i>
                        <p class="text-sm text-slate-600 font-bold">Klik untuk upload file</p>
                        <p class="text-[10px] text-slate-400 mt-1">Format: JPG, PNG, PDF (Maks 2MB)</p>
                    </div>
                </div>
                
                <div class="grid grid-cols-2 gap-4 mt-8 pt-4 border-t border-slate-100">
                    <button onclick="toggleModal('modalAddIzin'); Swal.fire('Ditolak', 'Permohonan izin ditolak.', 'error');" class="w-full bg-red-50 text-red-600 font-bold py-3 rounded-xl hover:bg-red-100 transition shadow-sm">TOLAK</button>
                    <button onclick="toggleModal('modalAddIzin'); Swal.fire('Disetujui', 'Permohonan izin disetujui.', 'success');" class="w-full bg-[#16a34a] text-white font-bold py-3 rounded-xl hover:bg-green-700 shadow-md shadow-green-200 transition">SETUJUI</button>
                </div>
            </div>
        </div>
    </div>

    <div id="modalEditIzin" class="fixed inset-0 bg-navy-900/50 hidden items-center justify-center z-50 backdrop-blur-sm transition-opacity duration-300">
        <div class="bg-white rounded-[20px] w-full max-w-lg shadow-2xl p-6 lg:p-8 transform scale-100 transition-all">
            <div class="flex justify-between items-center mb-6 pb-4 border-b border-slate-100">
                <h3 class="font-bold text-xl text-slate-800">Verifikasi Izin</h3>
                <button onclick="toggleModal('modalEditIzin')" class="text-slate-400 hover:text-red-500 transition">
                    <i class="fa-solid fa-xmark text-xl"></i>
                </button>
            </div>
            
            <div class="space-y-5">
                <div class="grid grid-cols-2 gap-4">
                    <div>
                        <label class="block text-xs font-bold text-slate-500 uppercase tracking-wide mb-2">Kelas</label>
                        <input type="text" value="9A" class="w-full border border-slate-200 rounded-xl px-4 py-3 text-sm bg-slate-50 outline-none text-slate-600" disabled>
                    </div>
                    <div>
                        <label class="block text-xs font-bold text-slate-500 uppercase tracking-wide mb-2">Siswa</label>
                        <input type="text" value="Doni Tata" class="w-full border border-slate-200 rounded-xl px-4 py-3 text-sm bg-slate-50 outline-none text-slate-600" disabled>
                    </div>
                </div>
                <div>
                    <label class="block text-xs font-bold text-slate-500 uppercase tracking-wide mb-2">Alasan</label>
                    <input type="text" value="Sakit Demam" class="w-full border border-slate-200 rounded-xl px-4 py-3 text-sm focus:ring-2 focus:ring-[#2563eb]/50 outline-none transition">
                </div>
                <div>
                    <label class="block text-xs font-bold text-slate-500 uppercase tracking-wide mb-2">Bukti Terlampir</label>
                    <div class="border border-slate-200 rounded-xl p-4 flex items-center justify-between bg-slate-50">
                        <div class="flex items-center gap-3">
                            <i class="fa-solid fa-file-image text-2xl text-blue-500"></i>
                            <div>
                                <p class="text-sm font-bold text-slate-700">Surat_Klinik.jpg</p>
                                <p class="text-xs text-slate-500">1.2 MB</p>
                            </div>
                        </div>
                        <button class="text-[#2563eb] text-sm font-bold hover:underline">Lihat Full</button>
                    </div>
                </div>
                
                <div class="grid grid-cols-2 gap-4 mt-8 pt-4 border-t border-slate-100">
                    <button onclick="toggleModal('modalEditIzin'); Swal.fire('Ditolak', 'Permohonan izin ditolak.', 'error');" class="w-full bg-red-50 text-red-600 font-bold py-3 rounded-xl hover:bg-red-100 transition shadow-sm">TOLAK</button>
                    <button onclick="toggleModal('modalEditIzin'); Swal.fire('Disetujui', 'Permohonan izin disetujui.', 'success');" class="w-full bg-[#16a34a] text-white font-bold py-3 rounded-xl hover:bg-green-700 shadow-md shadow-green-200 transition">SETUJUI</button>
                </div>
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
                title: "Hapus Data Izin?",
                text: "Data ini akan dihapus permanen dari sistem.",
                icon: "warning",
                showCancelButton: true,
                confirmButtonColor: "#ef4444",
                cancelButtonColor: "#64748b",
                confirmButtonText: "Ya, Hapus",
                cancelButtonText: "Batal"
            }).then((result) => {
                if (result.isConfirmed) {
                    Swal.fire('Terhapus!', 'Data izin berhasil dihapus.', 'success');
                }
            });
        }
    </script>
    @endpush
</x-layout-app>