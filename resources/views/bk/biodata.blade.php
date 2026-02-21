<x-layout-app title="Biodata Lengkap" role="bk">
    
    <header class="h-16 flex-none px-8 flex items-center justify-between bg-white border-b border-slate-200 sticky top-0 z-20">
        <h2 class="text-lg font-bold text-slate-800 tracking-tight">Biodata Lengkap</h2>
        <div class="flex items-center gap-2">
            <div class="w-7 h-7 rounded-full bg-blue-600 flex items-center justify-center text-white font-bold text-[10px]">BK</div>
            <span class="text-xs font-bold text-slate-600">Guru BK</span>
        </div>
    </header>

    <div class="flex-1 overflow-y-auto p-6 lg:p-8 custom-scroll bg-[#f8fafc]">
        
        <div class="w-full space-y-6">

            <div class="bg-white p-4 lg:p-5 rounded-[20px] shadow-sm border border-slate-200 flex justify-between items-center gap-4 overflow-hidden">
                
                <div class="flex items-center gap-3 flex-shrink-0">
                    <div class="w-10 h-10 bg-[#2563eb] text-white rounded-xl flex items-center justify-center text-lg shadow-sm">
                        <i class="fa-solid fa-address-card"></i>
                    </div>
                    <div>
                        <h3 class="font-bold text-lg lg:text-[20px] text-slate-800">Database Biodata Siswa</h3>
                        <p class="text-[11px] lg:text-xs text-slate-500 mt-0.5">Kelola data lengkap siswa.</p>
                    </div>
                </div>

                <div class="flex items-center gap-3 overflow-x-auto custom-scroll pb-1 md:pb-0">
                    
                    <div class="relative group flex-shrink-0">
                        <select class="appearance-none bg-white border border-slate-200 hover:border-slate-300 rounded-xl py-2 pl-4 pr-9 text-xs font-bold text-slate-700 outline-none focus:ring-2 focus:ring-blue-500/20 cursor-pointer transition-all">
                            <option>Semua Kelas</option>
                            <option>7A</option>
                            <option>8A</option>
                            <option>9A</option>
                        </select>
                        <i class="fa-solid fa-chevron-down absolute right-3 top-2.5 text-[10px] text-slate-400 pointer-events-none"></i>
                    </div>

                    <button class="flex-shrink-0 bg-[#fefce8] border-2 border-dashed border-[#facc15] text-[#ca8a04] px-4 py-2 rounded-xl text-xs font-bold hover:bg-[#fef9c3] transition flex items-center gap-2">
                        <div class="w-5 h-5 rounded-full bg-[#facc15] text-white flex items-center justify-center text-[10px]">1</div>
                        Download Template <i class="fa-solid fa-file-excel ml-0.5"></i>
                    </button>

                    <button class="flex-shrink-0 bg-[#16a34a] hover:bg-green-700 text-white px-4 py-2 rounded-xl text-xs font-bold shadow-md shadow-green-200 transition flex items-center gap-2">
                        <i class="fa-solid fa-file-import"></i> Import Excel
                    </button>

                    <button class="flex-shrink-0 bg-white border border-[#16a34a] text-[#16a34a] hover:bg-green-50 px-4 py-2 rounded-xl text-xs font-bold shadow-sm transition flex items-center gap-2">
                        <i class="fa-solid fa-file-export"></i> Unduh Excel
                    </button>

                    <button onclick="toggleModal('modalBiodata')" class="flex-shrink-0 bg-[#2563eb] hover:bg-blue-700 text-white px-4 py-2 rounded-xl text-xs font-bold shadow-md shadow-blue-200 transition flex items-center gap-2">
                        <i class="fa-solid fa-plus"></i> Tambah Biodata
                    </button>

                </div>
            </div>

            <div class="bg-white rounded-[20px] shadow-sm border border-slate-200 overflow-hidden w-full">
                <div class="overflow-x-auto custom-scroll pb-2">
                    <table class="w-full text-left border-collapse min-w-max">
                        <thead class="bg-slate-50 border-b border-slate-100">
                            <tr>
                                <th class="py-3 pl-5 pr-3 text-[9px] font-bold text-slate-400 uppercase tracking-widest">NAMA / NIS / NISN</th>
                                <th class="py-3 px-3 text-[9px] font-bold text-slate-400 uppercase tracking-widest">KELAS/GENDER</th>
                                <th class="py-3 px-3 text-[9px] font-bold text-slate-400 uppercase tracking-widest">TTL</th>
                                <th class="py-3 px-3 text-[9px] font-bold text-slate-400 uppercase tracking-widest">DATA AYAH</th>
                                <th class="py-3 px-3 text-[9px] font-bold text-slate-400 uppercase tracking-widest">DATA IBU</th>
                                <th class="py-3 px-3 text-[9px] font-bold text-slate-400 uppercase tracking-widest">DATA WALI</th>
                                <th class="py-3 px-3 text-[9px] font-bold text-slate-400 uppercase tracking-widest">KONTAK / DOMISILI</th>
                                <th class="py-3 pr-5 pl-3 text-[9px] font-bold text-slate-400 uppercase tracking-widest text-right">AKSI</th>
                            </tr>
                        </thead>
                        
                        <tbody class="divide-y divide-slate-50">
                            <tr class="hover:bg-slate-50 transition group">
                                <td class="py-3 pl-5 pr-3">
                                    <div class="flex items-center gap-2.5">
                                        <div class="w-8 h-8 rounded-full bg-[#a3e635] text-slate-800 flex items-center justify-center font-bold text-xs shadow-sm border border-white">
                                            DT
                                        </div>
                                        <div>
                                            <p class="font-bold text-slate-800 text-xs group-hover:text-[#2563eb] transition">Doni Tata</p>
                                            <p class="text-[9px] text-slate-400 font-mono mt-0.5">12345 / 12345678</p>
                                        </div>
                                    </div>
                                </td>
                                
                                <td class="py-3 px-3">
                                    <div class="flex items-center gap-1.5">
                                        <span class="bg-blue-50 text-[#2563eb] px-2 py-1 rounded text-[10px] font-bold">9A</span>
                                        <span class="text-[11px] text-slate-500 font-medium">Laki-laki</span>
                                    </div>
                                </td>
                                
                                <td class="py-3 px-3 text-[11px] text-slate-600 font-medium">
                                    Jakarta, 12-05-2010
                                </td>
                                
                                <td class="py-3 px-3">
                                    <p class="font-bold text-slate-700 text-xs">Bpk. Agus</p>
                                    <p class="text-[9px] text-slate-500 mt-0.5">Wiraswasta</p>
                                </td>
                                
                                <td class="py-3 px-3">
                                    <p class="font-bold text-slate-700 text-xs">Ibu Siti</p>
                                    <p class="text-[9px] text-slate-500 mt-0.5">IRT</p>
                                </td>
                                
                                <td class="py-3 px-3 text-slate-400 font-bold text-xs">
                                    -
                                </td>
                                
                                <td class="py-3 px-3">
                                    <p class="font-bold text-[#16a34a] text-xs tracking-wide">0812345678</p>
                                    <p class="text-[9px] text-slate-500 mt-0.5 max-w-[100px] truncate" title="Jl. Merdeka No. 1">Jl. Merdeka No. 1</p>
                                </td>
                                
                                <td class="py-3 pr-5 pl-3 text-right">
                                    <div class="flex justify-end gap-2">
                                        <button onclick="toggleModal('modalBiodata')" class="text-slate-400 hover:text-[#2563eb] transition text-base" title="Edit">
                                            <i class="fa-regular fa-pen-to-square"></i>
                                        </button>
                                        <button onclick="confirmDelete()" class="text-slate-400 hover:text-[#ef4444] transition text-base" title="Hapus">
                                            <i class="fa-regular fa-trash-can"></i>
                                        </button>
                                    </div>
                                </td>
                            </tr>
                        </tbody>
                    </table>
                </div>

                <div class="flex flex-col md:flex-row justify-between items-center p-4 lg:p-5 border-t border-slate-100 bg-white">
                    <p class="text-slate-500 text-xs mb-3 md:mb-0">
                        Menampilkan 1–10 dari 320 data
                    </p>
                    <div class="flex items-center gap-1.5">
                        <button class="px-3 py-1.5 border border-slate-200 rounded-lg text-slate-600 text-[11px] font-bold hover:bg-slate-50 transition">
                            Prev
                        </button>
                        <button class="w-7 h-7 bg-[#2563eb] text-white rounded-lg text-[11px] font-bold shadow-md shadow-blue-200 transition">
                            1
                        </button>
                        <button class="w-7 h-7 bg-white border border-slate-200 text-slate-600 rounded-lg text-[11px] font-bold hover:bg-slate-50 transition">
                            2
                        </button>
                        <button class="px-3 py-1.5 border border-slate-200 rounded-lg text-slate-600 text-[11px] font-bold hover:bg-slate-50 transition">
                            Next
                        </button>
                    </div>
                </div>
            </div>

        </div> </div>

    <div id="modalBiodata" class="fixed inset-0 bg-navy-900/50 hidden items-center justify-center z-50 backdrop-blur-sm transition-opacity duration-300">
        <div class="bg-white rounded-[20px] w-full max-w-5xl shadow-2xl transform scale-100 transition-all duration-300 overflow-hidden flex flex-col max-h-[90vh]">
            
            <div class="p-6 border-b border-slate-100 flex justify-between items-center bg-slate-50">
                <h3 class="font-bold text-slate-800 text-xl" id="modalBiodataTitle">Input Biodata Lengkap Siswa</h3>
                <button onclick="toggleModal('modalBiodata')" class="text-slate-400 hover:text-red-500 transition">
                    <i class="fa-solid fa-xmark text-xl"></i>
                </button>
            </div>
            
            <div class="p-6 lg:p-8 overflow-y-auto custom-scroll space-y-8">
                <div>
                    <h4 class="text-sm font-bold text-[#2563eb] uppercase mb-5 border-l-4 border-[#2563eb] pl-3">A. Identitas Siswa</h4>
                    <div class="flex flex-col md:flex-row gap-8">
                        <div class="w-full md:w-1/4 flex flex-col items-center">
                            <div class="w-40 h-40 rounded-full border-2 border-dashed border-slate-300 flex flex-col items-center justify-center text-slate-400 hover:bg-slate-50 cursor-pointer bg-slate-50/50 relative group overflow-hidden transition-all">
                                <i class="fa-solid fa-camera mb-2 text-3xl group-hover:scale-110 transition-transform text-[#2563eb]"></i>
                                <span class="text-xs font-bold uppercase tracking-widest">Upload Foto</span>
                                <input type="file" class="absolute inset-0 opacity-0 cursor-pointer" title="Klik untuk upload foto" />
                            </div>
                            <p class="text-[10px] text-slate-400 mt-2 text-center">Format JPG/PNG. Maks 2MB.</p>
                        </div>

                        <div class="flex-1 space-y-5">
                            <div class="grid grid-cols-1 md:grid-cols-2 gap-5">
                                <div>
                                    <label class="text-xs font-bold text-slate-500 uppercase block mb-2">Nama Lengkap</label>
                                    <input type="text" class="w-full border border-slate-200 rounded-xl p-3 text-sm outline-none focus:ring-2 focus:ring-[#2563eb]/50 transition" placeholder="Cth: Doni Tata" />
                                </div>
                                <div>
                                    <label class="text-xs font-bold text-slate-500 uppercase block mb-2">Kelas</label>
                                    <select class="w-full border border-slate-200 rounded-xl p-3 text-sm bg-white outline-none focus:ring-2 focus:ring-[#2563eb]/50 transition">
                                        <option>Pilih Kelas</option>
                                        <option>7A</option>
                                        <option>8A</option>
                                        <option>9A</option>
                                    </select>
                                </div>
                            </div>
                            <div class="grid grid-cols-1 md:grid-cols-2 gap-5">
                                <div>
                                    <label class="text-xs font-bold text-slate-500 uppercase block mb-2">NIS</label>
                                    <input type="text" class="w-full border border-slate-200 rounded-xl p-3 text-sm outline-none focus:ring-2 focus:ring-[#2563eb]/50 transition" />
                                </div>
                                <div>
                                    <label class="text-xs font-bold text-slate-500 uppercase block mb-2">NISN</label>
                                    <input type="text" class="w-full border border-slate-200 rounded-xl p-3 text-sm outline-none focus:ring-2 focus:ring-[#2563eb]/50 transition" />
                                </div>
                            </div>
                            <div class="grid grid-cols-1 md:grid-cols-2 gap-5">
                                <div>
                                    <label class="text-xs font-bold text-slate-500 uppercase block mb-2">Tempat Lahir</label>
                                    <input type="text" class="w-full border border-slate-200 rounded-xl p-3 text-sm outline-none focus:ring-2 focus:ring-[#2563eb]/50 transition" />
                                </div>
                                <div>
                                    <label class="text-xs font-bold text-slate-500 uppercase block mb-2">Tanggal Lahir</label>
                                    <input type="date" class="w-full border border-slate-200 rounded-xl p-3 text-sm outline-none focus:ring-2 focus:ring-[#2563eb]/50 transition text-slate-600" />
                                </div>
                            </div>
                            <div>
                                <label class="text-xs font-bold text-slate-500 uppercase block mb-2">Alamat Domisili Lengkap</label>
                                <textarea rows="2" class="w-full border border-slate-200 rounded-xl p-3 text-sm outline-none focus:ring-2 focus:ring-[#2563eb]/50 transition"></textarea>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="space-y-5 border-t border-slate-100 pt-8">
                    <h4 class="text-sm font-bold text-[#2563eb] uppercase mb-4 border-l-4 border-[#2563eb] pl-3">B. Data Orang Tua / Wali</h4>
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-x-8 gap-y-5">
                        <div>
                            <label class="text-xs font-bold text-slate-500 uppercase block mb-2">Nama Ayah</label>
                            <input type="text" class="w-full border border-slate-200 rounded-xl p-3 text-sm outline-none focus:ring-2 focus:ring-[#2563eb]/50 transition" />
                        </div>
                        <div>
                            <label class="text-xs font-bold text-slate-500 uppercase block mb-2">Pekerjaan Ayah</label>
                            <input type="text" class="w-full border border-slate-200 rounded-xl p-3 text-sm outline-none focus:ring-2 focus:ring-[#2563eb]/50 transition" />
                        </div>
                        <div>
                            <label class="text-xs font-bold text-slate-500 uppercase block mb-2">Nama Ibu</label>
                            <input type="text" class="w-full border border-slate-200 rounded-xl p-3 text-sm outline-none focus:ring-2 focus:ring-[#2563eb]/50 transition" />
                        </div>
                        <div>
                            <label class="text-xs font-bold text-slate-500 uppercase block mb-2">Pekerjaan Ibu</label>
                            <input type="text" class="w-full border border-slate-200 rounded-xl p-3 text-sm outline-none focus:ring-2 focus:ring-[#2563eb]/50 transition" />
                        </div>
                        <div class="md:col-span-2">
                            <label class="text-xs font-bold text-slate-500 uppercase block mb-2">Nomor Telepon / WhatsApp Aktif</label>
                            <input type="text" class="w-full border border-slate-200 rounded-xl p-3 text-sm outline-none focus:ring-2 focus:ring-[#2563eb]/50 transition font-bold text-slate-700" placeholder="08xxxxxxxxxx" />
                        </div>
                    </div>
                </div>
            </div>
            
            <div class="p-6 border-t border-slate-100 flex justify-end gap-3 bg-slate-50/50">
                <button onclick="toggleModal('modalBiodata')" class="px-6 py-2.5 border border-slate-300 rounded-xl font-bold text-sm text-slate-600 hover:bg-white transition">
                    Batal
                </button>
                <button onclick="Swal.fire('Tersimpan', 'Biodata lengkap berhasil disimpan.', 'success'); toggleModal('modalBiodata');" class="px-8 py-2.5 bg-[#2563eb] text-white rounded-xl font-bold text-sm shadow-lg shadow-blue-200 hover:bg-blue-700 transition">
                    Simpan Data
                </button>
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
                    Swal.fire('Terhapus!', 'Data biodata berhasil dihapus.', 'success');
                }
            });
        }
    </script>
    @endpush
</x-layout-app>