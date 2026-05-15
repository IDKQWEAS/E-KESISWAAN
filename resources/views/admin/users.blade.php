<x-layout-app title="Manajemen User" role="admin">
    {{-- Padding Utama (Resize -20%: p-8 -> p-6) --}}
    <div class="flex-1 overflow-y-auto p-6 custom-scroll bg-[#f8fafc]">

        <div class="w-full space-y-5">

            {{-- Header Card (Resize -20%: p-6 -> p-5, rounded 20px -> 2xl) --}}
            <div class="bg-white rounded-2xl border border-slate-200 shadow-sm p-5 flex flex-col md:flex-row justify-between items-center gap-4">
                <div>
                    <h1 class="text-lg font-bold text-slate-800 uppercase tracking-tight">Manajemen User System</h1>
                    <p class="text-slate-500 text-xs mt-1 font-medium">
                        Kelola akun Kepala Sekolah, Guru BK, Petugas Absensi, dan Administrator. (Total: {{ count($dataUser) }} Akun)
                    </p>
                </div>

                <button onclick="openTambahModal()" class="bg-[#2563eb] hover:bg-blue-700 text-white py-2 px-5 rounded-xl text-xs font-black shadow-md shadow-blue-200 transition flex items-center gap-2 uppercase tracking-widest">
                    <i class="fa-solid fa-user-plus text-[10px]"></i> Tambah User
                </button>
            </div>

            {{-- Table Card (Resize -20%: rounded 20px -> 2xl) --}}
            <div class="bg-white rounded-2xl border border-slate-200 shadow-sm p-0 overflow-hidden">
                <div class="overflow-x-auto">
                    <table class="w-full text-left border-collapse">
                        <thead class="border-b border-slate-100 bg-[#fbfcfd]">
                            <tr>
                                {{-- Resize -20%: py-5 -> py-4, text 11px -> 9px --}}
                                <th class="py-4 pl-6 pr-4 text-[9px] font-black text-slate-400 uppercase tracking-widest">Nama User</th>
                                <th class="py-4 px-4 text-[9px] font-black text-slate-400 uppercase tracking-widest">Username / NIP</th>
                                <th class="py-4 px-4 text-[9px] font-black text-slate-400 uppercase tracking-widest text-center">Role</th>
                                <th class="py-4 pr-6 pl-4 text-[9px] font-black text-slate-400 uppercase tracking-widest text-right">Aksi</th>
                            </tr>
                        </thead>

                        <tbody class="divide-y divide-slate-50">
                            @forelse($dataUser as $user)
                                @php
                                    $role = strtolower($user['role'] ?? '');
                                    if(in_array($role, ['kepala_sekolah', 'kepsek', 'kepala sekolah'])) {
                                        $badgeClass = 'bg-purple-50 text-purple-700 border-purple-100';
                                        $roleText = 'Kepala Sekolah';
                                    } elseif(in_array($role, ['guru_bk', 'bk', 'guru bk'])) {
                                        $badgeClass = 'bg-orange-50 text-orange-700 border-orange-100';
                                        $roleText = 'Guru BK';
                                    } elseif($role === 'absensi') {
                                        $badgeClass = 'bg-green-50 text-green-700 border-green-100';
                                        $roleText = 'Petugas Absensi';
                                    } else {
                                        $badgeClass = 'bg-blue-50 text-blue-700 border-blue-100';
                                        $roleText = 'Administrator';
                                    }
                                @endphp

                                <tr class="hover:bg-slate-50 transition group h-14">
                                    <td class="py-4 pl-6 pr-4">
                                        <span class="text-sm font-bold text-slate-800 uppercase">{{ $user['nama'] ?? 'Tanpa Nama' }}</span>
                                    </td>
                                    <td class="py-4 px-4">
                                        <span class="text-xs font-bold text-slate-500 font-mono tracking-tight">{{ $user['username'] ?? ($user['nip'] ?? '-') }}</span>
                                    </td>
                                    <td class="py-4 px-4 text-center">
                                        <span class="{{ $badgeClass }} px-3 py-1 rounded-lg text-[9px] font-black border uppercase tracking-wider inline-block">
                                            {{ $roleText }}
                                        </span>
                                    </td>
                                    <td class="py-4 pr-6 pl-4 text-right">
                                        <div class="flex justify-end gap-3">
                                            <button type="button" onclick="openEditModal(this)" data-user='{!! json_encode($user) !!}' class="text-slate-400 hover:text-blue-600 transition text-base" title="Edit">
                                                <i class="fa-regular fa-pen-to-square"></i>
                                            </button>
                                            <button type="button" data-id="{{ $user['id'] }}" onclick="hapusUser(this.getAttribute('data-id'))" class="text-slate-400 hover:text-red-600 transition text-base" title="Hapus">
                                                <i class="fa-regular fa-trash-can"></i>
                                            </button>
                                        </div>
                                    </td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="4" class="py-12 text-center text-slate-300 text-xs font-bold uppercase tracking-widest italic opacity-60">
                                        Belum ada data user di database.
                                    </td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>

    {{-- MODAL FORM (RESIZED -20%) --}}
    <div id="modalAddUser" class="fixed inset-0 bg-slate-900/40 hidden items-center justify-center z-50 backdrop-blur-sm transition-opacity p-4">
        <div class="bg-white rounded-2xl w-full max-w-md shadow-2xl p-6">
            <div class="flex justify-between items-center mb-5">
                <h3 id="modalTitle" class="font-bold text-lg text-slate-800 tracking-tight">Tambah User Baru</h3>
                <button type="button" onclick="toggleModal('modalAddUser')" class="text-slate-400 hover:text-red-500 transition">
                    <i class="fa-solid fa-xmark text-lg"></i>
                </button>
            </div>

            <form id="formUser" class="space-y-4">
                <div>
                    <label class="block text-[9px] font-black text-slate-400 uppercase mb-1.5 ml-1 tracking-widest">Nama Lengkap</label>
                    <input type="text" name="nama" class="w-full border border-slate-200 rounded-xl px-4 py-2.5 text-xs font-bold text-slate-700 focus:border-blue-500 focus:ring-4 focus:ring-blue-500/10 outline-none transition uppercase" placeholder="Contoh: admin" required>
                </div>
                <div>
                    <label class="block text-[9px] font-black text-slate-400 uppercase mb-1.5 ml-1 tracking-widest">Username / NIP</label>
                    <input type="text" name="username" class="w-full border border-slate-200 rounded-xl px-4 py-2.5 text-xs font-bold text-slate-700 focus:border-blue-500 focus:ring-4 focus:ring-blue-500/10 outline-none transition" placeholder="Masukkan NIP atau Username" required>
                </div>
                <div>
                    <label class="block text-[9px] font-black text-slate-400 uppercase mb-1.5 ml-1 tracking-widest">Role Akses</label>
                    <div class="relative">
                        <select name="role" class="w-full appearance-none border border-slate-200 rounded-xl px-4 py-2.5 text-xs font-bold text-slate-700 focus:border-blue-500 focus:ring-4 focus:ring-blue-500/10 outline-none transition bg-white cursor-pointer" required>
                            <option value="guru_bk">Guru BK</option>
                            <option value="kepala_sekolah">Kepala Sekolah</option>
                            <option value="absensi">Petugas Absensi</option>
                            <option value="admin">Administrator</option>
                        </select>
                        <i class="fa-solid fa-chevron-down absolute right-4 top-3.5 text-[10px] text-slate-400 pointer-events-none"></i>
                    </div>
                </div>
                <div>
                    <label class="block text-[9px] font-black text-slate-400 uppercase mb-1.5 ml-1 tracking-widest">Password</label>
                    <input type="text" name="password" id="inputPassword" placeholder="Masukkan password" class="w-full border border-slate-200 rounded-xl px-4 py-2.5 text-xs font-bold text-slate-700 focus:border-blue-500 outline-none transition">
                    <p id="passwordHint" class="text-[8px] font-bold text-slate-400 mt-1 uppercase tracking-tight">*Kosongkan jika tidak ingin merubah password.</p>
                </div>

                <div class="pt-3 flex gap-2.5 justify-end">
                    <button type="button" onclick="toggleModal('modalAddUser')" class="px-5 py-2 border border-slate-200 rounded-xl text-[10px] font-black uppercase text-slate-500 hover:bg-slate-50 transition tracking-widest">Batal</button>
                    <button type="button" onclick="handleSimpanUser()" class="px-5 py-2 bg-[#2563eb] text-white rounded-xl text-[10px] font-black uppercase shadow-lg hover:bg-blue-700 transition tracking-widest">Simpan User</button>
                </div>
            </form>
        </div>
    </div>

    @push('scripts')
    <script>
        function toggleModal(id) {
            const el = document.getElementById(id);
            el.classList.toggle('hidden');
            el.classList.toggle('flex');
        }

        let isEdit = false;
        let editId = null;

        function openTambahModal() {
            isEdit = false;
            editId = null;
            document.getElementById('formUser').reset();
            document.getElementById('modalTitle').textContent = 'Tambah User Baru';
            document.getElementById('inputPassword').required = true;
            document.getElementById('passwordHint').textContent = '*Wajib diisi untuk user baru.';
            toggleModal('modalAddUser');
        }

        function openEditModal(btn) {
            const data = JSON.parse(btn.getAttribute('data-user'));
            isEdit = true;
            editId = data.id;

            const form = document.getElementById('formUser');
            form.nama.value = data.nama || '';
            form.username.value = data.username || data.nip || '';

            // Otomatis pilih role yang sesuai
            const roleDb = (data.role || '').toLowerCase();
            const option = Array.from(form.role.options).find(opt => opt.value.toLowerCase() === roleDb);
            form.role.value = option ? option.value : '';

            form.password.value = '';
            document.getElementById('modalTitle').textContent = 'Edit Data User';
            document.getElementById('inputPassword').required = false;
            document.getElementById('passwordHint').textContent = '*Kosongkan jika tidak ingin merubah password.';

            toggleModal('modalAddUser');
        }

        async function handleSimpanUser() {
            const form = document.getElementById('formUser');
            if(!form.checkValidity()) {
                form.reportValidity();
                return;
            }

            const payload = {
                nama: form.nama.value,
                username: form.username.value,
                role: form.role.value
            };

            if(form.password.value.trim() !== "") {
                payload.password = form.password.value;
            }

            // Sesuai dengan route web.php
            const url = isEdit ? `/admin/api-update-user/${editId}` : `/admin/api-simpan-user`;
            const method = 'POST'; // Laravel web.php mendaftarkan route update sebagai POST

            Swal.fire({title: 'Menyimpan...', didOpen: () => Swal.showLoading(), allowOutsideClick: false});

            try {
                const res = await fetch(url, {
                    method: method,
                    headers: {
                        'X-CSRF-TOKEN': '{{ csrf_token() }}',
                        'Content-Type': 'application/json'
                    },
                    body: JSON.stringify(payload)
                });

                const json = await res.json();

                if(res.ok) {
                    Swal.fire('Sukses', 'Data user berhasil disimpan!', 'success').then(() => location.reload());
                } else {
                    Swal.fire('Gagal', json.data?.message || 'Gagal menyimpan user.', 'error');
                }
            } catch (error) {
                Swal.fire('Error', 'Terjadi kesalahan koneksi', 'error');
            }
        }

        function hapusUser(id) {
            Swal.fire({
                title: 'Hapus user ini?',
                text: "User tidak akan bisa login lagi ke sistem!",
                icon: 'warning',
                showCancelButton: true,
                confirmButtonColor: '#ef4444',
                confirmButtonText: 'Ya, Hapus!'
            }).then(async (result) => {
                if (result.isConfirmed) {
                    Swal.fire({title: 'Menghapus...', didOpen: () => Swal.showLoading()});
                    try {
                        const res = await fetch(`/admin/api-hapus-user/${id}`, {
                            method: 'DELETE',
                            headers: { 'X-CSRF-TOKEN': '{{ csrf_token() }}' }
                        });
                        if(res.ok) {
                            Swal.fire('Terhapus!', 'User berhasil dihapus.', 'success').then(()=>location.reload());
                        } else {
                            const err = await res.json();
                            Swal.fire('Gagal', err.data?.message || 'Gagal menghapus', 'error');
                        }
                    } catch (error) {
                        Swal.fire('Error', 'Gagal menyambung ke server', 'error');
                    }
                }
            });
        }
    </script>
    @endpush
</x-layout-app>