<x-layout-app title="Edit Profil" :role="$role" :showAcademic="false">

    <div class="w-full max-w-2xl mx-auto space-y-8 fade-in">

        <div class="text-center">
            <div class="w-20 h-20 bg-indigo-600 rounded-full flex items-center justify-center mx-auto mb-4 text-2xl font-bold text-white shadow-lg ring-4 ring-white">
                {{ strtoupper(substr($userData['nama'] ?? 'KS', 0, 2)) }}
            </div>
            <h2 class="text-xl font-bold text-slate-800">{{ $userData['nama'] ?? 'Kepala Sekolah' }}</h2>
            <p class="text-sm text-slate-500 mt-1">
                {{ ucfirst(str_replace('_', ' ', $userData['role'] ?? 'kepala sekolah')) }}
            </p>
        </div>

        <div id="alertBox" class="hidden px-4 py-3 rounded-xl text-sm font-medium border"></div>

        <div class="bg-white rounded-3xl shadow-sm border border-slate-200 p-8 space-y-6">

            <!-- Nama -->
            <div>
                <label class="block text-xs font-bold text-slate-600 mb-2 uppercase tracking-wide">
                    Nama Lengkap
                </label>
                <input
                    type="text"
                    id="inputNama"
                    value="{{ $userData['nama'] ?? '' }}"
                    class="w-full border border-slate-200 rounded-xl p-3 text-sm focus:border-indigo-500 focus:ring-1 focus:ring-indigo-500 outline-none transition"
                    placeholder="Nama lengkap"
                />
            </div>

            <!-- Username -->
            <div>
                <label class="block text-xs font-bold text-slate-600 mb-2 uppercase tracking-wide">
                    Username
                </label>
                <input
                    type="text"
                    value="{{ $userData['username'] ?? '' }}"
                    readonly
                    class="w-full border-b border-slate-200 py-2 text-sm bg-transparent text-slate-400 cursor-not-allowed focus:outline-none"
                />
                <p class="text-[10px] text-slate-400 mt-1">
                    Username tidak dapat diubah.
                </p>
            </div>

            <!-- Password -->
            <div class="border-t border-slate-100 pt-6">
                <p class="text-xs font-bold text-slate-500 uppercase tracking-wide mb-4">
                    Ganti Password
                    <span class="font-normal text-slate-400 normal-case">(opsional)</span>
                </p>

                <div class="space-y-4">

                    <!-- Password Lama -->
                    <div>
                        <label class="block text-xs font-bold text-slate-600 mb-2 uppercase tracking-wide">
                            Password Lama
                        </label>
                        <div class="relative">
                            <input
                                type="password"
                                id="oldPass"
                                class="w-full border border-slate-200 rounded-xl p-3 text-sm focus:border-indigo-500 focus:ring-1 focus:ring-indigo-500 outline-none transition pr-12"
                                placeholder="••••••••"
                            />
                            <button
                                type="button"
                                onclick="togglePass('oldPass', this)"
                                class="absolute right-3 top-1/2 -translate-y-1/2 text-slate-400 hover:text-slate-600 p-1"
                            >
                                <i class="fa-solid fa-eye"></i>
                            </button>
                        </div>
                    </div>

                    <!-- Password Baru -->
                    <div>
                        <label class="block text-xs font-bold text-slate-600 mb-2 uppercase tracking-wide">
                            Password Baru
                        </label>
                        <div class="relative">
                            <input
                                type="password"
                                id="newPass"
                                class="w-full border border-slate-200 rounded-xl p-3 text-sm focus:border-indigo-500 focus:ring-1 focus:ring-indigo-500 outline-none transition pr-12"
                                placeholder="Minimal 6 karakter"
                            />
                            <button
                                type="button"
                                onclick="togglePass('newPass', this)"
                                class="absolute right-3 top-1/2 -translate-y-1/2 text-slate-400 hover:text-slate-600 p-1"
                            >
                                <i class="fa-solid fa-eye"></i>
                            </button>
                        </div>
                    </div>

                    <!-- Konfirmasi Password -->
                    <div>
                        <label class="block text-xs font-bold text-slate-600 mb-2 uppercase tracking-wide">
                            Konfirmasi Password Baru
                        </label>
                        <div class="relative">
                            <input
                                type="password"
                                id="confirmPass"
                                class="w-full border border-slate-200 rounded-xl p-3 text-sm focus:border-indigo-500 focus:ring-1 focus:ring-indigo-500 outline-none transition pr-12"
                                placeholder="Ulangi password baru"
                            />
                            <button
                                type="button"
                                onclick="togglePass('confirmPass', this)"
                                class="absolute right-3 top-1/2 -translate-y-1/2 text-slate-400 hover:text-slate-600 p-1"
                            >
                                <i class="fa-solid fa-eye"></i>
                            </button>
                        </div>
                    </div>

                </div>
            </div>

            <!-- Button -->
            <div class="pt-4 border-t border-slate-100 flex justify-end gap-3">
                <button
                    type="button"
                    onclick="window.location='{{ route('kepsek.dashboard') }}'"
                    class="px-6 py-2.5 rounded-xl text-sm font-bold text-slate-500 hover:bg-slate-50 transition"
                >
                    Batal
                </button>

                <button
                    type="button"
                    id="btnSimpan"
                    onclick="handleUpdateProfile()"
                    class="bg-indigo-600 text-white px-8 py-2.5 rounded-xl text-sm font-bold shadow-lg shadow-indigo-500/30 hover:bg-indigo-700 transition"
                >
                    Simpan Perubahan
                </button>
            </div>

        </div>
    </div>

    @push('scripts')
    <script>

        function togglePass(id, btn) {
            const input = document.getElementById(id);
            const icon  = btn.querySelector('i');

            if (input.type === 'password') {
                input.type = 'text';
                icon.classList.replace('fa-eye', 'fa-eye-slash');
            } else {
                input.type = 'password';
                icon.classList.replace('fa-eye-slash', 'fa-eye');
            }
        }

        function showAlert(message, type) {
            const box = document.getElementById('alertBox');

            box.textContent = message;
            box.className   = 'px-4 py-3 rounded-xl text-sm font-medium border';

            if (type === 'success') {
                box.classList.add('bg-green-50', 'border-green-200', 'text-green-700');
            } else {
                box.classList.add('bg-red-50', 'border-red-200', 'text-red-700');
            }

            box.classList.remove('hidden');
            box.scrollIntoView({ behavior: 'smooth', block: 'center' });

            setTimeout(() => box.classList.add('hidden'), 5000);
        }

        async function handleUpdateProfile() {

            const btn = document.getElementById('btnSimpan');

            btn.disabled    = true;
            btn.textContent = 'Menyimpan...';

            const payload = {
                nama: document.getElementById('inputNama').value.trim(),
                old_password: document.getElementById('oldPass').value,
                new_password: document.getElementById('newPass').value,
                confirm_password: document.getElementById('confirmPass').value,
                _token: '{{ csrf_token() }}',
            };

            try {

                const res = await fetch('{{ route("kepsek.profile.update") }}', {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/json',
                        'X-CSRF-TOKEN': '{{ csrf_token() }}'
                    },
                    body: JSON.stringify(payload),
                });

                const data = await res.json();

                if (data.success) {

                    showAlert(data.message, 'success');

                    document.getElementById('oldPass').value = '';
                    document.getElementById('newPass').value = '';
                    document.getElementById('confirmPass').value = '';

                } else {

                    showAlert(data.message, 'error');

                }

            } catch (e) {

                showAlert('Terjadi kesalahan. Coba lagi.', 'error');

            } finally {

                btn.disabled    = false;
                btn.textContent = 'Simpan Perubahan';

            }
        }

    </script>
    @endpush

</x-layout-app>
