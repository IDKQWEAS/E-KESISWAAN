<x-layout-app title="Approval Perizinan" :role="$role">

    <div class="flex-1 overflow-y-auto px-6 pb-6 pt-0 lg:px-8 lg:pb-8 custom-scroll bg-[#f8fafc]">

        @if(session('success'))
            <div class="mb-4 bg-green-50 border border-green-200 text-green-700 px-5 py-3 rounded-xl text-sm font-medium flex items-center gap-2">
                <i class="fa-solid fa-circle-check"></i> {{ session('success') }}
            </div>
        @endif
        @if(session('error'))
            <div class="mb-4 bg-red-50 border border-red-200 text-red-600 px-5 py-3 rounded-xl text-sm font-medium flex items-center gap-2">
                <i class="fa-solid fa-circle-exclamation"></i> {{ session('error') }}
            </div>
        @endif

        @if ($errors->any())
            <div class="mb-4 bg-red-50 border border-red-200 text-red-600 px-5 py-3 rounded-xl text-sm font-medium">
                <p class="font-bold mb-1"><i class="fa-solid fa-triangle-exclamation"></i> Form gagal disubmit karena:</p>
                <ul class="list-disc pl-5">
                    @foreach ($errors->all() as $error)
                        <li>{{ $error }}</li>
                    @endforeach
                </ul>
            </div>
        @endif

        <div class="w-full space-y-6">
            <div class="bg-white p-6 lg:p-8 rounded-[24px] shadow-sm border border-slate-200">

                <div class="flex flex-col xl:flex-row justify-between items-start xl:items-center gap-6 mb-8">
                    <div>
                        <h3 class="font-bold text-xl text-slate-800">Pusat Approval Izin</h3>
                        <p class="text-sm text-slate-500 mt-1">Verifikasi izin keluar/pulang siswa.</p>
                    </div>

                    <form method="GET" action="{{ route('bk.perizinan') }}"
                          class="flex flex-wrap items-center gap-3 w-full xl:w-auto">
                        <div class="relative flex-1 sm:flex-none">
                            <select name="kelas" onchange="this.form.submit()"
                                class="w-full appearance-none bg-white border border-slate-200 rounded-xl py-2.5 pl-4 pr-10 text-sm font-medium text-slate-600 outline-none focus:ring-2 focus:ring-blue-200 cursor-pointer min-w-[150px]">
                                <option value="">Semua Kelas</option>
                                @foreach($kelasList as $kelas)
                                    <option value="{{ $kelas }}" {{ request('kelas') == $kelas ? 'selected' : '' }}>{{ $kelas }}</option>
                                @endforeach
                            </select>
                            <i class="fa-solid fa-chevron-down absolute right-4 top-3.5 text-[10px] text-slate-400 pointer-events-none"></i>
                        </div>

                        <div class="flex-1 sm:flex-none">
                            <input type="date" name="tanggal" onchange="this.form.submit()"
                                value="{{ request('tanggal') }}"
                                class="w-full bg-white border border-slate-200 rounded-xl py-2.5 px-4 text-sm font-medium text-slate-600 outline-none focus:ring-2 focus:ring-blue-200" />
                        </div>

                        <button type="button" onclick="openModal('modalAddIzin')"
                            class="bg-[#2563eb] hover:bg-blue-700 text-white px-5 py-2.5 rounded-xl text-sm font-bold shadow-md shadow-blue-200 transition flex items-center gap-2 w-full sm:w-auto">
                            <i class="fa-solid fa-plus text-[12px]"></i> Buat Izin Baru
                        </button>
                    </form>
                </div>

                <div class="border border-slate-200 rounded-[20px] overflow-hidden">
                    <div class="overflow-x-auto">
                        <table class="w-full text-left text-sm whitespace-nowrap min-w-max">
                            <thead class="bg-white border-b border-slate-100">
                                <tr>
                                    <th class="py-5 pl-6 pr-4 text-[10px] font-bold text-slate-400 uppercase tracking-widest">Tanggal</th>
                                    <th class="py-5 px-4 text-[10px] font-bold text-slate-400 uppercase tracking-widest">Siswa</th>
                                    <th class="py-5 px-4 text-[10px] font-bold text-slate-400 uppercase tracking-widest">Status</th>
                                    <th class="py-5 px-4 text-[10px] font-bold text-slate-400 uppercase tracking-widest">Alasan</th>
                                    <th class="py-5 px-4 text-[10px] font-bold text-slate-400 uppercase tracking-widest">Bukti</th>
                                    <th class="py-5 pr-6 pl-4 text-[10px] font-bold text-slate-400 uppercase tracking-widest text-right">Aksi</th>
                                </tr>
                            </thead>
                            <tbody class="divide-y divide-slate-50">
                                @forelse($perizinan as $izin)
                                    @php
                                        $tglRaw = $izin['tanggal'] ?? $izin['created_at'] ?? null;
                                        $tgl    = $tglRaw ? \Carbon\Carbon::parse($tglRaw)->translatedFormat('d M Y') : '-';
                                        $statusLabel = match($izin['status'] ?? '') {
                                            'izin'  => ['label' => 'Izin',  'bg' => 'bg-blue-50',   'text' => 'text-blue-600'],
                                            'sakit' => ['label' => 'Sakit', 'bg' => 'bg-yellow-50', 'text' => 'text-yellow-600'],
                                            'alpha' => ['label' => 'Alpha', 'bg' => 'bg-red-50',    'text' => 'text-red-500'],
                                            default => ['label' => ucfirst($izin['status'] ?? '-'), 'bg' => 'bg-slate-100', 'text' => 'text-slate-500'],
                                        };
                                    @endphp
                                    <tr class="hover:bg-slate-50 transition">
                                        <td class="py-5 pl-6 pr-4 text-[13px] text-slate-500 font-medium">{{ $tgl }}</td>
                                        <td class="py-5 px-4 font-bold text-slate-800 text-[15px]">
                                            {{ $izin['nama_siswa'] ?? '-' }}
                                            <span class="text-slate-400 font-normal text-[13px]">({{ $izin['kelas'] ?? '-' }})</span>
                                        </td>
                                        <td class="py-5 px-4">
                                            <span class="{{ $statusLabel['bg'] }} {{ $statusLabel['text'] }} px-4 py-1.5 rounded-full text-[10px] font-bold uppercase tracking-wider inline-block">
                                                {{ $statusLabel['label'] }}
                                            </span>
                                        </td>
                                        <td class="py-5 px-4 text-[14px] text-slate-600 max-w-[200px] truncate">{{ $izin['keterangan'] ?? '-' }}</td>
                                        <td class="py-5 px-4">
                                            @if(!empty($izin['gambar']))
                                                <button type="button" onclick='openEditModal({{ json_encode($izin) }})'
                                                   class="text-[#2563eb] text-[13px] font-bold underline hover:text-blue-800 flex items-center gap-1.5 cursor-pointer">
                                                    <i class="fa-regular fa-image"></i> Lihat Preview
                                                </button>
                                            @else
                                                <span class="text-slate-300 text-[13px]">—</span>
                                            @endif
                                        </td>
                                        <td class="py-5 pr-6 pl-4">
                                            <div class="flex justify-end gap-2">
                                                <button type="button"
                                                    onclick='openEditModal({{ json_encode($izin) }})'
                                                    class="w-9 h-9 rounded-xl bg-slate-100 text-slate-400 hover:bg-slate-200 hover:text-[#2563eb] transition flex items-center justify-center">
                                                    <i class="fa-regular fa-pen-to-square"></i>
                                                </button>
                                                <button type="button"
                                                    onclick="confirmDelete({{ $izin['id'] }})"
                                                    class="w-9 h-9 rounded-xl bg-slate-100 text-slate-400 hover:bg-slate-200 hover:text-[#ef4444] transition flex items-center justify-center">
                                                    <i class="fa-regular fa-trash-can"></i>
                                                </button>
                                            </div>
                                        </td>
                                    </tr>
                                @empty
                                    <tr>
                                        <td colspan="6" class="py-16 text-center text-slate-400 text-sm">
                                            <i class="fa-regular fa-folder-open text-3xl mb-3 block"></i>
                                            Belum ada data perizinan.
                                        </td>
                                    </tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>
                </div>

            </div>
        </div>
    </div>

    {{-- ===== MODAL ADD ===== --}}
    <div id="modalAddIzin" style="display:none" class="fixed inset-0 bg-black/40 items-center justify-center z-50 backdrop-blur-sm">
        <div class="bg-white rounded-[20px] w-full max-w-lg shadow-2xl p-6 lg:p-8 mx-4 max-h-[90vh] overflow-y-auto custom-scroll">
            <div class="flex justify-between items-center mb-6 pb-4 border-b border-slate-100">
                <h3 class="font-bold text-xl text-slate-800">Formulir Izin Siswa</h3>
                <button type="button" onclick="closeModal('modalAddIzin')" class="text-slate-400 hover:text-red-500 transition">
                    <i class="fa-solid fa-xmark text-xl"></i>
                </button>
            </div>
            <form method="POST" action="{{ route('bk.perizinan.store') }}" enctype="multipart/form-data">
                @csrf
                <div class="space-y-5">
                    <div>
                        <label class="block text-xs font-bold text-slate-500 uppercase tracking-wide mb-2">Tanggal Izin</label>
                        <input type="date" name="tanggal" required
                            class="w-full border border-slate-200 rounded-xl px-4 py-3 text-sm outline-none focus:ring-2 focus:ring-blue-300 text-slate-700">
                    </div>
                    <div class="grid grid-cols-2 gap-4">
                        <div>
                            <label class="block text-xs font-bold text-slate-500 uppercase tracking-wide mb-2">Kelas</label>
                            <select id="addKelasSelect" onchange="filterSiswaByKelas(this.value, 'addSiswaSelect')"
                                class="w-full border border-slate-200 rounded-xl px-4 py-3 text-sm outline-none focus:ring-2 focus:ring-blue-300 bg-white">
                                <option value="">Pilih Kelas</option>
                                @foreach($kelasList as $k)
                                    <option value="{{ $k }}">{{ $k }}</option>
                                @endforeach
                            </select>
                        </div>
                        <div>
                            <label class="block text-xs font-bold text-slate-500 uppercase tracking-wide mb-2">Siswa</label>
                            <select id="addSiswaSelect" name="id_siswa" required
                                class="w-full border border-slate-200 rounded-xl px-4 py-3 text-sm outline-none focus:ring-2 focus:ring-blue-300 bg-white">
                                <option value="">Pilih Siswa</option>
                            </select>
                        </div>
                    </div>
                    <div>
                        <label class="block text-xs font-bold text-slate-500 uppercase tracking-wide mb-2">Status</label>
                        <select name="status" required
                            class="w-full border border-slate-200 rounded-xl px-4 py-3 text-sm outline-none focus:ring-2 focus:ring-blue-300 bg-white">
                            <option value="">Pilih Status</option>
                            <option value="izin">Izin</option>
                            <option value="sakit">Sakit</option>
                            <option value="alpha">Alpha</option>
                        </select>
                    </div>
                    <div>
                        <label class="block text-xs font-bold text-slate-500 uppercase tracking-wide mb-2">Alasan / Keterangan</label>
                        <textarea name="keterangan" rows="2"
                            class="w-full border border-slate-200 rounded-xl px-4 py-3 text-sm outline-none focus:ring-2 focus:ring-blue-300"
                            placeholder="Tuliskan alasan izin..."></textarea>
                    </div>
                    <div>
                        <label class="block text-xs font-bold text-slate-500 uppercase tracking-wide mb-2">Upload Bukti</label>
                        <label class="border-2 border-dashed border-slate-300 rounded-xl p-6 text-center hover:bg-slate-50 hover:border-blue-400 transition cursor-pointer block">
                            <input type="file" name="gambar" accept=".jpg,.jpeg,.png,.pdf" class="hidden"
                                onchange="showFileName(this, 'addFileName')">
                            <i class="fa-solid fa-cloud-arrow-up text-3xl text-slate-300 mb-2 block"></i>
                            <p id="addFileName" class="text-sm text-slate-600 font-bold">Klik untuk upload file</p>
                            <p class="text-[10px] text-slate-400 mt-1">Format: JPG, PNG, PDF (Maks 2MB)</p>
                        </label>
                    </div>
                    <div class="flex justify-end gap-3 pt-4 border-t border-slate-100">
                        <button type="button" onclick="closeModal('modalAddIzin')"
                            class="px-5 py-2.5 rounded-xl border border-slate-200 text-slate-500 text-sm font-bold hover:bg-slate-50 transition">Batal</button>
                        <button type="submit"
                            class="px-6 py-2.5 rounded-xl bg-[#2563eb] text-white text-sm font-bold hover:bg-blue-700 shadow-md shadow-blue-200 transition">Simpan</button>
                    </div>
                </div>
            </form>
        </div>
    </div>

    {{-- ===== MODAL EDIT / PREVIEW ===== --}}
    <div id="modalEditIzin" style="display:none" class="fixed inset-0 bg-black/40 items-center justify-center z-50 backdrop-blur-sm">
        <div class="bg-white rounded-[20px] w-full max-w-lg shadow-2xl p-6 lg:p-8 mx-4 max-h-[90vh] overflow-y-auto custom-scroll">
            <div class="flex justify-between items-center mb-6 pb-4 border-b border-slate-100">
                <h3 class="font-bold text-xl text-slate-800">Edit / Verifikasi Izin</h3>
                <button type="button" onclick="closeModal('modalEditIzin')" class="text-slate-400 hover:text-red-500 transition">
                    <i class="fa-solid fa-xmark text-xl"></i>
                </button>
            </div>

            <form id="editForm" method="POST" action="" enctype="multipart/form-data">
                @csrf
                <div class="space-y-5">
                    <div class="grid grid-cols-2 gap-4">
                        <div>
                            <label class="block text-xs font-bold text-slate-500 uppercase tracking-wide mb-2">Kelas</label>
                            <input type="text" id="editKelas"
                                class="w-full border border-slate-200 rounded-xl px-4 py-3 text-sm bg-slate-50 outline-none text-slate-500 cursor-not-allowed" disabled>
                        </div>
                        <div>
                            <label class="block text-xs font-bold text-slate-500 uppercase tracking-wide mb-2">Siswa</label>
                            <input type="text" id="editNama"
                                class="w-full border border-slate-200 rounded-xl px-4 py-3 text-sm bg-slate-50 outline-none text-slate-500 cursor-not-allowed" disabled>
                        </div>
                    </div>
                    <div>
                        <label class="block text-xs font-bold text-slate-500 uppercase tracking-wide mb-2">Status</label>
                        <select name="status" id="editStatus"
                            class="w-full border border-slate-200 rounded-xl px-4 py-3 text-sm outline-none focus:ring-2 focus:ring-blue-300 bg-white">
                            <option value="izin">Izin</option>
                            <option value="sakit">Sakit</option>
                            <option value="alpha">Alpha</option>
                        </select>
                    </div>
                    <div>
                        <label class="block text-xs font-bold text-slate-500 uppercase tracking-wide mb-2">Alasan</label>
                        <input type="text" name="keterangan" id="editKeterangan"
                            class="w-full border border-slate-200 rounded-xl px-4 py-3 text-sm outline-none focus:ring-2 focus:ring-blue-300">
                    </div>
                    <div>
                        <label class="block text-xs font-bold text-slate-500 uppercase tracking-wide mb-2">Tanggal</label>
                        <input type="date" name="tanggal" id="editTanggal"
                            class="w-full border border-slate-200 rounded-xl px-4 py-3 text-sm outline-none focus:ring-2 focus:ring-blue-300">
                    </div>

                    {{-- AREA PREVIEW GAMBAR INLINE --}}
                    <div>
                        <label class="block text-xs font-bold text-slate-500 uppercase tracking-wide mb-2">Bukti Terlampir</label>
                        <div id="editBuktiBox" class="border border-slate-200 rounded-xl p-3 bg-slate-50 mb-3" style="display:none">
                            <p class="text-[10px] font-bold text-slate-400 uppercase tracking-wider mb-2 text-center">Preview Bukti Saat Ini</p>

                            <div id="editBuktiImageWrapper" class="w-full flex justify-center bg-gray-200/50 rounded-lg overflow-hidden p-2">
                                <img id="editBuktiImage" src="" alt="Bukti Izin"
                                    class="max-h-48 object-contain rounded shadow-sm"
                                    onerror="this.parentElement.style.display='none'">
                            </div>

                            <div id="editBuktiPdfWrapper" class="p-6 flex-col items-center justify-center bg-white rounded-lg border border-slate-200 hidden">
                                <i class="fa-solid fa-file-pdf text-4xl text-red-500 mb-2"></i>
                                <p id="editBuktiPdfName" class="text-sm font-bold text-slate-700 text-center break-all px-4"></p>
                                <a id="editBuktiPdfLink" href="" target="_blank" class="mt-3 px-4 py-1.5 bg-red-50 text-red-600 rounded-full text-xs font-bold hover:bg-red-100 transition">
                                    Buka File PDF
                                </a>
                            </div>
                        </div>
                        <p id="editNoBukti" class="text-slate-400 text-sm mb-3">Tidak ada bukti terlampir.</p>

                        <label class="block text-xs font-bold text-slate-500 uppercase tracking-wide mt-2 mb-2">Ubah / Upload Bukti Baru</label>
                        <label class="border-2 border-dashed border-slate-300 rounded-xl p-4 text-center hover:bg-slate-50 hover:border-blue-400 transition cursor-pointer block">
                            <input type="file" name="gambar" accept=".jpg,.jpeg,.png,.pdf" class="hidden"
                                onchange="showFileName(this, 'editNewFileName')">
                            <i class="fa-solid fa-upload text-xl text-slate-300 mb-2 block"></i>
                            <p id="editNewFileName" class="text-sm text-slate-600 font-bold">Pilih file baru</p>
                        </label>
                    </div>

                    <div class="flex justify-end gap-3 pt-4 border-t border-slate-100">
                        <button type="button" onclick="closeModal('modalEditIzin')"
                            class="px-5 py-2.5 rounded-xl border border-slate-200 text-slate-500 text-sm font-bold hover:bg-slate-50 transition">Tutup</button>
                        <button type="submit"
                            class="px-6 py-2.5 rounded-xl bg-[#2563eb] text-white text-sm font-bold hover:bg-blue-700 shadow-md shadow-blue-200 transition">Simpan Perubahan</button>
                    </div>
                </div>
            </form>
        </div>
    </div>

    <form id="deleteForm" method="POST" action="" class="hidden">@csrf</form>

    @push('scripts')
    <script>
        const allSiswa   = @json($siswaList);
        // Base URL tanpa /api — konsisten dengan biodata.blade.php
        const staticBase = "{{ rtrim(preg_replace('#/api$#', '', env('API_BASE_URL')), '/') }}";

        function openModal(id) {
            document.getElementById(id).style.display = 'flex';
        }

        function closeModal(id) {
            document.getElementById(id).style.display = 'none';
        }

        ['modalAddIzin', 'modalEditIzin'].forEach(id => {
            document.getElementById(id).addEventListener('click', function(e) {
                if (e.target === this) closeModal(id);
            });
        });

        function openEditModal(izin) {
            document.getElementById('editForm').action = `/bk/perizinan/${izin.id}/update`;
            document.getElementById('editKelas').value      = izin.kelas      ?? '';
            document.getElementById('editNama').value       = izin.nama_siswa ?? '';
            document.getElementById('editKeterangan').value = izin.keterangan ?? '';
            document.getElementById('editStatus').value     = izin.status     ?? 'izin';

            const tglRaw = izin.tanggal ?? izin.created_at ?? '';
            document.getElementById('editTanggal').value = tglRaw ? String(tglRaw).substring(0, 10) : '';

            const buktiBox = document.getElementById('editBuktiBox');
            const noBukti  = document.getElementById('editNoBukti');
            const imgWrap  = document.getElementById('editBuktiImageWrapper');
            const imgEl    = document.getElementById('editBuktiImage');
            const pdfWrap  = document.getElementById('editBuktiPdfWrapper');
            const pdfName  = document.getElementById('editBuktiPdfName');
            const pdfLink  = document.getElementById('editBuktiPdfLink');

            if (izin.gambar) {
                // Pakai staticBase (http://127.0.0.1:3000) — bukan apiBase
                const fileUrl   = `${staticBase}/${izin.gambar}?t=${Date.now()}`;
                const extension = izin.gambar.split('.').pop().toLowerCase();

                if (['jpg', 'jpeg', 'png', 'gif', 'webp'].includes(extension)) {
                    imgEl.src = fileUrl;
                    imgWrap.style.display = 'flex';
                    pdfWrap.style.display = 'none';
                } else {
                    imgWrap.style.display = 'none';
                    pdfName.textContent = izin.gambar.split('/').pop();
                    pdfLink.href = fileUrl;
                    pdfWrap.style.display = 'flex';
                }

                buktiBox.style.display = 'block';
                noBukti.style.display  = 'none';
            } else {
                buktiBox.style.display = 'none';
                noBukti.style.display  = 'block';
            }

            openModal('modalEditIzin');
        }

        function confirmDelete(id) {
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
                    const form  = document.getElementById('deleteForm');
                    form.action = `/bk/perizinan/${id}/delete`;
                    form.submit();
                }
            });
        }

        function filterSiswaByKelas(kelas, targetSelectId) {
            const select = document.getElementById(targetSelectId);
            select.innerHTML = '<option value="">Pilih Siswa</option>';
            if (!kelas) return;
            allSiswa
                .filter(s => s.kelas === kelas)
                .forEach(s => {
                    const opt       = document.createElement('option');
                    opt.value       = s.id;
                    opt.textContent = s.nama;
                    select.appendChild(opt);
                });
        }

        function showFileName(input, targetId) {
            if (input.files && input.files[0]) {
                document.getElementById(targetId).textContent = input.files[0].name;
            }
        }
    </script>
    @endpush
</x-layout-app>
