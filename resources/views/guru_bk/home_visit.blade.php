<x-layout-app title="Home Visit" :role="$role">

    <div class="w-full space-y-6">

        <div class="bg-white p-5 lg:p-6 rounded-[20px] shadow-sm border border-slate-200 flex flex-col md:flex-row justify-between items-start md:items-center gap-4">
            <div>
                <h3 class="font-bold text-xl text-slate-800">Manajemen Home Visit</h3>
                <p class="text-[13px] text-[#ef4444] font-medium mt-1">Prioritas: Siswa dengan pelanggaran berat.</p>
            </div>

            <div class="flex items-center gap-3 w-full md:w-auto">
                <div class="relative group flex-1 md:flex-none">
                    <select onchange="window.location.href='?kelas='+this.value" class="w-full appearance-none bg-white border border-slate-200 hover:border-slate-300 rounded-xl py-2.5 pl-4 pr-10 text-sm font-medium text-slate-600 outline-none focus:ring-2 focus:ring-blue-500/20 cursor-pointer transition-all min-w-[140px]">
                        <option value="">Semua Kelas</option>
                        @foreach(['7A','7B','7C','7D','7E','7F','7G', '8A','8B','8C','8D','8E','8F','8G', '9A','9B','9C','9D','9E','9F','9G'] as $k)
                            <option value="{{ $k }}" {{ request('kelas') == $k ? 'selected' : '' }}>Kelas {{ $k }}</option>
                        @endforeach
                    </select>
                    <i class="fa-solid fa-chevron-down absolute right-4 top-3.5 text-[10px] text-slate-400 pointer-events-none"></i>
                </div>

                <button onclick="toggleModal('modalAddVisit')" class="bg-[#2563eb] hover:bg-blue-700 text-white px-5 py-2.5 rounded-xl text-sm font-bold shadow-md shadow-blue-200 transition flex items-center justify-center gap-2 flex-shrink-0">
                    <i class="fa-solid fa-plus"></i> Tambah Visit
                </button>
            </div>
        </div>

        <div class="grid grid-cols-1 lg:grid-cols-2 gap-8">

            <div class="flex flex-col gap-4">
                <div class="p-4 bg-[#f0fdf4] border border-[#bbf7d0] rounded-[16px] flex justify-between items-center">
                    <h4 class="font-bold text-[#166534] text-[15px]">Sudah Terlaksana</h4>
                    <span class="bg-white text-[#16a34a] border border-[#86efac] px-3 py-1 rounded-md text-[11px] font-bold shadow-sm">{{ count($visitSelesai) }} Selesai</span>
                </div>

                @forelse($visitSelesai as $visit)
                    <div onclick="openDetailModal({{ $visit['id'] }})" class="bg-white rounded-[16px] border border-slate-200 p-5 shadow-sm flex justify-between items-center cursor-pointer hover:border-slate-300 hover:shadow-md transition group">
                        <div class="flex gap-4 items-center">
                            <div class="w-12 h-12 rounded-full bg-slate-100 flex items-center justify-center text-sm font-bold text-slate-500 uppercase">
                                {{ substr($visit['nama_siswa'], 0, 2) }}
                            </div>
                            <div>
                                <p class="text-[15px] font-bold text-slate-800 group-hover:text-[#2563eb] transition">{{ $visit['nama_siswa'] }} ({{ $visit['kelas'] }})</p>
                                <p class="text-xs text-slate-400 mt-1 flex items-center gap-1.5"><i class="fa-regular fa-calendar"></i> {{ \Carbon\Carbon::parse($visit['tanggal'])->format('d M Y') }}</p>
                            </div>
                        </div>
                        <div class="flex gap-3 text-lg">
                            <button onclick="event.stopPropagation(); openEditModal({{ json_encode($visit) }})" class="text-slate-400 hover:text-[#2563eb] transition" title="Edit">
                                <i class="fa-regular fa-pen-to-square"></i>
                            </button>
                            <button onclick="event.stopPropagation(); confirmDelete({{ $visit['id'] }})" class="text-slate-400 hover:text-[#ef4444] transition" title="Hapus">
                                <i class="fa-regular fa-trash-can"></i>
                            </button>
                            <form id="delete-form-{{ $visit['id'] }}" action="{{ route('bk.visit.destroy', $visit['id']) }}" method="POST" class="hidden">
                                @csrf @method('DELETE')
                            </form>
                        </div>
                    </div>
                @empty
                    <div class="text-center py-6 text-sm text-slate-400 border-2 border-dashed border-slate-200 rounded-[16px]">Belum ada data.</div>
                @endforelse
            </div>

            <div class="flex flex-col gap-4">
                <div class="p-4 bg-[#fff7ed] border border-[#ffedd5] rounded-[16px] flex justify-between items-center">
                    <h4 class="font-bold text-[#9a3412] text-[15px]">Rencana Kunjungan</h4>
                    <span class="bg-white text-[#ea580c] border border-[#fdba74] px-3 py-1 rounded-md text-[11px] font-bold shadow-sm">{{ count($visitPending) }} Pending</span>
                </div>

                @forelse($visitPending as $visit)
                    <div onclick="openDetailModal({{ $visit['id'] }})" class="bg-white rounded-[16px] border border-slate-200 p-5 shadow-sm flex justify-between items-center cursor-pointer hover:border-slate-300 hover:shadow-md transition group">
                        <div class="flex gap-4 items-center">
                            <div class="w-12 h-12 rounded-full bg-[#fee2e2] flex items-center justify-center text-sm font-bold text-[#ef4444] uppercase">
                                {{ substr($visit['nama_siswa'], 0, 2) }}
                            </div>
                            <div>
                                <p class="text-[15px] font-bold text-slate-800 group-hover:text-[#2563eb] transition">{{ $visit['nama_siswa'] }} ({{ $visit['kelas'] }})</p>
                                <p class="text-xs text-[#ea580c] font-medium mt-1 flex items-center gap-1.5">
                                    <i class="fa-regular fa-calendar"></i> {{ \Carbon\Carbon::parse($visit['tanggal'])->format('d M Y') }}
                                </p>
                            </div>
                        </div>
                        <div class="flex gap-3 text-lg">
                            <button onclick="event.stopPropagation(); openEditModal({{ json_encode($visit) }})" class="text-slate-400 hover:text-[#2563eb] transition" title="Edit">
                                <i class="fa-regular fa-pen-to-square"></i>
                            </button>
                            <button onclick="event.stopPropagation(); confirmDelete({{ $visit['id'] }})" class="text-slate-400 hover:text-[#ef4444] transition" title="Hapus">
                                <i class="fa-regular fa-trash-can"></i>
                            </button>
                            <form id="delete-form-{{ $visit['id'] }}" action="{{ route('bk.visit.destroy', $visit['id']) }}" method="POST" class="hidden">
                                @csrf @method('DELETE')
                            </form>
                        </div>
                    </div>
                @empty
                    <div class="text-center py-6 text-sm text-slate-400 border-2 border-dashed border-slate-200 rounded-[16px]">Belum ada data.</div>
                @endforelse
            </div>

        </div>
    </div>

    <div id="modalAddVisit" class="fixed inset-0 bg-navy-900/50 hidden items-center justify-center z-50 backdrop-blur-sm transition-opacity duration-300">
        <div class="bg-white rounded-[20px] w-full max-w-xl p-8 shadow-2xl transform scale-100 transition-all">
            <div class="flex justify-between items-center mb-6 pb-4 border-b border-slate-100">
                <h3 class="font-bold text-xl text-slate-800">Tambah Kunjungan Rumah</h3>
                <button onclick="toggleModal('modalAddVisit')" class="text-slate-400 hover:text-red-500 transition"><i class="fa-solid fa-xmark text-xl"></i></button>
            </div>

            <form action="{{ route('bk.visit.store') }}" method="POST">
                @csrf
                <div class="space-y-5 mb-8">
                    <div class="grid grid-cols-2 gap-6">
                        <div>
                            <label class="text-xs font-bold text-slate-500 uppercase tracking-wide mb-2 block">Kelas</label>
                            <select id="add_kelas" onchange="fetchSiswaForAdd(this.value)" class="w-full border border-slate-200 p-3 rounded-xl text-sm focus:ring-2 focus:ring-[#2563eb]/50 outline-none transition bg-white" required>
                                <option value="">Pilih Kelas</option>
                                @foreach(['7A','7B','7C','7D','7E','7F','7G', '8A','8B','8C','8D','8E','8F','8G', '9A','9B','9C','9D','9E','9F','9G'] as $k)
                                    <option value="{{ $k }}">{{ $k }}</option>
                                @endforeach
                            </select>
                        </div>
                        <div>
                            <label class="text-xs font-bold text-slate-500 uppercase tracking-wide mb-2 block">Siswa</label>
                            <select id="add_id_siswa" name="id_siswa" class="w-full border border-slate-200 p-3 rounded-xl text-sm focus:ring-2 focus:ring-[#2563eb]/50 outline-none transition bg-white" required>
                                <option value="">Pilih Siswa</option>
                            </select>
                        </div>
                    </div>
                    <div>
                        <label class="text-xs font-bold text-slate-500 uppercase tracking-wide mb-2 block">Tanggal Kunjungan</label>
                        <input type="date" name="tanggal" class="w-full border border-slate-200 p-3 rounded-xl text-sm focus:ring-2 focus:ring-[#2563eb]/50 outline-none transition text-slate-600" required/>
                    </div>
                    <div>
                        <label class="text-xs font-bold text-slate-500 uppercase tracking-wide mb-2 block">Status Awal</label>
                        <select name="status" class="w-full border border-slate-200 p-3 rounded-xl text-sm focus:ring-2 focus:ring-[#2563eb]/50 outline-none transition bg-white" required>
                            <option value="Rencana Kunjungan">Rencana Kunjungan</option>
                            <option value="Sudah Terlaksana">Sudah Terlaksana</option>
                        </select>
                    </div>
                </div>

                <div class="flex gap-4">
                    <button type="button" onclick="toggleModal('modalAddVisit')" class="flex-1 border border-slate-200 py-3 rounded-xl font-bold text-sm text-slate-600 hover:bg-slate-50 transition">Batal</button>
                    <button type="submit" class="flex-1 bg-[#2563eb] text-white py-3 rounded-xl font-bold text-sm shadow-lg shadow-blue-200 hover:bg-blue-700 transition">Simpan Jadwal</button>
                </div>
            </form>
        </div>
    </div>

    <div id="modalEditVisit" class="fixed inset-0 bg-navy-900/50 hidden items-center justify-center z-50 backdrop-blur-sm transition-opacity duration-300">
        <div class="bg-white rounded-[20px] w-full max-w-xl p-8 shadow-2xl transform scale-100 transition-all">
            <div class="flex justify-between items-center mb-6 pb-4 border-b border-slate-100">
                <h3 class="font-bold text-xl text-slate-800">Edit Data Kunjungan</h3>
                <button onclick="toggleModal('modalEditVisit')" class="text-slate-400 hover:text-red-500 transition"><i class="fa-solid fa-xmark text-xl"></i></button>
            </div>

            <form id="formEditVisit" method="POST">
                @csrf @method('PATCH')
                <div class="space-y-5 mb-8">
                    <div class="grid grid-cols-2 gap-6">
                        <div>
                            <label class="text-xs font-bold text-slate-500 uppercase tracking-wide mb-2 block">Kelas</label>
                            <input type="text" id="edit_kelas" class="w-full border border-slate-200 p-3 rounded-xl text-sm bg-slate-50 outline-none" disabled>
                        </div>
                        <div>
                            <label class="text-xs font-bold text-slate-500 uppercase tracking-wide mb-2 block">Siswa</label>
                            <input type="text" id="edit_nama_siswa" class="w-full border border-slate-200 p-3 rounded-xl text-sm bg-slate-50 outline-none" disabled>
                        </div>
                    </div>
                    <div>
                        <label class="text-xs font-bold text-slate-500 uppercase tracking-wide mb-2 block">Tanggal Kunjungan</label>
                        <input type="date" id="edit_tanggal" name="tanggal" class="w-full border border-slate-200 p-3 rounded-xl text-sm focus:ring-2 focus:ring-[#2563eb]/50 outline-none transition text-slate-600" required/>
                    </div>
                    <div>
                        <label class="text-xs font-bold text-slate-500 uppercase tracking-wide mb-2 block">Status</label>
                        <select id="edit_status" name="status" class="w-full border border-slate-200 p-3 rounded-xl text-sm focus:ring-2 focus:ring-[#2563eb]/50 outline-none transition bg-white" required>
                            <option value="Rencana Kunjungan">Rencana Kunjungan</option>
                            <option value="Sudah Terlaksana">Sudah Terlaksana</option>
                        </select>
                    </div>
                </div>

                <div class="flex gap-4">
                    <button type="button" onclick="toggleModal('modalEditVisit')" class="flex-1 border border-slate-200 py-3 rounded-xl font-bold text-sm text-slate-600 hover:bg-slate-50 transition">Batal</button>
                    <button type="submit" class="flex-1 bg-orange-500 text-white py-3 rounded-xl font-bold text-sm shadow-lg shadow-orange-200 hover:bg-orange-600 transition">Update Data</button>
                </div>
            </form>
        </div>
    </div>

    <div id="modalDetailVisit" class="fixed inset-0 bg-navy-900/50 hidden items-center justify-center z-50 backdrop-blur-sm transition-opacity">
        <div class="bg-white rounded-[20px] w-full max-w-lg shadow-2xl overflow-hidden transform scale-100 transition-all">
            <div class="p-6 border-b border-slate-100 flex justify-between items-center bg-white">
                <h3 class="font-bold text-slate-800 text-lg">Detail Kunjungan Rumah</h3>
                <button onclick="toggleModal('modalDetailVisit')" class="text-slate-400 hover:text-red-500 transition">
                    <i class="fa-solid fa-xmark text-xl"></i>
                </button>
            </div>

            <div class="p-6" id="detailContent">
                <div class="text-center py-8 text-slate-500"><i class="fa-solid fa-spinner fa-spin text-2xl"></i><br>Memuat data...</div>
            </div>
        </div>
    </div>

    @push('scripts')
    <script>
        @if(session('success'))
            Swal.fire('Berhasil!', '{{ session('success') }}', 'success');
        @endif
        @if(session('error'))
            Swal.fire('Gagal!', '{{ session('error') }}', 'error');
        @endif

        function toggleModal(id) {
            const el = document.getElementById(id);
            if (el.classList.contains('hidden')) {
                el.classList.remove('hidden'); el.classList.add('flex');
            } else {
                el.classList.add('hidden'); el.classList.remove('flex');
            }
        }

        function confirmDelete(id) {
            Swal.fire({
                title: "Hapus Jadwal?",
                text: "Data kunjungan ini akan dihapus permanen.",
                icon: "warning",
                showCancelButton: true,
                confirmButtonColor: "#ef4444",
                cancelButtonColor: "#64748b",
                confirmButtonText: "Ya, Hapus",
                cancelButtonText: "Batal"
            }).then((result) => {
                if (result.isConfirmed) {
                    document.getElementById('delete-form-' + id).submit();
                }
            });
        }

        async function fetchSiswaForAdd(kelas) {
            const selectSiswa = document.getElementById('add_id_siswa');
            selectSiswa.innerHTML = '<option value="">Memuat...</option>';

            if(!kelas) {
                selectSiswa.innerHTML = '<option value="">Pilih Siswa</option>';
                return;
            }

            try {
                const res = await fetch(`{{ route('bk.visit.getSiswa') }}?kelas=${kelas}`);
                const data = await res.json();

                selectSiswa.innerHTML = '<option value="">Pilih Siswa</option>';
                data.forEach(siswa => {
                    selectSiswa.innerHTML += `<option value="${siswa.id}">${siswa.nama}</option>`;
                });
            } catch (e) {
                selectSiswa.innerHTML = '<option value="">Gagal memuat siswa</option>';
            }
        }

        function openEditModal(visit) {
            document.getElementById('edit_kelas').value = visit.kelas;
            document.getElementById('edit_nama_siswa').value = visit.nama_siswa;
            const formattedDate = new Date(visit.tanggal).toISOString().slice(0, 10);
            document.getElementById('edit_tanggal').value = formattedDate;
            document.getElementById('edit_status').value = visit.status;

            const formAction = `{{ url('bk/home-visit') }}/${visit.id}`;
            document.getElementById('formEditVisit').action = formAction;

            toggleModal('modalEditVisit');
        }

        async function openDetailModal(id) {
            toggleModal('modalDetailVisit');
            const content = document.getElementById('detailContent');
            content.innerHTML = '<div class="text-center py-8 text-slate-500"><i class="fa-solid fa-spinner fa-spin text-2xl"></i><br>Memuat data detail...</div>';

            try {
                const res = await fetch(`{{ url('bk/home-visit') }}/${id}/detail`);
                const data = await res.json();

                const dateObj = new Date(data.tanggal);
                const dateStr = dateObj.toLocaleDateString('id-ID', { day: 'numeric', month: 'long', year: 'numeric' });

                const waLink = data.no_telepon
                    ? `https://wa.me/62${data.no_telepon.replace(/^0+/, '')}`
                    : '#';

                content.innerHTML = `
                    <div class="flex items-center gap-5 mb-6 bg-gradient-to-r from-blue-50 to-indigo-50 p-5 rounded-2xl border border-blue-100">
                        <div class="w-16 h-16 bg-white text-[#2563eb] rounded-full flex items-center justify-center font-bold text-2xl shadow-sm border-2 border-white uppercase">
                            ${data.nama_siswa.substring(0,2)}
                        </div>
                        <div>
                            <h4 class="font-bold text-slate-800 text-xl">${data.nama_siswa}</h4>
                            <div class="flex flex-wrap items-center gap-2 mt-1.5">
                                <span class="text-[11px] font-bold text-slate-600 bg-white px-2.5 py-1 rounded-md border border-slate-200 shadow-sm">Kelas ${data.kelas}</span>
                                <span class="text-[10px] font-bold ${data.status === 'Sudah Terlaksana' ? 'text-green-700 bg-green-100 border-green-200' : 'text-orange-700 bg-orange-100 border-orange-200'} px-2.5 py-1 rounded-full flex items-center gap-1.5 border">
                                    <i class="fa-solid ${data.status === 'Sudah Terlaksana' ? 'fa-circle-check' : 'fa-clock'}"></i> ${data.status}
                                </span>
                            </div>
                        </div>
                    </div>

                    <div class="grid grid-cols-1 md:grid-cols-2 gap-6 mb-8">
                        <div class="space-y-1.5">
                            <p class="text-[10px] font-bold text-slate-400 uppercase tracking-wider flex items-center gap-1.5">
                                <i class="fa-regular fa-calendar text-slate-400"></i> Tanggal Kunjungan
                            </p>
                            <p class="font-bold text-slate-800 text-sm">${dateStr}</p>
                        </div>
                        <div class="space-y-1.5">
                            <p class="text-[10px] font-bold text-slate-400 uppercase tracking-wider flex items-center gap-1.5">
                                <i class="fa-solid fa-user-group text-slate-400"></i> Orang Tua / Wali
                            </p>
                            <p class="font-bold text-slate-800 text-sm">${data.nama_wali}</p>
                        </div>
                        <div class="md:col-span-2 space-y-2">
                            <p class="text-[10px] font-bold text-slate-400 uppercase tracking-wider flex items-center gap-1.5">
                                <i class="fa-solid fa-location-dot text-slate-400"></i> Alamat Rumah Lengkap
                            </p>
                            <p class="font-medium text-slate-700 text-sm leading-relaxed bg-slate-50 p-4 rounded-xl border border-slate-200 shadow-inner">
                                ${data.alamat}
                            </p>
                        </div>
                    </div>

                    <div class="pt-2 border-t border-slate-100">
                        <a href="${waLink}" target="_blank" class="flex items-center justify-center gap-2 w-full bg-[#16a34a] hover:bg-green-700 text-white font-bold py-3.5 rounded-xl text-sm shadow-md shadow-green-200 transition-all group ${!data.no_telepon ? 'opacity-50 pointer-events-none' : ''}">
                            <i class="fa-brands fa-whatsapp text-lg group-hover:scale-110 transition-transform"></i>
                            <span>Hubungi Wali via WhatsApp</span>
                        </a>
                    </div>
                `;
            } catch (e) {
                content.innerHTML = '<div class="text-center py-8 text-red-500">Gagal memuat detail data. Coba lagi.</div>';
            }
        }
    </script>
    @endpush
</x-layout-app>
