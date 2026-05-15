<x-layout-app title="Approval Perizinan" :role="$role">

    <div class="flex-1 overflow-y-auto px-4 pb-4 pt-0 lg:px-6 lg:pb-6 custom-scroll bg-[#f8fafc]">

        @if(session('success'))
            <div class="mb-3 bg-green-50 border border-green-200 text-green-700 px-4 py-2.5 rounded-lg text-xs font-bold flex items-center gap-2">
                <i class="fa-solid fa-circle-check"></i> {{ session('success') }}
            </div>
        @endif
        @if(session('error'))
            <div class="mb-3 bg-red-50 border border-red-200 text-red-600 px-4 py-2.5 rounded-lg text-xs font-bold flex items-center gap-2">
                <i class="fa-solid fa-circle-exclamation"></i> {{ session('error') }}
            </div>
        @endif

        @if ($errors->any())
            <div class="mb-3 bg-red-50 border border-red-200 text-red-600 px-4 py-2.5 rounded-lg text-xs font-bold">
                <p class="font-bold mb-1"><i class="fa-solid fa-triangle-exclamation"></i> Form gagal disubmit karena:</p>
                <ul class="list-disc pl-5">
                    @foreach ($errors->all() as $error)
                        <li>{{ $error }}</li>
                    @endforeach
                </ul>
            </div>
        @endif

        <div class="w-full space-y-4">
            <div class="bg-white p-4 lg:p-5 rounded-2xl shadow-sm border border-slate-200">

                {{-- PERBAIKAN: Menggunakan md:flex-row agar selalu sejajar berdampingan --}}
                <div class="flex flex-col md:flex-row justify-between items-start md:items-center gap-4 mb-5">
                    <div>
                        <h3 class="font-bold text-base text-slate-800">Pusat Approval Izin</h3>
                        <p class="text-[11px] text-slate-500 mt-0.5">Verifikasi izin keluar/pulang siswa.</p>
                    </div>

                    {{-- Form Filter AJAX --}}
                    <form id="filterFormIzin" method="GET" action="{{ route('bk.perizinan') }}"
                          class="flex flex-wrap sm:flex-nowrap items-center gap-2 w-full md:w-auto">

                        <div class="relative flex-1 sm:w-32">
                            <select name="kelas"
                                class="auto-submit w-full appearance-none bg-white border border-slate-200 rounded-lg py-1.5 pl-3 pr-8 text-[11px] font-bold text-slate-600 outline-none focus:ring-2 focus:ring-blue-200 cursor-pointer h-[32px]">
                                <option value="">Semua Kelas</option>
                                @foreach($kelasList as $kelas)
                                    <option value="{{ $kelas }}" {{ request('kelas') == $kelas ? 'selected' : '' }}>{{ $kelas }}</option>
                                @endforeach
                            </select>
                            <i class="fa-solid fa-chevron-down absolute right-3 top-1/2 -translate-y-1/2 text-[9px] text-slate-400 pointer-events-none"></i>
                        </div>

                        <div class="relative flex-1 sm:w-36">
                            <input type="date" name="tanggal"
                                value="{{ request('tanggal') }}"
                                class="auto-submit w-full bg-white border border-slate-200 rounded-lg py-1.5 px-3 text-[11px] font-bold text-slate-600 outline-none focus:ring-2 focus:ring-blue-200 h-[32px]" />
                        </div>

                        <button type="button" onclick="openModal('modalAddIzin')"
                            class="bg-[#2563eb] hover:bg-blue-700 text-white px-4 rounded-lg text-[11px] font-bold shadow-sm transition flex items-center justify-center gap-1.5 w-full sm:w-auto h-[32px] shrink-0">
                            <i class="fa-solid fa-plus text-[10px]"></i> Buat Izin Baru
                        </button>

                        @if(request('kelas') || request('tanggal'))
                            <button type="button" onclick="window.location='{{ route('bk.perizinan') }}'"
                                class="px-3 bg-white border border-slate-200 text-slate-500 rounded-lg text-[11px] font-bold hover:bg-slate-50 transition flex items-center justify-center h-[32px] shrink-0">
                                Reset
                            </button>
                        @endif
                    </form>
                </div>

                {{-- BUNGKUS DENGAN AJAX ID --}}
                <div id="table-container" class="border border-slate-200 rounded-xl overflow-hidden transition-opacity duration-300">
                    <div class="overflow-x-auto custom-scroll">
                        <table class="w-full text-left text-sm whitespace-nowrap min-w-max">
                            <thead class="bg-slate-50 border-b border-slate-100">
                                <tr>
                                    <th class="py-3 pl-5 pr-3 text-[9px] font-bold text-slate-400 uppercase tracking-widest">Tanggal</th>
                                    <th class="py-3 px-3 text-[9px] font-bold text-slate-400 uppercase tracking-widest">Siswa</th>
                                    <th class="py-3 px-3 text-[9px] font-bold text-slate-400 uppercase tracking-widest">Status</th>
                                    <th class="py-3 px-3 text-[9px] font-bold text-slate-400 uppercase tracking-widest">Jam Pelajaran</th>
                                    <th class="py-3 px-3 text-[9px] font-bold text-slate-400 uppercase tracking-widest">Alasan</th>
                                    <th class="py-3 px-3 text-[9px] font-bold text-slate-400 uppercase tracking-widest">Bukti</th>
                                    <th class="py-3 pr-5 pl-3 text-[9px] font-bold text-slate-400 uppercase tracking-widest text-right">Aksi</th>
                                </tr>
                            </thead>
                            <tbody class="divide-y divide-slate-50">
                                @forelse($perizinan as $izin)
                                    @php
                                        $tglRaw = $izin['tanggal'] ?? $izin['created_at'] ?? null;
                                        $tgl    = $tglRaw ? \Carbon\Carbon::parse($tglRaw)->timezone('Asia/Jakarta')->translatedFormat('d M Y') : '-';
                                        $tglEdit = $tglRaw ? \Carbon\Carbon::parse($tglRaw)->timezone('Asia/Jakarta')->format('Y-m-d') : '';
                                        $statusLabel = match($izin['status'] ?? '') {
                                            'izin'  => ['label' => 'Izin',  'bg' => 'bg-blue-50',   'text' => 'text-blue-600'],
                                            'sakit' => ['label' => 'Sakit', 'bg' => 'bg-yellow-50', 'text' => 'text-yellow-600'],
                                            'alpha' => ['label' => 'Alpha', 'bg' => 'bg-red-50',    'text' => 'text-red-500'],
                                            default => ['label' => ucfirst($izin['status'] ?? '-'), 'bg' => 'bg-slate-100', 'text' => 'text-slate-500'],
                                        };
                                        $jamMulai   = $izin['jam_mulai']   ?? null;
                                        $jamSelesai = $izin['jam_selesai'] ?? null;
                                    @endphp
                                    <tr class="hover:bg-slate-50 transition group">
                                        <td class="py-3 pl-5 pr-3 text-[11px] text-slate-500 font-mono">{{ $tgl }}</td>
                                        <td class="py-3 px-3 font-bold text-slate-800 text-[12px] group-hover:text-[#2563eb] transition">
                                            {{ $izin['nama_siswa'] ?? '-' }}
                                            <span class="text-slate-400 font-normal text-[10px] ml-1">({{ $izin['kelas'] ?? '-' }})</span>
                                        </td>
                                        <td class="py-3 px-3">
                                            <span class="{{ $statusLabel['bg'] }} {{ $statusLabel['text'] }} px-2 py-0.5 rounded text-[9px] font-bold uppercase tracking-wider inline-block">
                                                {{ $statusLabel['label'] }}
                                            </span>
                                        </td>
                                        <td class="py-3 px-3">
                                            @if($jamMulai && $jamSelesai)
                                                <div class="inline-flex items-center gap-1.5 bg-indigo-50 text-indigo-700 px-2 py-1 rounded text-[10px] font-bold">
                                                    Jam ke-{{ $jamMulai }}
                                                    <span class="text-indigo-300 font-light mx-0.5">s/d</span>
                                                    Jam ke-{{ $jamSelesai }}
                                                </div>
                                            @elseif($jamMulai)
                                                <div class="inline-flex items-center gap-1.5 bg-indigo-50 text-indigo-700 px-2 py-1 rounded text-[10px] font-bold">
                                                    Jam ke-{{ $jamMulai }}
                                                </div>
                                            @else
                                                <span class="text-slate-300 text-[11px]">—</span>
                                            @endif
                                        </td>
                                        <td class="py-3 px-3 text-[11px] text-slate-600 max-w-[150px] truncate">{{ $izin['keterangan'] ?? '-' }}</td>
                                        <td class="py-3 px-3">
                                            @if(!empty($izin['gambar']))
                                                <button type="button" onclick='openEditModal({{ json_encode($izin) }}, "{{ $tglEdit }}")'
                                                   class="text-[#2563eb] text-[11px] font-bold underline hover:text-blue-800 flex items-center gap-1 cursor-pointer">
                                                    <i class="fa-regular fa-image"></i> Preview
                                                </button>
                                            @else
                                                <span class="text-slate-300 text-[11px]">—</span>
                                            @endif
                                        </td>
                                        <td class="py-3 pr-5 pl-3 text-right">
                                            <div class="flex justify-end gap-1.5">
                                                <button type="button"
                                                    onclick='openEditModal({{ json_encode($izin) }}, "{{ $tglEdit }}")'
                                                    class="w-7 h-7 rounded-lg bg-slate-50 text-slate-400 hover:bg-slate-100 hover:text-[#2563eb] transition flex items-center justify-center text-[11px]">
                                                    <i class="fa-regular fa-pen-to-square"></i>
                                                </button>
                                                <button type="button"
                                                    onclick="confirmDelete({{ $izin['id'] }})"
                                                    class="w-7 h-7 rounded-lg bg-slate-50 text-slate-400 hover:bg-slate-100 hover:text-[#ef4444] transition flex items-center justify-center text-[11px]">
                                                    <i class="fa-regular fa-trash-can"></i>
                                                </button>
                                            </div>
                                        </td>
                                    </tr>
                                @empty
                                    <tr>
                                        <td colspan="7" class="py-12 text-center text-slate-400 text-xs">
                                            <i class="fa-regular fa-folder-open text-3xl mb-2 block text-slate-300"></i>
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
    <div id="modalAddIzin" style="display:none" class="fixed inset-0 bg-black/50 items-center justify-center z-50 backdrop-blur-sm">
        <div class="bg-white rounded-2xl w-full max-w-lg shadow-2xl p-5 lg:p-6 mx-4 max-h-[90vh] overflow-y-auto custom-scroll">
            <div class="flex justify-between items-center mb-5 pb-3 border-b border-slate-100">
                <h3 class="font-bold text-base text-slate-800">Formulir Izin Siswa</h3>
                <button type="button" onclick="closeModal('modalAddIzin')" class="text-slate-400 hover:text-red-500 transition">
                    <i class="fa-solid fa-xmark text-lg"></i>
                </button>
            </div>
            <form method="POST" action="{{ route('bk.perizinan.store') }}" enctype="multipart/form-data">
                @csrf
                <div class="space-y-4">
                    <div>
                        <label class="block text-[10px] font-bold text-slate-500 uppercase tracking-wide mb-1.5">Tanggal Izin</label>
                        <input type="date" name="tanggal" required
                            class="w-full border border-slate-200 rounded-lg px-3 py-2 text-[11px] outline-none focus:ring-2 focus:ring-blue-300 text-slate-700">
                    </div>

                    <div>
                        <label class="block text-[10px] font-bold text-slate-500 uppercase tracking-wide mb-1.5">
                            <i class="fa-solid fa-book-open mr-1 text-indigo-400"></i> Jam Pelajaran
                        </label>
                        <div class="grid grid-cols-2 gap-3">
                            <div>
                                <label class="block text-[9px] text-slate-400 font-semibold mb-1 pl-1">Dari Jam ke-</label>
                                <div class="relative">
                                    <select name="jam_mulai" required
                                        class="w-full appearance-none border border-slate-200 rounded-lg px-3 py-2 text-[11px] outline-none focus:ring-2 focus:ring-blue-300 bg-white text-slate-700 pr-8">
                                        <option value="">Pilih</option>
                                        @for($i = 1; $i <= 10; $i++)
                                            <option value="{{ $i }}">Jam ke-{{ $i }}</option>
                                        @endfor
                                    </select>
                                    <i class="fa-solid fa-chevron-down absolute right-3 top-1/2 -translate-y-1/2 text-[9px] text-slate-400 pointer-events-none"></i>
                                </div>
                            </div>
                            <div>
                                <label class="block text-[9px] text-slate-400 font-semibold mb-1 pl-1">Sampai Jam ke-</label>
                                <div class="relative">
                                    <select name="jam_selesai" required
                                        class="w-full appearance-none border border-slate-200 rounded-lg px-3 py-2 text-[11px] outline-none focus:ring-2 focus:ring-blue-300 bg-white text-slate-700 pr-8">
                                        <option value="">Pilih</option>
                                        @for($i = 1; $i <= 10; $i++)
                                            <option value="{{ $i }}">Jam ke-{{ $i }}</option>
                                        @endfor
                                    </select>
                                    <i class="fa-solid fa-chevron-down absolute right-3 top-1/2 -translate-y-1/2 text-[9px] text-slate-400 pointer-events-none"></i>
                                </div>
                            </div>
                        </div>
                        <p class="text-[9px] text-slate-400 mt-1 pl-1">Contoh: izin dari jam ke-3 sampai jam ke-5</p>
                    </div>

                    <div class="grid grid-cols-2 gap-4">
                        <div>
                            <label class="block text-[10px] font-bold text-slate-500 uppercase tracking-wide mb-1.5">Kelas</label>
                            <select id="addKelasSelect" onchange="filterSiswaByKelas(this.value, 'addSiswaSelect')"
                                class="w-full border border-slate-200 rounded-lg px-3 py-2 text-[11px] outline-none focus:ring-2 focus:ring-blue-300 bg-white">
                                <option value="">Pilih Kelas</option>
                                @foreach($kelasList as $k)
                                    <option value="{{ $k }}">{{ $k }}</option>
                                @endforeach
                            </select>
                        </div>
                        <div>
                            <label class="block text-[10px] font-bold text-slate-500 uppercase tracking-wide mb-1.5">Siswa</label>
                            <select id="addSiswaSelect" name="id_siswa" required
                                class="w-full border border-slate-200 rounded-lg px-3 py-2 text-[11px] outline-none focus:ring-2 focus:ring-blue-300 bg-white">
                                <option value="">Pilih Siswa</option>
                            </select>
                        </div>
                    </div>
                    <div>
                        <label class="block text-[10px] font-bold text-slate-500 uppercase tracking-wide mb-1.5">Status</label>
                        <select name="status" required
                            class="w-full border border-slate-200 rounded-lg px-3 py-2 text-[11px] outline-none focus:ring-2 focus:ring-blue-300 bg-white">
                            <option value="">Pilih Status</option>
                            <option value="izin">Izin</option>
                            <option value="sakit">Sakit</option>
                        </select>
                    </div>
                    <div>
                        <label class="block text-[10px] font-bold text-slate-500 uppercase tracking-wide mb-1.5">Alasan / Keterangan</label>
                        <textarea name="keterangan" rows="2"
                            class="w-full border border-slate-200 rounded-lg px-3 py-2 text-[11px] outline-none focus:ring-2 focus:ring-blue-300 resize-none"
                            placeholder="Tuliskan alasan izin..."></textarea>
                    </div>
                    <div>
                        <label class="block text-[10px] font-bold text-slate-500 uppercase tracking-wide mb-1.5">Upload Bukti</label>
                        <label class="border-2 border-dashed border-slate-300 rounded-lg p-4 text-center hover:bg-slate-50 hover:border-blue-400 transition cursor-pointer block">
                            <input type="file" name="gambar" accept=".jpg,.jpeg,.png,.pdf" class="hidden"
                                onchange="showFileName(this, 'addFileName')">
                            <i class="fa-solid fa-cloud-arrow-up text-2xl text-slate-300 mb-1 block"></i>
                            <p id="addFileName" class="text-[11px] text-slate-600 font-bold">Klik untuk upload file</p>
                            <p class="text-[9px] text-slate-400 mt-0.5">Format: JPG, PNG, PDF (Maks 2MB)</p>
                        </label>
                    </div>
                    <div class="flex justify-end gap-2 pt-3 border-t border-slate-100">
                        <button type="button" onclick="closeModal('modalAddIzin')"
                            class="px-4 py-2 rounded-lg border border-slate-200 text-slate-500 text-[11px] font-bold hover:bg-slate-50 transition">Batal</button>
                        <button type="submit"
                            class="px-5 py-2 rounded-lg bg-[#2563eb] text-white text-[11px] font-bold hover:bg-blue-700 shadow-sm transition">Simpan</button>
                    </div>
                </div>
            </form>
        </div>
    </div>

    {{-- ===== MODAL EDIT / PREVIEW ===== --}}
    <div id="modalEditIzin" style="display:none" class="fixed inset-0 bg-black/50 items-center justify-center z-50 backdrop-blur-sm">
        <div class="bg-white rounded-2xl w-full max-w-lg shadow-2xl p-5 lg:p-6 mx-4 max-h-[90vh] overflow-y-auto custom-scroll">
            <div class="flex justify-between items-center mb-5 pb-3 border-b border-slate-100">
                <h3 class="font-bold text-base text-slate-800">Edit / Verifikasi Izin</h3>
                <button type="button" onclick="closeModal('modalEditIzin')" class="text-slate-400 hover:text-red-500 transition">
                    <i class="fa-solid fa-xmark text-lg"></i>
                </button>
            </div>

            <form id="editForm" method="POST" action="" enctype="multipart/form-data">
                @csrf
                <div class="space-y-4">
                    <div class="grid grid-cols-2 gap-4">
                        <div>
                            <label class="block text-[10px] font-bold text-slate-500 uppercase tracking-wide mb-1.5">Kelas</label>
                            <input type="text" id="editKelas"
                                class="w-full border border-slate-200 rounded-lg px-3 py-2 text-[11px] bg-slate-50 outline-none text-slate-500 cursor-not-allowed font-bold" disabled>
                        </div>
                        <div>
                            <label class="block text-[10px] font-bold text-slate-500 uppercase tracking-wide mb-1.5">Siswa</label>
                            <input type="text" id="editNama"
                                class="w-full border border-slate-200 rounded-lg px-3 py-2 text-[11px] bg-slate-50 outline-none text-slate-500 cursor-not-allowed font-bold" disabled>
                        </div>
                    </div>
                    <div>
                        <label class="block text-[10px] font-bold text-slate-500 uppercase tracking-wide mb-1.5">Status</label>
                        <select name="status" id="editStatus"
                            class="w-full border border-slate-200 rounded-lg px-3 py-2 text-[11px] outline-none focus:ring-2 focus:ring-blue-300 bg-white">
                            <option value="izin">Izin</option>
                            <option value="sakit">Sakit</option>
                            <option value="alpha">Alpha</option>
                        </select>
                    </div>
                    <div>
                        <label class="block text-[10px] font-bold text-slate-500 uppercase tracking-wide mb-1.5">Alasan</label>
                        <input type="text" name="keterangan" id="editKeterangan"
                            class="w-full border border-slate-200 rounded-lg px-3 py-2 text-[11px] outline-none focus:ring-2 focus:ring-blue-300">
                    </div>
                    <div>
                        <label class="block text-[10px] font-bold text-slate-500 uppercase tracking-wide mb-1.5">Tanggal</label>
                        <input type="date" name="tanggal" id="editTanggal"
                            class="w-full border border-slate-200 rounded-lg px-3 py-2 text-[11px] outline-none focus:ring-2 focus:ring-blue-300 text-slate-700">
                    </div>

                    <div>
                        <label class="block text-[10px] font-bold text-slate-500 uppercase tracking-wide mb-1.5">
                            <i class="fa-solid fa-book-open mr-1 text-indigo-400"></i> Jam Pelajaran
                        </label>
                        <div class="grid grid-cols-2 gap-3">
                            <div>
                                <label class="block text-[9px] text-slate-400 font-semibold mb-1 pl-1">Dari Jam ke-</label>
                                <div class="relative">
                                    <select name="jam_mulai" id="editJamMulai"
                                        class="w-full appearance-none border border-slate-200 rounded-lg px-3 py-2 text-[11px] outline-none focus:ring-2 focus:ring-blue-300 bg-white text-slate-700 pr-8">
                                        <option value="">Pilih</option>
                                        @for($i = 1; $i <= 10; $i++)
                                            <option value="{{ $i }}">Jam ke-{{ $i }}</option>
                                        @endfor
                                    </select>
                                    <i class="fa-solid fa-chevron-down absolute right-3 top-1/2 -translate-y-1/2 text-[9px] text-slate-400 pointer-events-none"></i>
                                </div>
                            </div>
                            <div>
                                <label class="block text-[9px] text-slate-400 font-semibold mb-1 pl-1">Sampai Jam ke-</label>
                                <div class="relative">
                                    <select name="jam_selesai" id="editJamSelesai"
                                        class="w-full appearance-none border border-slate-200 rounded-lg px-3 py-2 text-[11px] outline-none focus:ring-2 focus:ring-blue-300 bg-white text-slate-700 pr-8">
                                        <option value="">Pilih</option>
                                        @for($i = 1; $i <= 10; $i++)
                                            <option value="{{ $i }}">Jam ke-{{ $i }}</option>
                                        @endfor
                                    </select>
                                    <i class="fa-solid fa-chevron-down absolute right-3 top-1/2 -translate-y-1/2 text-[9px] text-slate-400 pointer-events-none"></i>
                                </div>
                            </div>
                        </div>
                    </div>

                    {{-- AREA PREVIEW GAMBAR INLINE --}}
                    <div>
                        <label class="block text-[10px] font-bold text-slate-500 uppercase tracking-wide mb-1.5">Bukti Terlampir</label>
                        <div id="editBuktiBox" class="border border-slate-200 rounded-lg p-3 bg-slate-50 mb-3" style="display:none">
                            <p class="text-[9px] font-bold text-slate-400 uppercase tracking-wider mb-2 text-center">Preview Bukti Saat Ini</p>
                            <div id="editBuktiImageWrapper" class="w-full flex justify-center bg-gray-200/50 rounded p-2">
                                <img id="editBuktiImage" src="" alt="Bukti Izin"
                                    class="max-h-40 object-contain rounded shadow-sm"
                                    onerror="this.parentElement.style.display='none'">
                            </div>
                            <div id="editBuktiPdfWrapper" class="p-4 flex-col items-center justify-center bg-white rounded border border-slate-200 hidden">
                                <i class="fa-solid fa-file-pdf text-3xl text-red-500 mb-2"></i>
                                <p id="editBuktiPdfName" class="text-[11px] font-bold text-slate-700 text-center break-all px-2"></p>
                                <a id="editBuktiPdfLink" href="" target="_blank" class="mt-2 px-3 py-1 bg-red-50 text-red-600 rounded-lg text-[10px] font-bold hover:bg-red-100 transition">
                                    Buka File PDF
                                </a>
                            </div>
                        </div>
                        <p id="editNoBukti" class="text-slate-400 text-[11px] mb-3 italic">Tidak ada bukti terlampir.</p>

                        <label class="block text-[10px] font-bold text-slate-500 uppercase tracking-wide mt-2 mb-1.5">Ubah / Upload Bukti Baru</label>
                        <label class="border-2 border-dashed border-slate-300 rounded-lg p-3 text-center hover:bg-slate-50 hover:border-blue-400 transition cursor-pointer block">
                            <input type="file" name="gambar" accept=".jpg,.jpeg,.png,.pdf" class="hidden"
                                onchange="showFileName(this, 'editNewFileName')">
                            <i class="fa-solid fa-upload text-lg text-slate-300 mb-1 block"></i>
                            <p id="editNewFileName" class="text-[11px] text-slate-600 font-bold">Pilih file baru</p>
                        </label>
                    </div>

                    <div class="flex justify-end gap-2 pt-3 border-t border-slate-100">
                        <button type="button" onclick="closeModal('modalEditIzin')"
                            class="px-4 py-2 rounded-lg border border-slate-200 text-slate-500 text-[11px] font-bold hover:bg-slate-50 transition">Tutup</button>
                        <button type="submit"
                            class="px-5 py-2 rounded-lg bg-[#2563eb] text-white text-[11px] font-bold hover:bg-blue-700 shadow-sm transition">Simpan Perubahan</button>
                    </div>
                </div>
            </form>
        </div>
    </div>

    <form id="deleteForm" method="POST" action="" class="hidden">@csrf</form>

    @push('scripts')
    <script>
        const allSiswa   = @json($siswaList);
        const staticBase = "{{ rtrim(preg_replace('#/api$#', '', env('API_BASE_URL')), '/') }}";

        // ── SCRIPT AJAX EVENT DELEGATION (ANTI BERKEDIP RESET) ─────────────────
        document.addEventListener('DOMContentLoaded', () => {
            document.addEventListener('change', e => {
                if (e.target.matches('.auto-submit')) {
                    const form = e.target.closest('form');
                    if (form) executeAjaxFilter(form);
                }
            });

            document.addEventListener('submit', e => {
                const form = e.target;
                if (form.id === 'filterFormIzin') {
                    e.preventDefault();
                    executeAjaxFilter(form);
                }
            });

            document.addEventListener('click', e => {
                const link = e.target.closest('.ajax-link');
                if (link) {
                    e.preventDefault();
                    reloadTableData(link.href);
                }
            });
        });

        function executeAjaxFilter(form) {
            const url = new URL(form.action);
            const params = new URLSearchParams(window.location.search);
            const formData = new FormData(form);

            for (const [key, value] of formData.entries()) {
                if (value) params.set(key, value);
                else params.delete(key);
            }

            reloadTableData(url.pathname + '?' + params.toString());
        }

        async function reloadTableData(url) {
            const container = document.getElementById('table-container');
            container.style.opacity = '0.5';
            container.style.pointerEvents = 'none';
            try {
                const res = await fetch(url, { headers: { 'X-Requested-With': 'XMLHttpRequest' } });
                const html = await res.text();
                // FIX: Menggunakan DOMParser bawaan JavaScript murni
                const doc = new DOMParser().parseFromString(html, 'text/html');
                const newContainer = doc.getElementById('table-container');

                if (newContainer) {
                    container.innerHTML = newContainer.innerHTML;
                    window.history.pushState({}, '', url);
                } else {
                    window.location.href = url; // Fallback jika gagal parse
                }
            } catch (e) {
                console.error("Gagal reload data:", e);
            } finally {
                container.style.opacity = '1';
                container.style.pointerEvents = 'auto';
            }
        }

        // ── FUNGSI MODAL & SWEETALERT BAWAAN ──────────────────────────
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

        function openEditModal(izin, tglEdit){
            document.getElementById('editForm').action = `/bk/perizinan/${izin.id}/update`;
            document.getElementById('editKelas').value      = izin.kelas      ?? '';
            document.getElementById('editNama').value       = izin.nama_siswa ?? '';
            document.getElementById('editKeterangan').value = izin.keterangan ?? '';
            document.getElementById('editStatus').value     = izin.status     ?? 'izin';

            document.getElementById('editTanggal').value = tglEdit;

            // jam_mulai & jam_selesai dari API adalah integer (1–10)
            document.getElementById('editJamMulai').value   = izin.jam_mulai   ?? '';
            document.getElementById('editJamSelesai').value = izin.jam_selesai ?? '';

            const buktiBox = document.getElementById('editBuktiBox');
            const noBukti  = document.getElementById('editNoBukti');
            const imgWrap  = document.getElementById('editBuktiImageWrapper');
            const imgEl    = document.getElementById('editBuktiImage');
            const pdfWrap  = document.getElementById('editBuktiPdfWrapper');
            const pdfName  = document.getElementById('editBuktiPdfName');
            const pdfLink  = document.getElementById('editBuktiPdfLink');

            if (izin.gambar) {
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
