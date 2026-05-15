<x-layout-app title="Database Prestasi" role="admin">
  
    <div class="flex-1 overflow-y-auto p-6 custom-scroll bg-[#f8fafc]">
        <div class="mx-auto space-y-5">

            {{-- PANEL FILTER & TOMBOL TAMBAH --}}
            <div class="bg-white rounded-[16px] border border-slate-200 shadow-sm p-5 flex flex-col md:flex-row justify-between items-center gap-4">
                <div>
                    <h1 class="text-lg font-bold text-slate-800">Database Prestasi</h1>
                    <p class="text-slate-500 text-xs mt-0.5">Total: <span id="countDisplay">{{ count($dataPrestasi) }}</span> Data Ditemukan</p>
                </div>
                <div class="flex items-center gap-3 w-full md:w-auto">
                    <div class="relative flex-1 md:w-[280px]">
                        <i class="fa-solid fa-magnifying-glass absolute left-3 top-2.5 text-slate-400 text-xs"></i>
                        <input type="text" id="liveSearchInput" placeholder="Cari prestasi / nama siswa..." class="w-full bg-slate-50 border border-transparent hover:border-slate-200 rounded-lg py-2 pl-9 pr-3 text-xs font-bold text-slate-700 outline-none focus:ring-2 focus:ring-blue-500/20 transition-all shadow-sm">
                    </div>
                    <button onclick="openTambahModal()" class="bg-[#2563eb] text-white px-5 py-2 rounded-lg text-xs font-bold shadow-md hover:bg-blue-700 transition flex items-center gap-2 active:scale-95">
                        <i class="fa-solid fa-plus"></i> Tambah Prestasi
                    </button>
                </div>
            </div>

            {{-- LIST PRESTASI DINAMIS --}}
            <div class="grid grid-cols-1 gap-5" id="prestasiListContainer">
               @forelse($dataPrestasi as $pres)
                <div class="bg-white rounded-2xl border border-slate-200 p-5 shadow-sm hover:shadow-md transition-all group relative overflow-hidden">
                    <div class="flex flex-col md:flex-row gap-6">

                        {{-- FOTO BUKTI --}}
                        <div class="w-full md:w-48 h-32 rounded-xl overflow-hidden bg-slate-100 flex-none border border-slate-100">
                            @php
                                $imgUrl = null;
                                if (!empty($pres['gambar'])) {
                                    // Memotong '/api' dari API_BASE_URL agar gambar tidak broken
                                    $base = rtrim(preg_replace('#/api$#', '', env('API_BASE_URL')), '/');
                                    $imgUrl = str_starts_with($pres['gambar'], 'uploads/')
                                              ? $base . '/' . $pres['gambar']
                                              : $base . '/uploads/' . $pres['gambar'];
                                }
                            @endphp

                            @if($imgUrl)
                                <img src="{{ $imgUrl }}" class="w-full h-full object-cover group-hover:scale-105 transition duration-500" onerror="this.src='https://images.unsplash.com/photo-1571260899304-425eee4c7efc?w=500&q=80'">
                            @else
                                <div class="w-full h-full flex flex-col items-center justify-center text-slate-400">
                                    <i class="fa-regular fa-image text-2xl mb-1"></i>
                                    <span class="text-[10px] font-bold uppercase">No Image</span>
                                </div>
                            @endif
                        </div>

                        {{-- INFO KONTEN --}}
                        <div class="flex-1 flex flex-col justify-between py-1">
                            <div>
                                <h3 class="text-base font-bold text-slate-800 mb-1 capitalize group-hover:text-blue-600 transition-colors">
                                    {{ $pres['nama_lomba'] ?? 'Tanpa Judul' }}
                                </h3>

                                <div class="flex flex-col gap-1.5 mt-3">
                                    <div class="flex items-center gap-2 text-slate-600">
                                        <i class="fa-solid fa-user-graduate w-4 text-slate-400 text-xs"></i>
                                        <p class="text-sm font-semibold">
                                            {{ $pres['nama_siswa'] ?? ($pres['nama'] ?? 'Siswa Unknown') }}
                                            <span class="text-slate-400 font-normal">({{ $pres['kelas'] ?? '-' }})</span>
                                        </p>
                                    </div>

                                    <div class="flex items-center gap-2 text-slate-400 text-xs font-medium">
                                        <i class="fa-regular fa-calendar w-4"></i>
                                        <span>{{ isset($pres['tanggal']) ? date('d M Y', strtotime($pres['tanggal'])) : '-' }}</span>
                                        <span class="mx-1 text-slate-200">|</span>
                                        <i class="fa-solid fa-trophy w-4 text-amber-400"></i>
                                        <span class="text-slate-600 font-bold">Juara {{ $pres['peringkat'] ?? '-' }}</span>
                                    </div>
                                </div>
                            </div>
                        </div>

                        {{-- AKSI --}}
                        <div class="flex md:flex-col gap-2">
                            {{-- PERBAIKAN: Menggunakan base64_encode agar tanda kutip JSON tidak merusak tombol HTML --}}
                            <button type="button" data-prestasi="{{ base64_encode(json_encode($pres)) }}" onclick="editPrestasi(this)" class="w-9 h-9 rounded-xl bg-indigo-50 text-indigo-600 flex items-center justify-center hover:bg-indigo-600 hover:text-white transition-all border border-indigo-100 shadow-sm">
                                <i class="fa-solid fa-pen-to-square text-sm"></i>
                            </button>
                            <button type="button" onclick="hapusPrestasi({{ $pres['id'] }})" class="w-9 h-9 rounded-xl bg-red-50 text-red-600 flex items-center justify-center hover:bg-red-600 hover:text-white transition-all border border-red-100 shadow-sm">
                                <i class="fa-solid fa-trash-can text-sm"></i>
                            </button>
                        </div>
                    </div>
                </div>
                @empty
                    <div class="bg-white rounded-3xl border-2 border-dashed border-slate-200 p-20 text-center">
                        <div class="w-16 h-16 bg-slate-50 text-slate-300 rounded-full flex items-center justify-center mx-auto mb-4"><i class="fa-solid fa-trophy text-2xl"></i></div>
                        <p class="text-slate-400 font-medium text-sm">Data prestasi tidak ditemukan untuk tahun ajaran ini.</p>
                    </div>
                @endforelse

                <div class="bg-white rounded-[24px] border border-dashed border-slate-300 p-16 text-center hidden" id="noResultRow">
                    <p class="text-slate-400 text-sm font-medium italic">Pencarian tidak menemukan hasil.</p>
                </div>

            </div>
        </div>
    </div>

    {{-- MODAL TAMBAH/EDIT PRESTASI --}}
    <div id="modalTambahPrestasi" class="fixed inset-0 bg-slate-900/40 hidden items-center justify-center z-[100] backdrop-blur-sm p-4 transition-all">
        <div class="bg-white rounded-[24px] w-full max-w-4xl shadow-2xl flex flex-col max-h-[95vh] overflow-hidden">

            {{-- HEADER MODAL --}}
            <div class="px-8 py-6 flex justify-between items-start flex-none">
                <div>
                    <h3 id="modalTitle" class="font-bold text-xl text-slate-800 mb-1">Input Prestasi Baru</h3>
                    <p class="text-slate-500 text-sm">Lengkapi data pencapaian siswa.</p>
                </div>
                <button type="button" onclick="toggleModal('modalTambahPrestasi')" class="w-8 h-8 rounded-full bg-white border border-slate-100 shadow-sm flex items-center justify-center text-slate-400 hover:text-slate-600 hover:bg-slate-50 transition">
                    <i class="fa-solid fa-xmark"></i>
                </button>
            </div>

            {{-- BODY FORM --}}
            <div class="flex-1 overflow-y-auto custom-scroll px-8 pb-4">
                <form id="formPrestasi" class="space-y-6">

                    {{-- ROW 1: KELAS & SISWA --}}
                    <div class="grid grid-cols-1 md:grid-cols-3 gap-5">
                        <div class="md:col-span-1">
                            <label class="text-[11px] font-bold text-slate-700 uppercase tracking-wide mb-2 block">Pilih Kelas</label>
                            <select id="pilihKelas" onchange="fetchSiswaByKelas(this.value)" class="w-full bg-white border border-slate-200 rounded-xl px-4 py-3 text-sm font-medium text-slate-700 outline-none focus:border-blue-500 focus:ring-2 focus:ring-blue-100 transition-all cursor-pointer">
                                <option value="">Pilih...</option>
                                @foreach(['7A', '7B', '8A', '8B', '9A', '9B'] as $kls)
                                    <option value="{{ $kls }}">{{ $kls }}</option>
                                @endforeach
                            </select>
                        </div>

                        <div class="md:col-span-2">
                            <label class="text-[11px] font-bold text-slate-700 uppercase tracking-wide mb-2 block">Cari Nama Siswa</label>
                            <select name="id_siswa" id="selectSiswa" class="w-full bg-white border border-slate-200 rounded-xl px-4 py-3 text-sm font-medium text-slate-700 outline-none focus:border-blue-500 focus:ring-2 focus:ring-blue-100 transition-all cursor-pointer disabled:bg-slate-50 disabled:text-slate-400" disabled>
                                <option value="">Pilih kelas terlebih dahulu...</option>
                            </select>
                        </div>
                    </div>

                    {{-- ROW 2: LOMBA, PENYELENGGARA, TANGGAL --}}
                    <div class="grid grid-cols-1 md:grid-cols-3 gap-5">
                        <div>
                            <label class="text-[11px] font-bold text-slate-700 uppercase tracking-wide mb-2 block">Nama Lomba</label>
                            <input type="text" name="judul_prestasi" placeholder="Contoh: OSN Informatika" class="w-full bg-white border border-slate-200 rounded-xl px-4 py-3 text-sm text-slate-700 outline-none focus:border-blue-500 focus:ring-2 focus:ring-blue-100 transition-all placeholder-slate-400" required>
                        </div>
                        <div>
                            <label class="text-[11px] font-bold text-slate-700 uppercase tracking-wide mb-2 block">Penyelenggara</label>
                            <input type="text" name="instansi" placeholder="Cth: Dinas Pendidikan" class="w-full bg-white border border-slate-200 rounded-xl px-4 py-3 text-sm text-slate-700 outline-none focus:border-blue-500 focus:ring-2 focus:ring-blue-100 transition-all placeholder-slate-400">
                        </div>
                        <div>
                            <label class="text-[11px] font-bold text-slate-700 uppercase tracking-wide mb-2 block">Tanggal</label>
                            <input type="date" name="tanggal" class="w-full bg-white border border-slate-200 rounded-xl px-4 py-3 text-sm text-slate-700 outline-none focus:border-blue-500 focus:ring-2 focus:ring-blue-100 transition-all text-slate-500 cursor-pointer" required>
                        </div>
                    </div>

                    {{-- ROW 3: KATEGORI, TINGKAT, PERINGKAT --}}
                    <div class="grid grid-cols-1 md:grid-cols-3 gap-5">
                        <div>
                            <label class="text-[11px] font-bold text-slate-700 uppercase tracking-wide mb-2 block">Kategori</label>
                            <select name="kategori" class="w-full bg-white border border-slate-200 rounded-xl px-4 py-3 text-sm font-medium text-slate-700 outline-none focus:border-blue-500 focus:ring-2 focus:ring-blue-100 transition-all">
                                <option value="akademik">Akademik</option>
                                <option value="non-akademik">Non-Akademik</option>
                            </select>
                        </div>
                        <div>
                            <label class="text-[11px] font-bold text-slate-700 uppercase tracking-wide mb-2 block">Tingkat</label>
                            <select name="tingkat" class="w-full bg-white border border-slate-200 rounded-xl px-4 py-3 text-sm font-medium text-slate-700 outline-none focus:border-blue-500 focus:ring-2 focus:ring-blue-100 transition-all">
                                <option value="kecamatan">Kecamatan</option>
                                <option value="kabupaten/kota">Kabupaten/Kota</option>
                                <option value="provinsi">Provinsi</option>
                                <option value="nasional">Nasional</option>
                                <option value="internasional">Internasional</option>
                            </select>
                        </div>
                        <div>
                            <label class="text-[11px] font-bold text-slate-700 uppercase tracking-wide mb-2 block">Peringkat</label>
                            <input type="text" name="juara" placeholder="Juara 1" class="w-full bg-white border border-slate-200 rounded-xl px-4 py-3 text-sm text-slate-700 outline-none focus:border-blue-500 focus:ring-2 focus:ring-blue-100 transition-all placeholder-slate-400">
                        </div>
                    </div>

                    {{-- ROW 4: DESKRIPSI --}}
                    <div>
                        <label class="text-[11px] font-bold text-slate-700 uppercase tracking-wide mb-2 block">Deskripsi Singkat</label>
                        <textarea name="deskripsi" rows="3" placeholder="Jelaskan sedikit tentang pencapaian..." class="w-full bg-white border border-slate-200 rounded-xl px-4 py-3 text-sm text-slate-700 outline-none focus:border-blue-500 focus:ring-2 focus:ring-blue-100 transition-all placeholder-slate-400 resize-none"></textarea>
                    </div>

                    {{-- ROW 5: UPLOAD FILE --}}
                    <div>
                        <label class="text-[11px] font-bold text-slate-700 uppercase tracking-wide mb-2 block">Upload Bukti Kejuaraan</label>
                        <div class="relative w-full">
                            <input type="file" id="file_pres" name="file_bukti" accept="image/*" class="absolute inset-0 w-full h-full opacity-0 cursor-pointer z-10" onchange="document.getElementById('fileName').textContent = this.files[0].name">
                            <div class="border-2 border-dashed border-slate-300 rounded-2xl p-8 flex flex-col items-center justify-center text-center bg-white hover:bg-slate-50 hover:border-blue-400 transition-all">
                                <div class="w-12 h-12 bg-blue-50 text-blue-500 rounded-full flex items-center justify-center mb-3 shadow-sm">
                                    <i class="fa-solid fa-cloud-arrow-up text-xl"></i>
                                </div>
                                <p class="text-sm font-semibold text-slate-700 mb-1" id="fileName">Klik untuk upload file</p>
                                <p class="text-[11px] text-slate-400 font-medium">Format: JPG, PNG (Max 2MB)</p>
                            </div>
                        </div>
                    </div>

                </form>
            </div>

            {{-- FOOTER MODAL --}}
            <div class="px-8 py-5 border-t border-slate-100 flex justify-end gap-3 flex-none bg-white">
                <button type="button" onclick="toggleModal('modalTambahPrestasi')" class="px-6 py-2.5 rounded-xl border border-slate-200 text-sm font-bold text-slate-600 hover:bg-slate-50 hover:text-slate-800 transition-all">
                    Batal
                </button>
                <button type="button" onclick="handleSimpanPrestasi()" class="px-6 py-2.5 bg-[#2563eb] text-white rounded-xl text-sm font-bold shadow-md shadow-blue-500/30 hover:bg-blue-700 hover:shadow-lg hover:shadow-blue-500/40 transition-all active:scale-95">
                    Simpan Data
                </button>
            </div>

        </div>
    </div>

   <script>
    // 1. FILTER PENCARIAN REALTIME
    document.getElementById('liveSearchInput').addEventListener('keyup', function() {
        let filter = this.value.toLowerCase();
        let cards = document.querySelectorAll('#prestasiListContainer > div.bg-white');
        let count = 0;

        cards.forEach(card => {
            let text = card.innerText.toLowerCase();
            if(text.includes(filter)) {
                card.style.display = '';
                count++;
            } else {
                card.style.display = 'none';
            }
        });

        document.getElementById('countDisplay').innerText = count;
        document.getElementById('noResultRow').style.display = count === 0 ? 'block' : 'none';
    });

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
        document.getElementById('formPrestasi').reset();
        const s = document.getElementById('selectSiswa');
        s.innerHTML = '<option value="">Pilih kelas terlebih dahulu...</option>';
        s.disabled = true;
        document.getElementById('modalTitle').textContent = 'Input Prestasi Baru';
        document.getElementById('fileName').textContent = 'Klik untuk upload file';
        toggleModal('modalTambahPrestasi');
    }

    // 2. FUNGSI FETCH SISWA (DIPERBAIKI)
    async function fetchSiswaByKelas(kelas, idSiswaTerpilih = null) {
        const selectSiswa = document.getElementById('selectSiswa');
        if (!kelas) {
            selectSiswa.innerHTML = '<option value="">Pilih kelas...</option>';
            selectSiswa.disabled = true;
            return;
        }

        selectSiswa.innerHTML = '<option>Memuat data...</option>';
        selectSiswa.disabled = true;

        try {
            const res = await fetch(`/admin/api-siswa-by-kelas?kelas=${kelas}`);
            const result = await res.json();
            
            // Handle jika result berupa {data: [...]} atau langsung [...]
            const list = result.data || result;

            selectSiswa.innerHTML = '<option value="">-- Pilih Siswa --</option>';
            if (Array.isArray(list)) {
                list.forEach(s => {
                    // Gunakan == (loose equality) untuk mencocokkan string vs number
                    const selected = (idSiswaTerpilih != null && s.id == idSiswaTerpilih) ? 'selected' : '';
                    selectSiswa.innerHTML += `<option value="${s.id}" ${selected}>${s.nama}</option>`;
                });
                selectSiswa.disabled = false;
            }
        } catch (e) {
            selectSiswa.innerHTML = '<option value="">Gagal memuat data</option>';
        }
    }

    // 3. FUNGSI EDIT (Hanya satu versi saja agar tidak bentrok)
    function editPrestasi(btn) {
        // Decode data dari Base64 ke JSON
        const data = JSON.parse(atob(btn.getAttribute('data-prestasi')));

        isEdit = true;
        editId = data.id;

        const form = document.getElementById('formPrestasi');
        form.judul_prestasi.value = data.nama_lomba || '';
        form.instansi.value       = data.penyelenggara || '';
        
        // Format tanggal ISO (2025-05-09T00:00:00Z) ke HTML date (2025-05-09)
        if(data.tanggal) {
            form.tanggal.value = data.tanggal.split('T')[0];
        }

        form.kategori.value = (data.kategori || 'akademik').toLowerCase();
        
        // Normalisasi tingkat kabupaten/kota
        let t = (data.tingkat || 'kecamatan').toLowerCase();
        if(t.includes('kabupaten')) t = 'kabupaten/kota';
        form.tingkat.value = t;

        form.juara.value     = data.peringkat || '';
        form.deskripsi.value = data.keterangan || '';

        // Load Siswa berdasarkan kelas yang ada di data prestasi
        if(data.kelas) {
            document.getElementById('pilihKelas').value = data.kelas;
            // Penting: Kirimkan id_siswa supaya otomatis terpilih (selected)
            fetchSiswaByKelas(data.kelas, data.id_siswa);
        }

        document.getElementById('modalTitle').textContent = 'Edit Data Prestasi';
        document.getElementById('fileName').textContent = data.gambar ? 'Ganti file bukti (Opsional)' : 'Klik untuk upload file';
        
        toggleModal('modalTambahPrestasi');
    }

    // 4. HANDLER SIMPAN
    async function handleSimpanPrestasi() {
        const formEl = document.getElementById('formPrestasi');
        const fd = new FormData(formEl);
        const url = isEdit ? `/admin/api-update-prestasi/${editId}` : `/admin/api-simpan-prestasi`;

        Swal.fire({title: 'Sedang memproses...', didOpen: () => Swal.showLoading(), allowOutsideClick: false});

        try {
            const res = await fetch(url, {
                method: 'POST',
                headers: { 'X-CSRF-TOKEN': '{{ csrf_token() }}' },
                body: fd
            });
            const result = await res.json();

            if (res.ok) {
                Swal.fire('Sukses!', 'Data berhasil disimpan.', 'success').then(() => location.reload());
            } else {
                Swal.fire('Gagal', result.message || 'Terjadi kesalahan sistem', 'error');
            }
        } catch (e) {
            Swal.fire('Error', 'Koneksi ke server gagal', 'error');
        }
    }

    // 5. HANDLER HAPUS
    function hapusPrestasi(id) {
        Swal.fire({
            title: 'Yakin hapus data ini?',
            text: "Data yang dihapus tidak bisa dikembalikan!",
            icon: 'warning',
            showCancelButton: true,
            confirmButtonColor: '#d33',
            confirmButtonText: 'Ya, Hapus'
        }).then(async (r) => {
            if(r.isConfirmed) {
                Swal.fire({title: 'Menghapus...', didOpen: () => Swal.showLoading()});
                const res = await fetch(`/admin/api-hapus-prestasi/${id}`, {
                    method: 'DELETE',
                    headers: { 'X-CSRF-TOKEN': '{{ csrf_token() }}' }
                });
                if(res.ok) location.reload();
            }
        });
    }
</script>
</x-layout-app>
