<x-layout-app title="Manajemen User" role="admin">
    
    <header class="h-16 flex-none px-8 flex items-center justify-between bg-white border-b border-slate-200 sticky top-0 z-20">
        <h2 class="text-lg font-bold text-slate-800 tracking-tight">Manajemen User</h2>
        <div class="flex items-center gap-2">
            <div class="w-7 h-7 rounded-full bg-blue-100 flex items-center justify-center text-blue-600 font-bold text-[10px]">A</div>
            <span class="text-xs font-bold text-slate-600">Admin</span>
        </div>
    </header>

    <div class="flex-1 overflow-y-auto p-8 custom-scroll bg-[#f8fafc]">
        
        <div class="w-full space-y-6">

            <div class="bg-white rounded-[20px] border border-slate-200 shadow-sm p-6 flex flex-col md:flex-row justify-between items-center gap-4">
                
                <div>
                    <h1 class="text-xl font-bold text-slate-800">Manajemen User System</h1>
                    <p class="text-slate-500 text-sm mt-1">
                        Kelola akun Kepala Sekolah, Guru BK, dan Absensi
                    </p>
                </div>

                <button onclick="toggleModal('modalAddUser')" class="bg-[#2563eb] hover:bg-blue-700 text-white py-2.5 px-6 rounded-xl text-sm font-bold shadow-md shadow-blue-200 transition flex items-center gap-2">
                    <i class="fa-solid fa-user-plus text-xs"></i> Tambah User
                </button>
            </div>

            <div class="bg-white rounded-[20px] border border-slate-200 shadow-sm p-0 overflow-hidden">
                
                <div class="overflow-x-auto">
                    <table class="w-full text-left border-collapse">
                        <thead class="border-b border-slate-100 bg-[#fbfcfd]">
                            <tr>
                                <th class="py-5 pl-8 pr-4 text-[11px] font-bold text-slate-400 uppercase tracking-widest">NAMA USER</th>
                                <th class="py-5 px-4 text-[11px] font-bold text-slate-400 uppercase tracking-widest">USERNAME / NIP</th>
                                <th class="py-5 px-4 text-[11px] font-bold text-slate-400 uppercase tracking-widest">ROLE</th>
                                <th class="py-5 pr-8 pl-4 text-[11px] font-bold text-slate-400 uppercase tracking-widest text-right">AKSI</th>
                            </tr>
                        </thead>
                        
                        <tbody class="divide-y divide-slate-50">
                            
                            <tr class="hover:bg-slate-50 transition group">
                                <td class="py-6 pl-8 pr-4">
                                    <span class="text-base font-bold text-slate-800">Bpk. Kepala Sekolah</span>
                                </td>
                                <td class="py-6 px-4">
                                    <span class="text-sm font-medium text-slate-500 font-mono">198001012000031001</span>
                                </td>
                                <td class="py-6 px-4">
                                    <span class="bg-purple-100 text-purple-700 px-4 py-1.5 rounded-lg text-xs font-bold inline-block">
                                        Kepala Sekolah
                                    </span>
                                </td>
                                <td class="py-6 pr-8 pl-4 text-right">
                                    <div class="flex justify-end gap-3">
                                        <button class="text-slate-400 hover:text-blue-600 transition text-lg" title="Edit">
                                            <i class="fa-regular fa-pen-to-square"></i>
                                        </button>
                                        <button class="text-slate-400 hover:text-red-600 transition text-lg" title="Hapus">
                                            <i class="fa-regular fa-trash-can"></i>
                                        </button>
                                    </div>
                                </td>
                            </tr>

                            <tr class="hover:bg-slate-50 transition group">
                                <td class="py-6 pl-8 pr-4">
                                    <span class="text-base font-bold text-slate-800">Ibu Guru BK</span>
                                </td>
                                <td class="py-6 px-4">
                                    <span class="text-sm font-medium text-slate-500 font-mono">199002022015012005</span>
                                </td>
                                <td class="py-6 px-4">
                                    <span class="bg-orange-100 text-orange-700 px-4 py-1.5 rounded-lg text-xs font-bold inline-block">
                                        Guru BK
                                    </span>
                                </td>
                                <td class="py-6 pr-8 pl-4 text-right">
                                    <div class="flex justify-end gap-3">
                                        <button class="text-slate-400 hover:text-blue-600 transition text-lg" title="Edit">
                                            <i class="fa-regular fa-pen-to-square"></i>
                                        </button>
                                        <button class="text-slate-400 hover:text-red-600 transition text-lg" title="Hapus">
                                            <i class="fa-regular fa-trash-can"></i>
                                        </button>
                                    </div>
                                </td>
                            </tr>

                            <tr class="hover:bg-slate-50 transition group">
                                <td class="py-6 pl-8 pr-4">
                                    <span class="text-base font-bold text-slate-800">Admin Tata Usaha</span>
                                </td>
                                <td class="py-6 px-4">
                                    <span class="text-sm font-medium text-slate-500 font-mono">admin_tu</span>
                                </td>
                                <td class="py-6 px-4">
                                    <span class="bg-blue-100 text-blue-700 px-4 py-1.5 rounded-lg text-xs font-bold inline-block">
                                        Administrator
                                    </span>
                                </td>
                                <td class="py-6 pr-8 pl-4 text-right">
                                    <div class="flex justify-end gap-3">
                                        <button class="text-slate-400 hover:text-blue-600 transition text-lg" title="Edit">
                                            <i class="fa-regular fa-pen-to-square"></i>
                                        </button>
                                        <button class="text-slate-400 hover:text-red-600 transition text-lg" title="Hapus">
                                            <i class="fa-regular fa-trash-can"></i>
                                        </button>
                                    </div>
                                </td>
                            </tr>

                        </tbody>
                    </table>
                </div>
            </div>

        </div> </div>

    <div id="modalAddUser" class="fixed inset-0 bg-navy-900/50 hidden items-center justify-center z-50 backdrop-blur-sm transition-opacity">
        <div class="bg-white rounded-[20px] w-full max-w-lg shadow-2xl p-8 transform scale-100 transition-transform">
            <div class="flex justify-between items-center mb-6">
                <h3 class="font-bold text-xl text-slate-800">Tambah User Baru</h3>
                <button onclick="toggleModal('modalAddUser')" class="text-slate-400 hover:text-red-500 transition">
                    <i class="fa-solid fa-xmark text-xl"></i>
                </button>
            </div>
            
            <form action="#" class="space-y-5">
                <div>
                    <label class="block text-xs font-bold text-slate-500 uppercase mb-2">Nama Lengkap</label>
                    <input type="text" class="w-full border border-slate-200 rounded-xl px-4 py-3 text-sm font-bold text-slate-700 focus:border-blue-500 focus:ring-4 focus:ring-blue-500/10 outline-none transition" placeholder="Contoh: Budi Santoso">
                </div>
                <div>
                    <label class="block text-xs font-bold text-slate-500 uppercase mb-2">Username / NIP</label>
                    <input type="text" class="w-full border border-slate-200 rounded-xl px-4 py-3 text-sm font-bold text-slate-700 focus:border-blue-500 focus:ring-4 focus:ring-blue-500/10 outline-none transition" placeholder="Masukkan NIP atau Username">
                </div>
                <div>
                    <label class="block text-xs font-bold text-slate-500 uppercase mb-2">Role Akses</label>
                    <div class="relative">
                        <select class="w-full appearance-none border border-slate-200 rounded-xl px-4 py-3 text-sm font-bold text-slate-700 focus:border-blue-500 focus:ring-4 focus:ring-blue-500/10 outline-none transition bg-white cursor-pointer">
                            <option value="admin">Administrator (Tata Usaha)</option>
                            <option value="bk">Guru BK</option>
                            <option value="kepsek">Kepala Sekolah</option>
                        </select>
                        <i class="fa-solid fa-chevron-down absolute right-4 top-4 text-xs text-slate-400 pointer-events-none"></i>
                    </div>
                </div>
                <div>
                    <label class="block text-xs font-bold text-slate-500 uppercase mb-2">Password Default</label>
                    <input type="text" value="123456" class="w-full bg-slate-50 border border-slate-200 rounded-xl px-4 py-3 text-sm font-bold text-slate-500 outline-none" readonly>
                    <p class="text-[10px] text-slate-400 mt-1">*User dapat mengubah password setelah login.</p>
                </div>

                <div class="pt-4 flex gap-3 justify-end">
                    <button type="button" onclick="toggleModal('modalAddUser')" class="px-6 py-2.5 border border-slate-200 rounded-xl text-sm font-bold text-slate-500 hover:bg-slate-50 transition">Batal</button>
                    <button type="button" class="px-6 py-2.5 bg-[#2563eb] text-white rounded-xl text-sm font-bold shadow-lg hover:bg-blue-700 transition">Simpan User</button>
                </div>
            </form>
        </div>
    </div>

    @push('scripts')
    <script>
        function toggleModal(id) {
            const el = document.getElementById(id);
            if (el.classList.contains('hidden')) {
                el.classList.remove('hidden');
                el.classList.add('flex');
            } else {
                el.classList.add('hidden');
                el.classList.remove('flex');
            }
        }
    </script>
    @endpush

</x-layout-app>