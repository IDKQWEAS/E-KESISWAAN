<x-layout-app title="Biodata Lengkap" :role="$role">

    {{-- Spasi luar diperkecil agar tidak terlalu jauh dari topbar --}}
    <div class="flex-1 overflow-y-auto p-4 lg:p-6 custom-scroll bg-[#f8fafc]">

        {{-- Menggunakan flex-col dan gap-4 agar jarak antar elemen konsisten dengan halaman lain --}}
        <div class="w-full flex flex-col gap-4">

            {{-- Header & Actions --}}
            <div class="bg-white p-4 lg:p-5 rounded-[20px] shadow-sm border border-slate-200 flex flex-col xl:flex-row justify-between items-start xl:items-center gap-4">

                {{-- Kiri: Judul --}}
                <div class="flex-shrink-0">
                    <h3 class="font-bold text-lg lg:text-[20px] text-slate-800">Biodata Siswa</h3>
                    <p class="text-[11px] lg:text-xs text-slate-500 mt-0.5">Kelola data lengkap siswa.</p>
                </div>

                {{-- Kanan: Filter & Tombol Aksi --}}
                {{-- Dibuat flex-nowrap agar STRIKTLI 1 baris. Jika tidak muat, akan scroll horizontal (overflow-x-auto) --}}
                <div class="flex items-center gap-2 overflow-x-auto custom-scroll w-full xl:w-auto pb-2 xl:pb-0 flex-nowrap">

                    {{-- Filter Kelas --}}
                    <form method="GET" action="{{ route('bk.biodata') }}" class="flex items-center gap-2 flex-shrink-0 flex-nowrap">
                        <div class="relative flex-shrink-0 w-32 md:w-36">
                            <select name="kelas" onchange="this.form.submit()"
                                class="w-full appearance-none bg-white border border-slate-200 rounded-xl py-2 pl-3 pr-8 text-xs font-bold text-slate-700 outline-none focus:ring-2 focus:ring-blue-500/20 cursor-pointer transition-all h-[36px]">
                                <option value="">Semua Kelas</option>
                                @foreach($kelasList as $k)
                                    <option value="{{ $k }}" {{ $kelasAktif === $k ? 'selected' : '' }}>{{ $k }}</option>
                                @endforeach
                            </select>
                            <i class="fa-solid fa-chevron-down absolute right-3 top-1/2 -translate-y-1/2 text-[10px] text-slate-400 pointer-events-none"></i>
                        </div>

                        <input type="text" name="search" value="{{ $search }}" placeholder="Cari nama / NISN..."
                            class="border border-slate-200 rounded-xl py-2 px-3 text-xs outline-none focus:ring-2 focus:ring-blue-300 w-36 md:w-40 flex-shrink-0 h-[36px]">

                        <button type="submit" class="bg-slate-100 hover:bg-slate-200 text-slate-600 px-3 py-2 rounded-xl text-xs font-bold transition flex items-center justify-center flex-shrink-0 h-[36px] w-[36px]">
                            <i class="fa-solid fa-magnifying-glass"></i>
                        </button>
                    </form>

                    <div class="w-px h-6 bg-slate-200 mx-1 flex-shrink-0"></div>

                    {{-- Download Template --}}
                    <a href="{{ route('bk.biodata.template') }}"
                        class="flex-shrink-0 whitespace-nowrap bg-[#fefce8] border-2 border-dashed border-[#facc15] text-[#ca8a04] px-3 py-2 rounded-xl text-xs font-bold hover:bg-[#fef9c3] transition flex items-center gap-1.5 h-[36px]">
                        <div class="w-4 h-4 rounded-full bg-[#facc15] text-white flex items-center justify-center text-[9px] font-bold">1</div>
                        Template <i class="fa-solid fa-file-excel"></i>
                    </a>

                    {{-- Import Excel --}}
                    <button onclick="openModal('modalImport')"
                        class="flex-shrink-0 whitespace-nowrap bg-[#16a34a] hover:bg-green-700 text-white px-3 py-2 rounded-xl text-xs font-bold shadow-md shadow-green-200 transition flex items-center gap-1.5 h-[36px]">
                        <i class="fa-solid fa-file-import"></i> Import
                    </button>

                    {{-- Unduh Excel --}}
                    <a href="{{ route('bk.biodata.excel', array_filter(['kelas' => $kelasAktif])) }}"
                        class="flex-shrink-0 whitespace-nowrap bg-white border border-[#16a34a] text-[#16a34a] hover:bg-green-50 px-3 py-2 rounded-xl text-xs font-bold shadow-sm transition flex items-center gap-1.5 h-[36px]"
                        title="{{ $kelasAktif ? 'Unduh Excel kelas '.$kelasAktif : 'Unduh Excel semua kelas' }}">
                        <i class="fa-solid fa-file-export"></i>
                        Unduh Excel{{ $kelasAktif ? ' ('.$kelasAktif.')' : '' }}
                    </a>

                    {{-- Tambah Biodata --}}
                    <button onclick="openModal('modalTambah')"
                        class="flex-shrink-0 whitespace-nowrap bg-[#2563eb] hover:bg-blue-700 text-white px-3 py-2 rounded-xl text-xs font-bold shadow-md shadow-blue-200 transition flex items-center gap-1.5 h-[36px]">
                        <i class="fa-solid fa-plus"></i> Tambah
                    </button>
                </div>
            </div>

            {{-- Tabel --}}
            @php
                // Strip /api dari akhir URL untuk akses static files (foto)
                $staticBase = rtrim(preg_replace('#/api$#', '', env('API_BASE_URL')), '/');
            @endphp

            <div class="bg-white rounded-[20px] shadow-sm border border-slate-200 overflow-hidden w-full flex-1">
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
                            @forelse($siswa as $s)
                                @php
                                    $initials = collect(explode(' ', $s['nama'] ?? 'S'))
                                        ->take(2)->map(fn($w) => strtoupper($w[0] ?? ''))->implode('');
                                    $tgl = $s['tanggal_lahir'] ? \Carbon\Carbon::parse($s['tanggal_lahir'])->format('d-m-Y') : '-';
                                    $colors = ['bg-lime-300','bg-blue-200','bg-purple-200','bg-pink-200','bg-orange-200','bg-teal-200'];
                                    $color = $colors[$s['id'] % count($colors)];
                                @endphp
                                <tr class="hover:bg-slate-50 transition group">
                                    <td class="py-3 pl-5 pr-3">
                                        <div class="flex items-center gap-2.5">
                                            @if(!empty($s['gambar']))
                                                <img src="{{ $staticBase }}/{{ $s['gambar'] }}"
                                                    class="w-8 h-8 rounded-full object-cover border border-slate-200"
                                                    onerror="this.style.display='none';this.nextElementSibling.style.display='flex'">
                                                <div class="w-8 h-8 rounded-full {{ $color }} text-slate-800 items-center justify-center font-bold text-xs border border-white shadow-sm" style="display:none">{{ $initials }}</div>
                                            @else
                                                <div class="w-8 h-8 rounded-full {{ $color }} text-slate-800 flex items-center justify-center font-bold text-xs border border-white shadow-sm">
                                                    {{ $initials }}
                                                </div>
                                            @endif
                                            <div>
                                                <p class="font-bold text-slate-800 text-xs group-hover:text-[#2563eb] transition">{{ $s['nama'] ?? '-' }}</p>
                                                <p class="text-[9px] text-slate-400 font-mono mt-0.5">{{ $s['nis'] ?? '-' }} / {{ $s['nisn'] ?? '-' }}</p>
                                            </div>
                                        </div>
                                    </td>
                                    <td class="py-3 px-3">
                                        <div class="flex items-center gap-1.5">
                                            <span class="bg-blue-50 text-[#2563eb] px-2 py-1 rounded text-[10px] font-bold">{{ $s['kelas'] ?? '-' }}</span>
                                            <span class="text-[11px] {{ ($s['jenis_kelamin'] ?? '') === 'L' ? 'text-blue-500' : 'text-pink-500' }} font-medium">
                                                {{ ($s['jenis_kelamin'] ?? '') === 'L' ? 'Laki-laki' : 'Perempuan' }}
                                            </span>
                                        </div>
                                    </td>
                                    <td class="py-3 px-3 text-[11px] text-slate-600 font-medium">
                                        {{ $s['tempat_lahir'] ?? '-' }}, {{ $tgl }}
                                    </td>
                                    <td class="py-3 px-3">
                                        <p class="font-bold text-slate-700 text-xs">{{ $s['nama_ayah'] ?? '-' }}</p>
                                        <p class="text-[9px] text-slate-500 mt-0.5">{{ $s['pekerjaan_ayah'] ?? '-' }}</p>
                                    </td>
                                    <td class="py-3 px-3">
                                        <p class="font-bold text-slate-700 text-xs">{{ $s['nama_ibu'] ?? '-' }}</p>
                                        <p class="text-[9px] text-slate-500 mt-0.5">{{ $s['pekerjaan_ibu'] ?? '-' }}</p>
                                    </td>
                                    <td class="py-3 px-3 text-slate-400 font-bold text-xs">
                                        @if(!empty($s['nama_wali']))
                                            <p class="font-bold text-slate-700 text-xs">{{ $s['nama_wali'] }}</p>
                                            <p class="text-[9px] text-slate-500 mt-0.5">{{ $s['pekerjaan_wali'] ?? '-' }}</p>
                                        @else
                                            -
                                        @endif
                                    </td>
                                    <td class="py-3 px-3">
                                        <p class="font-bold text-[#16a34a] text-xs tracking-wide">{{ $s['no_telepon'] ?? '-' }}</p>
                                        <p class="text-[9px] text-slate-500 mt-0.5 max-w-[100px] truncate" title="{{ $s['alamat'] ?? '' }}">{{ $s['alamat'] ?? '-' }}</p>
                                    </td>
                                    <td class="py-3 pr-5 pl-3 text-right">
                                        <div class="flex justify-end gap-2">
                                            <button type="button" onclick='openEditModal({{ json_encode($s) }})'
                                                class="text-slate-400 hover:text-[#2563eb] transition text-base" title="Edit">
                                                <i class="fa-regular fa-pen-to-square"></i>
                                            </button>
                                            <button type="button" onclick="confirmDelete({{ $s['id'] }})"
                                                class="text-slate-400 hover:text-[#ef4444] transition text-base" title="Hapus">
                                                <i class="fa-regular fa-trash-can"></i>
                                            </button>
                                        </div>
                                    </td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="8" class="text-center p-10 text-slate-400 text-sm">
                                        <i class="fa-regular fa-folder-open text-3xl mb-2 block"></i>
                                        Tidak ada data siswa.
                                    </td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>

                {{-- Pagination --}}
                @if(!empty($pagination) && ($pagination['totalPages'] ?? 1) > 1)
                    <div class="flex flex-col md:flex-row justify-between items-center p-4 border-t border-slate-100">
                        <p class="text-slate-500 text-xs mb-3 md:mb-0">
                            Halaman {{ $pagination['page'] ?? 1 }} dari {{ $pagination['totalPages'] ?? 1 }}
                            ({{ $pagination['total'] ?? 0 }} data)
                        </p>
                        <div class="flex items-center gap-1.5">
                            @if(($pagination['page'] ?? 1) > 1)
                                <a href="{{ route('bk.biodata', array_merge(request()->query(), ['page' => $pagination['page'] - 1])) }}"
                                    class="px-3 py-1.5 border border-slate-200 rounded-lg text-slate-600 text-[11px] font-bold hover:bg-slate-50 transition">Prev</a>
                            @endif
                            @for($p = 1; $p <= ($pagination['totalPages'] ?? 1); $p++)
                                <a href="{{ route('bk.biodata', array_merge(request()->query(), ['page' => $p])) }}"
                                    class="w-7 h-7 rounded-lg border text-[11px] font-bold flex items-center justify-center transition
                                    {{ $p == ($pagination['page'] ?? 1) ? 'bg-[#2563eb] text-white border-blue-600 shadow-md shadow-blue-200' : 'bg-white border-slate-200 text-slate-600 hover:bg-slate-50' }}">
                                    {{ $p }}
                                </a>
                            @endfor
                            @if(($pagination['page'] ?? 1) < ($pagination['totalPages'] ?? 1))
                                <a href="{{ route('bk.biodata', array_merge(request()->query(), ['page' => $pagination['page'] + 1])) }}"
                                    class="px-3 py-1.5 border border-slate-200 rounded-lg text-slate-600 text-[11px] font-bold hover:bg-slate-50 transition">Next</a>
                            @endif
                        </div>
                    </div>
                @endif
            </div>

        </div>
    </div>

    {{-- ===== MODAL TAMBAH ===== --}}
    <div id="modalTambah" style="display:none" class="fixed inset-0 bg-black/40 items-center justify-center z-50 backdrop-blur-sm">
        <div class="bg-white rounded-[20px] w-full max-w-4xl shadow-2xl flex flex-col max-h-[90vh] mx-4">
            <div class="p-5 border-b border-slate-100 flex justify-between items-center bg-slate-50 rounded-t-[20px]">
                <h3 class="font-bold text-slate-800 text-lg">Input Biodata Lengkap Siswa</h3>
                <button onclick="closeModal('modalTambah')" class="text-slate-400 hover:text-red-500 transition">
                    <i class="fa-solid fa-xmark text-xl"></i>
                </button>
            </div>
            <div class="p-6 overflow-y-auto custom-scroll space-y-6">
                <form id="formTambah" method="POST" action="{{ route('bk.biodata.store') }}" enctype="multipart/form-data">
                    @csrf

                    <h4 class="text-sm font-bold text-[#2563eb] uppercase border-l-4 border-[#2563eb] pl-3">A. Identitas Siswa</h4>

                    {{-- Pilih Tahun Ajaran --}}
                    <div class="bg-blue-50 border border-blue-100 rounded-xl p-4 mb-2">
                        <label class="text-xs font-bold text-slate-600 uppercase block mb-1.5">
                            <i class="fa-solid fa-calendar-alt text-[#2563eb] mr-1"></i> Tahun Ajaran *
                        </label>
                        <select name="id_tahun_ajaran" required
                            class="w-full border border-blue-200 rounded-xl p-3 text-sm bg-white outline-none focus:ring-2 focus:ring-blue-300 font-semibold text-slate-700">
                            <option value="">-- Pilih Tahun Ajaran --</option>
                            @foreach($tahunAjaranList as $ta)
                                <option value="{{ $ta['id'] }}" {{ ($ta['status'] ?? '') === 'aktif' ? 'selected' : '' }}>
                                    {{ $ta['tahun_ajaran'] }} &mdash; Semester {{ $ta['semester'] }}
                                    @if(($ta['status'] ?? '') === 'aktif') &bull; Aktif @endif
                                </option>
                            @endforeach
                        </select>
                        <p class="text-[10px] text-blue-500 mt-1.5">
                            <i class="fa-solid fa-circle-info mr-1"></i>
                            Pilih tahun ajaran yang sesuai. Siswa akan muncul di halaman biodata sesuai pilihan ini.
                        </p>
                    </div>

                    <div class="flex flex-col md:flex-row gap-6">
                        {{-- Foto --}}
                        <div class="w-full md:w-1/5 flex flex-col items-center">
                            <label class="w-32 h-32 rounded-full border-2 border-dashed border-slate-300 flex flex-col items-center justify-center text-slate-400 hover:bg-slate-50 cursor-pointer bg-slate-50/50 relative overflow-hidden transition">
                                <i class="fa-solid fa-camera mb-1 text-2xl text-[#2563eb]"></i>
                                <span class="text-[10px] font-bold uppercase tracking-widest">Upload Foto</span>
                                <input type="file" name="gambar" accept=".jpg,.jpeg,.png" class="absolute inset-0 opacity-0 cursor-pointer">
                            </label>
                            <p class="text-[10px] text-slate-400 mt-1 text-center">JPG/PNG. Maks 2MB.</p>
                        </div>

                        <div class="flex-1 space-y-4">
                            <div class="grid grid-cols-2 gap-4">
                                <div>
                                    <label class="text-xs font-bold text-slate-500 uppercase block mb-1.5">Nama Lengkap *</label>
                                    <input type="text" name="nama" required class="w-full border border-slate-200 rounded-xl p-3 text-sm outline-none focus:ring-2 focus:ring-blue-300">
                                </div>
                                <div>
                                    <label class="text-xs font-bold text-slate-500 uppercase block mb-1.5">Kelas *</label>
                                    <select name="kelas" required class="w-full border border-slate-200 rounded-xl p-3 text-sm bg-white outline-none focus:ring-2 focus:ring-blue-300">
                                        <option value="">Pilih Kelas</option>
                                        @foreach($kelasList as $k)
                                            <option value="{{ $k }}">{{ $k }}</option>
                                        @endforeach
                                    </select>
                                </div>
                            </div>
                            <div class="grid grid-cols-3 gap-4">
                                <div>
                                    <label class="text-xs font-bold text-slate-500 uppercase block mb-1.5">NIS *</label>
                                    <input type="text" name="nis" required class="w-full border border-slate-200 rounded-xl p-3 text-sm outline-none focus:ring-2 focus:ring-blue-300">
                                </div>
                                <div>
                                    <label class="text-xs font-bold text-slate-500 uppercase block mb-1.5">NISN *</label>
                                    <input type="text" name="nisn" required class="w-full border border-slate-200 rounded-xl p-3 text-sm outline-none focus:ring-2 focus:ring-blue-300">
                                </div>
                                <div>
                                    <label class="text-xs font-bold text-slate-500 uppercase block mb-1.5">Jenis Kelamin *</label>
                                    <select name="jenis_kelamin" required class="w-full border border-slate-200 rounded-xl p-3 text-sm bg-white outline-none focus:ring-2 focus:ring-blue-300">
                                        <option value="">Pilih</option>
                                        <option value="L">Laki-laki</option>
                                        <option value="P">Perempuan</option>
                                    </select>
                                </div>
                            </div>
                            <div class="grid grid-cols-2 gap-4">
                                <div>
                                    <label class="text-xs font-bold text-slate-500 uppercase block mb-1.5">Tempat Lahir</label>
                                    <input type="text" name="tempat_lahir" class="w-full border border-slate-200 rounded-xl p-3 text-sm outline-none focus:ring-2 focus:ring-blue-300">
                                </div>
                                <div>
                                    <label class="text-xs font-bold text-slate-500 uppercase block mb-1.5">Tanggal Lahir</label>
                                    <input type="date" name="tanggal_lahir" class="w-full border border-slate-200 rounded-xl p-3 text-sm outline-none focus:ring-2 focus:ring-blue-300 text-slate-600">
                                </div>
                            </div>
                            <div>
                                <label class="text-xs font-bold text-slate-500 uppercase block mb-1.5">Alamat</label>
                                <textarea name="alamat" rows="2" class="w-full border border-slate-200 rounded-xl p-3 text-sm outline-none focus:ring-2 focus:ring-blue-300"></textarea>
                            </div>
                        </div>
                    </div>

                    <h4 class="text-sm font-bold text-[#2563eb] uppercase border-l-4 border-[#2563eb] pl-3 pt-2">B. Data Orang Tua / Wali</h4>
                    <div class="grid grid-cols-2 gap-4">
                        <div>
                            <label class="text-xs font-bold text-slate-500 uppercase block mb-1.5">Nama Ayah</label>
                            <input type="text" name="nama_ayah" class="w-full border border-slate-200 rounded-xl p-3 text-sm outline-none focus:ring-2 focus:ring-blue-300">
                        </div>
                        <div>
                            <label class="text-xs font-bold text-slate-500 uppercase block mb-1.5">Pekerjaan Ayah</label>
                            <input type="text" name="pekerjaan_ayah" class="w-full border border-slate-200 rounded-xl p-3 text-sm outline-none focus:ring-2 focus:ring-blue-300">
                        </div>
                        <div>
                            <label class="text-xs font-bold text-slate-500 uppercase block mb-1.5">Nama Ibu</label>
                            <input type="text" name="nama_ibu" class="w-full border border-slate-200 rounded-xl p-3 text-sm outline-none focus:ring-2 focus:ring-blue-300">
                        </div>
                        <div>
                            <label class="text-xs font-bold text-slate-500 uppercase block mb-1.5">Pekerjaan Ibu</label>
                            <input type="text" name="pekerjaan_ibu" class="w-full border border-slate-200 rounded-xl p-3 text-sm outline-none focus:ring-2 focus:ring-blue-300">
                        </div>
                        <div>
                            <label class="text-xs font-bold text-slate-500 uppercase block mb-1.5">Nama Wali</label>
                            <input type="text" name="nama_wali" class="w-full border border-slate-200 rounded-xl p-3 text-sm outline-none focus:ring-2 focus:ring-blue-300">
                        </div>
                        <div>
                            <label class="text-xs font-bold text-slate-500 uppercase block mb-1.5">Pekerjaan Wali</label>
                            <input type="text" name="pekerjaan_wali" class="w-full border border-slate-200 rounded-xl p-3 text-sm outline-none focus:ring-2 focus:ring-blue-300">
                        </div>
                        <div class="col-span-2">
                            <label class="text-xs font-bold text-slate-500 uppercase block mb-1.5">No. Telepon / WhatsApp</label>
                            <input type="text" name="no_telepon" placeholder="08xxxxxxxxxx" class="w-full border border-slate-200 rounded-xl p-3 text-sm outline-none focus:ring-2 focus:ring-blue-300 font-bold text-slate-700">
                        </div>
                    </div>

                    <div class="flex justify-end gap-3 pt-4 border-t border-slate-100">
                        <button type="button" onclick="closeModal('modalTambah')"
                            class="px-6 py-2.5 border border-slate-300 rounded-xl font-bold text-sm text-slate-600 hover:bg-white transition">Batal</button>
                        <button type="submit"
                            class="px-8 py-2.5 bg-[#2563eb] text-white rounded-xl font-bold text-sm shadow-lg shadow-blue-200 hover:bg-blue-700 transition">Simpan Data</button>
                    </div>
                </form>
            </div>
        </div>
    </div>

    {{-- ===== MODAL EDIT ===== --}}
    <div id="modalEdit" style="display:none" class="fixed inset-0 bg-black/40 items-center justify-center z-50 backdrop-blur-sm">
        <div class="bg-white rounded-[20px] w-full max-w-4xl shadow-2xl flex flex-col max-h-[90vh] mx-4">
            <div class="p-5 border-b border-slate-100 flex justify-between items-center bg-slate-50 rounded-t-[20px]">
                <h3 class="font-bold text-slate-800 text-lg">Edit Biodata Siswa</h3>
                <button onclick="closeModal('modalEdit')" class="text-slate-400 hover:text-red-500 transition">
                    <i class="fa-solid fa-xmark text-xl"></i>
                </button>
            </div>
            <div class="p-6 overflow-y-auto custom-scroll space-y-4">
                <form id="editForm" method="POST" action="" enctype="multipart/form-data">
                    @csrf

                    <div class="flex flex-col md:flex-row gap-6">
                        <div class="w-full md:w-1/5 flex flex-col items-center">
                            <div id="editFotoPreview" class="w-32 h-32 rounded-full border-2 border-dashed border-slate-300 bg-slate-50 flex items-center justify-center overflow-hidden">
                                <i class="fa-solid fa-user text-3xl text-slate-300"></i>
                            </div>
                            <label class="mt-2 cursor-pointer text-xs text-blue-500 font-bold hover:underline">
                                Ganti Foto
                                <input type="file" name="gambar" accept=".jpg,.jpeg,.png" class="hidden"
                                    onchange="previewEditFoto(this)">
                            </label>
                        </div>
                        <div class="flex-1 space-y-4">
                            <div class="grid grid-cols-2 gap-4">
                                <div>
                                    <label class="text-xs font-bold text-slate-500 uppercase block mb-1.5">Nama Lengkap</label>
                                    <input type="text" name="nama" id="editNama" class="w-full border border-slate-200 rounded-xl p-3 text-sm outline-none focus:ring-2 focus:ring-blue-300">
                                </div>
                                <div>
                                    <label class="text-xs font-bold text-slate-500 uppercase block mb-1.5">Kelas</label>
                                    <select name="kelas" id="editKelas" class="w-full border border-slate-200 rounded-xl p-3 text-sm bg-white outline-none focus:ring-2 focus:ring-blue-300">
                                        @foreach($kelasList as $k)
                                            <option value="{{ $k }}">{{ $k }}</option>
                                        @endforeach
                                    </select>
                                </div>
                            </div>
                            <div class="grid grid-cols-2 gap-4">
                                <div>
                                    <label class="text-xs font-bold text-slate-500 uppercase block mb-1.5">NIS</label>
                                    <input type="text" id="editNis" class="w-full border border-slate-200 rounded-xl p-3 text-sm bg-slate-50 text-slate-400 cursor-not-allowed outline-none" disabled>
                                </div>
                                <div>
                                    <label class="text-xs font-bold text-slate-500 uppercase block mb-1.5">Jenis Kelamin</label>
                                    <select name="jenis_kelamin" id="editJenisKelamin" class="w-full border border-slate-200 rounded-xl p-3 text-sm bg-white outline-none focus:ring-2 focus:ring-blue-300">
                                        <option value="L">Laki-laki</option>
                                        <option value="P">Perempuan</option>
                                    </select>
                                </div>
                            </div>
                            <div class="grid grid-cols-2 gap-4">
                                <div>
                                    <label class="text-xs font-bold text-slate-500 uppercase block mb-1.5">Tempat Lahir</label>
                                    <input type="text" name="tempat_lahir" id="editTempatLahir" class="w-full border border-slate-200 rounded-xl p-3 text-sm outline-none focus:ring-2 focus:ring-blue-300">
                                </div>
                                <div>
                                    <label class="text-xs font-bold text-slate-500 uppercase block mb-1.5">Tanggal Lahir</label>
                                    <input type="date" name="tanggal_lahir" id="editTanggalLahir" class="w-full border border-slate-200 rounded-xl p-3 text-sm outline-none focus:ring-2 focus:ring-blue-300 text-slate-600">
                                </div>
                            </div>
                            <div>
                                <label class="text-xs font-bold text-slate-500 uppercase block mb-1.5">Alamat</label>
                                <textarea name="alamat" id="editAlamat" rows="2" class="w-full border border-slate-200 rounded-xl p-3 text-sm outline-none focus:ring-2 focus:ring-blue-300"></textarea>
                            </div>
                        </div>
                    </div>

                    <div class="grid grid-cols-2 gap-4 pt-2 border-t border-slate-100">
                        <div>
                            <label class="text-xs font-bold text-slate-500 uppercase block mb-1.5">Nama Ayah</label>
                            <input type="text" name="nama_ayah" id="editNamaAyah" class="w-full border border-slate-200 rounded-xl p-3 text-sm outline-none focus:ring-2 focus:ring-blue-300">
                        </div>
                        <div>
                            <label class="text-xs font-bold text-slate-500 uppercase block mb-1.5">Pekerjaan Ayah</label>
                            <input type="text" name="pekerjaan_ayah" id="editPekerjaanAyah" class="w-full border border-slate-200 rounded-xl p-3 text-sm outline-none focus:ring-2 focus:ring-blue-300">
                        </div>
                        <div>
                            <label class="text-xs font-bold text-slate-500 uppercase block mb-1.5">Nama Ibu</label>
                            <input type="text" name="nama_ibu" id="editNamaIbu" class="w-full border border-slate-200 rounded-xl p-3 text-sm outline-none focus:ring-2 focus:ring-blue-300">
                        </div>
                        <div>
                            <label class="text-xs font-bold text-slate-500 uppercase block mb-1.5">Pekerjaan Ibu</label>
                            <input type="text" name="pekerjaan_ibu" id="editPekerjaanIbu" class="w-full border border-slate-200 rounded-xl p-3 text-sm outline-none focus:ring-2 focus:ring-blue-300">
                        </div>
                        <div>
                            <label class="text-xs font-bold text-slate-500 uppercase block mb-1.5">Nama Wali</label>
                            <input type="text" name="nama_wali" id="editNamaWali" class="w-full border border-slate-200 rounded-xl p-3 text-sm outline-none focus:ring-2 focus:ring-blue-300">
                        </div>
                        <div>
                            <label class="text-xs font-bold text-slate-500 uppercase block mb-1.5">Pekerjaan Wali</label>
                            <input type="text" name="pekerjaan_wali" id="editPekerjaanWali" class="w-full border border-slate-200 rounded-xl p-3 text-sm outline-none focus:ring-2 focus:ring-blue-300">
                        </div>
                        <div class="col-span-2">
                            <label class="text-xs font-bold text-slate-500 uppercase block mb-1.5">No. Telepon</label>
                            <input type="text" name="no_telepon" id="editNoTelepon" class="w-full border border-slate-200 rounded-xl p-3 text-sm outline-none focus:ring-2 focus:ring-blue-300 font-bold text-slate-700">
                        </div>
                    </div>

                    <div class="flex justify-end gap-3 pt-4 border-t border-slate-100">
                        <button type="button" onclick="closeModal('modalEdit')"
                            class="px-6 py-2.5 border border-slate-300 rounded-xl font-bold text-sm text-slate-600 hover:bg-white transition">Batal</button>
                        <button type="submit"
                            class="px-8 py-2.5 bg-[#2563eb] text-white rounded-xl font-bold text-sm shadow-lg shadow-blue-200 hover:bg-blue-700 transition">Simpan Perubahan</button>
                    </div>
                </form>
            </div>
        </div>
    </div>

    {{-- ===== MODAL IMPORT ===== --}}
    <div id="modalImport" style="display:none" class="fixed inset-0 bg-black/40 items-center justify-center z-50 backdrop-blur-sm">
        <div class="bg-white rounded-[20px] w-full max-w-md shadow-2xl p-6 mx-4">
            <div class="flex justify-between items-center mb-5 pb-4 border-b border-slate-100">
                <h3 class="font-bold text-lg text-slate-800">Import Data Siswa</h3>
                <button onclick="closeModal('modalImport')" class="text-slate-400 hover:text-red-500 transition">
                    <i class="fa-solid fa-xmark text-xl"></i>
                </button>
            </div>
            <form method="POST" action="{{ route('bk.biodata.import') }}" enctype="multipart/form-data">
                @csrf
                <div class="space-y-4">
                    <div class="bg-amber-50 border border-amber-200 rounded-xl p-3 text-xs text-amber-700 flex items-start gap-2">
                        <i class="fa-solid fa-circle-info mt-0.5"></i>
                        <span>Gunakan template yang sudah disediakan. Pastikan format tahun ajaran sesuai (contoh: 2025/2026).</span>
                    </div>
                    <div>
                        <label class="text-xs font-bold text-slate-500 uppercase block mb-2">File Excel (.xlsx)</label>
                        <label class="border-2 border-dashed border-slate-300 rounded-xl p-6 text-center hover:bg-slate-50 hover:border-blue-400 transition cursor-pointer block">
                            <input type="file" name="file" accept=".xlsx,.xls" required class="hidden"
                                onchange="document.getElementById('importFileName').textContent = this.files[0]?.name ?? 'Pilih file'">
                            <i class="fa-solid fa-cloud-arrow-up text-3xl text-slate-300 mb-2 block"></i>
                            <p id="importFileName" class="text-sm text-slate-600 font-bold">Klik untuk pilih file</p>
                            <p class="text-[10px] text-slate-400 mt-1">Format: XLSX, XLS</p>
                        </label>
                    </div>
                    <div class="flex justify-end gap-3 pt-2">
                        <button type="button" onclick="closeModal('modalImport')"
                            class="px-5 py-2.5 rounded-xl border border-slate-200 text-slate-500 text-sm font-bold hover:bg-slate-50 transition">Batal</button>
                        <button type="submit"
                            class="px-6 py-2.5 rounded-xl bg-[#16a34a] text-white text-sm font-bold hover:bg-green-700 shadow-md shadow-green-200 transition">Import Sekarang</button>
                    </div>
                </div>
            </form>
        </div>
    </div>

    <form id="deleteForm" method="POST" action="" class="hidden">@csrf</form>

    @push('scripts')
    <script>
        const apiBase   = "{{ env('API_BASE_URL') }}";
        // Base URL tanpa /api — untuk akses static files (foto upload)
        const staticBase = "{{ rtrim(preg_replace('#/api$#', '', env('API_BASE_URL')), '/') }}";

        function openModal(id) {
            document.getElementById(id).style.display = 'flex';
        }
        function closeModal(id) {
            document.getElementById(id).style.display = 'none';
        }
        ['modalTambah','modalEdit','modalImport'].forEach(id => {
            document.getElementById(id).addEventListener('click', function(e) {
                if (e.target === this) closeModal(id);
            });
        });

        function openEditModal(s) {
            document.getElementById('editForm').action = `/bk/biodata/${s.id}/update`;
            document.getElementById('editNama').value          = s.nama          ?? '';
            document.getElementById('editNis').value           = s.nis           ?? '';
            document.getElementById('editKelas').value         = s.kelas         ?? '';
            document.getElementById('editJenisKelamin').value  = s.jenis_kelamin ?? 'L';
            document.getElementById('editTempatLahir').value   = s.tempat_lahir  ?? '';
            document.getElementById('editAlamat').value        = s.alamat        ?? '';
            document.getElementById('editNamaAyah').value      = s.nama_ayah     ?? '';
            document.getElementById('editPekerjaanAyah').value = s.pekerjaan_ayah ?? '';
            document.getElementById('editNamaIbu').value       = s.nama_ibu      ?? '';
            document.getElementById('editPekerjaanIbu').value  = s.pekerjaan_ibu ?? '';
            document.getElementById('editNamaWali').value      = s.nama_wali     ?? '';
            document.getElementById('editPekerjaanWali').value = s.pekerjaan_wali ?? '';
            document.getElementById('editNoTelepon').value     = s.no_telepon    ?? '';

            document.getElementById('editTanggalLahir').value = s.tanggal_lahir
                ? String(s.tanggal_lahir).substring(0, 10) : '';

            // Preview foto — pakai staticBase bukan apiBase
            const preview = document.getElementById('editFotoPreview');
            if (s.gambar) {
                preview.innerHTML = `<img src="${staticBase}/${s.gambar}" class="w-full h-full object-cover rounded-full">`;
            } else {
                preview.innerHTML = `<i class="fa-solid fa-user text-3xl text-slate-300"></i>`;
            }

            openModal('modalEdit');
        }

        function previewEditFoto(input) {
            if (input.files && input.files[0]) {
                const reader = new FileReader();
                reader.onload = e => {
                    document.getElementById('editFotoPreview').innerHTML =
                        `<img src="${e.target.result}" class="w-full h-full object-cover rounded-full">`;
                };
                reader.readAsDataURL(input.files[0]);
            }
        }

        function confirmDelete(id) {
            Swal.fire({
                title: 'Hapus Data Siswa?',
                text: 'Tindakan ini tidak dapat dibatalkan!',
                icon: 'warning',
                showCancelButton: true,
                confirmButtonColor: '#ef4444',
                cancelButtonColor: '#64748b',
                confirmButtonText: 'Ya, Hapus',
                cancelButtonText: 'Batal',
            }).then(result => {
                if (result.isConfirmed) {
                    const form = document.getElementById('deleteForm');
                    form.action = `/bk/biodata/${id}/delete`;
                    form.submit();
                }
            });
        }

        // ====================================================
        // MANAJEMEN NOTIFIKASI SWEETALERT (SUCCESS & ERROR)
        // ====================================================
        @if(session('success'))
            Swal.fire({
                icon: 'success',
                title: 'Berhasil!',
                text: {!! json_encode(session('success')) !!},
                showConfirmButton: false,
                timer: 2000
            });
        @endif

        @if(session('error'))
            Swal.fire({
                icon: 'error',
                title: 'Gagal!',
                text: {!! json_encode(session('error')) !!},
                confirmButtonColor: '#ef4444',
            });
        @endif

        @if($errors->any())
            Swal.fire({
                icon: 'error',
                title: 'Peringatan Validasi!',
                html: `
                    <div class="text-left text-sm text-red-600 mt-2">
                        <ul class="list-disc pl-5">
                            @foreach ($errors->all() as $error)
                                <li>{{ $error }}</li>
                            @endforeach
                        </ul>
                    </div>
                `,
                confirmButtonColor: '#ef4444',
            });
        @endif
    </script>
    @endpush

</x-layout-app>
