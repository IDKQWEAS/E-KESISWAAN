<x-layout-app title="Biodata Lengkap" :role="$role">

    <div class="flex-1 overflow-y-auto px-4 pb-4 pt-0 lg:px-6 lg:pb-6 custom-scroll bg-[#f8fafc] fade-in">
        <div class="w-full space-y-4">

            {{-- Header & Actions --}}
            <div class="bg-white p-4 lg:p-5 rounded-2xl shadow-sm border border-slate-200 flex flex-col xl:flex-row justify-between items-start xl:items-center gap-4">
                <div class="flex-shrink-0">
                    <h3 class="font-bold text-base text-slate-800">Biodata Siswa</h3>
                    <p class="text-[11px] text-slate-500 mt-0.5">Kelola data lengkap siswa.</p>
                </div>

                <div class="flex items-center gap-2 overflow-x-auto custom-scroll w-full xl:w-auto pb-1 xl:pb-0 flex-nowrap">
                    {{-- Form AJAX --}}
                    <form id="filterFormBiodata" method="GET" action="{{ route('bk.biodata') }}" class="flex items-center gap-2 flex-shrink-0 flex-nowrap">
                        <div class="relative flex-shrink-0 w-32 md:w-36">
                            <select name="kelas"
                                class="auto-submit w-full appearance-none bg-white border border-slate-200 rounded-lg py-1.5 pl-3 pr-8 text-[11px] font-bold text-slate-700 outline-none focus:ring-2 focus:ring-blue-500/20 cursor-pointer transition-all h-[32px]">
                                <option value="">Semua Kelas</option>
                                @foreach($kelasList as $k)
                                    <option value="{{ $k }}" {{ $kelasAktif === $k ? 'selected' : '' }}>{{ $k }}</option>
                                @endforeach
                            </select>
                            <i class="fa-solid fa-chevron-down absolute right-3 top-1/2 -translate-y-1/2 text-[9px] text-slate-400 pointer-events-none"></i>
                        </div>

                        <div class="relative">
                            <input type="text" name="search" value="{{ $search }}" placeholder="Cari nama / NISN..."
                                class="border border-slate-200 rounded-lg py-1.5 px-3 text-[11px] outline-none focus:ring-2 focus:ring-blue-300 w-36 md:w-40 flex-shrink-0 h-[32px]">
                        </div>

                        <button type="submit" class="bg-slate-100 hover:bg-slate-200 text-slate-600 px-3 py-1.5 rounded-lg text-[11px] font-bold transition flex items-center justify-center flex-shrink-0 h-[32px] w-[32px]">
                            <i class="fa-solid fa-magnifying-glass"></i>
                        </button>

                        @if($search || $kelasAktif)
                            <button type="button" onclick="window.location='{{ route('bk.biodata') }}'" class="bg-white border border-slate-200 hover:bg-slate-50 text-slate-500 px-3 py-1.5 rounded-lg text-[11px] font-bold transition flex items-center justify-center flex-shrink-0 h-[32px]">
                                Reset
                            </button>
                        @endif
                    </form>

                    <div class="w-px h-5 bg-slate-200 mx-1 flex-shrink-0"></div>

                    <a href="{{ route('bk.biodata.template') }}"
                        class="flex-shrink-0 whitespace-nowrap bg-[#fefce8] border-2 border-dashed border-[#facc15] text-[#ca8a04] px-3 py-1.5 rounded-lg text-[11px] font-bold hover:bg-[#fef9c3] transition flex items-center gap-1.5 h-[32px]">
                        <div class="w-3.5 h-3.5 rounded-full bg-[#facc15] text-white flex items-center justify-center text-[8px] font-bold">1</div>
                        Template <i class="fa-solid fa-file-excel"></i>
                    </a>

                    <button onclick="openModal('modalImport')"
                        class="flex-shrink-0 whitespace-nowrap bg-[#16a34a] hover:bg-green-700 text-white px-3 py-1.5 rounded-lg text-[11px] font-bold shadow-sm transition flex items-center gap-1.5 h-[32px]">
                        <i class="fa-solid fa-file-import"></i> Import
                    </button>

                    <a id="btn-excel" href="{{ route('bk.biodata.excel', request()->query()) }}"
                        class="flex-shrink-0 whitespace-nowrap bg-white border border-[#16a34a] text-[#16a34a] hover:bg-green-50 px-3 py-1.5 rounded-lg text-[11px] font-bold shadow-sm transition flex items-center gap-1.5 h-[32px]">
                        <i class="fa-solid fa-file-export"></i>
                        Unduh Excel{{ $kelasAktif ? ' ('.$kelasAktif.')' : '' }}
                    </a>

                    <button onclick="openModal('modalTambah')"
                        class="flex-shrink-0 whitespace-nowrap bg-[#2563eb] hover:bg-blue-700 text-white px-3 py-1.5 rounded-lg text-[11px] font-bold shadow-sm transition flex items-center gap-1.5 h-[32px]">
                        <i class="fa-solid fa-plus"></i> Tambah
                    </button>
                </div>
            </div>

            {{-- Tabel --}}
            @php
                $staticBase = rtrim(preg_replace('#/api$#', '', env('API_BASE_URL')), '/');
            @endphp

            {{-- BUNGKUS DENGAN AJAX ID --}}
            <div id="table-container" class="bg-white rounded-2xl shadow-sm border border-slate-200 overflow-hidden w-full transition-opacity duration-300">
                <div class="overflow-x-auto custom-scroll pb-1">
                    <table class="w-full text-left border-collapse min-w-max">
                        <thead class="bg-slate-50 border-b border-slate-100">
                            <tr>
                                <th class="py-2.5 pl-5 pr-3 text-[9px] font-bold text-slate-400 uppercase tracking-widest">NAMA / NIPD / NISN</th>
                                <th class="py-2.5 px-3 text-[9px] font-bold text-slate-400 uppercase tracking-widest">KELAS/GENDER</th>
                                <th class="py-2.5 px-3 text-[9px] font-bold text-slate-400 uppercase tracking-widest">TTL</th>
                                <th class="py-2.5 px-3 text-[9px] font-bold text-slate-400 uppercase tracking-widest">DATA AYAH</th>
                                <th class="py-2.5 px-3 text-[9px] font-bold text-slate-400 uppercase tracking-widest">DATA IBU</th>
                                <th class="py-2.5 px-3 text-[9px] font-bold text-slate-400 uppercase tracking-widest">DATA WALI</th>
                                <th class="py-2.5 px-3 text-[9px] font-bold text-slate-400 uppercase tracking-widest">KONTAK / DOMISILI</th>
                                <th class="py-2.5 pr-5 pl-3 text-[9px] font-bold text-slate-400 uppercase tracking-widest text-right">AKSI</th>
                            </tr>
                        </thead>
                        <tbody class="divide-y divide-slate-50">
                            @forelse($siswa as $s)
                                @php
                                    $initials = collect(explode(' ', $s['nama'] ?? 'S'))
                                        ->take(2)->map(fn($w) => strtoupper($w[0] ?? ''))->implode('');
                                    $tgl = !empty($s['tanggal_lahir'])
                                        ? \Carbon\Carbon::parse($s['tanggal_lahir'])->format('d-m-Y') : '-';
                                    $colors = ['bg-lime-300','bg-blue-200','bg-purple-200','bg-pink-200','bg-orange-200','bg-teal-200'];
                                    $color  = $colors[$s['id'] % count($colors)];
                                @endphp
                                <tr class="hover:bg-slate-50 transition group">
                                    <td class="py-2.5 pl-5 pr-3">
                                        <div class="flex items-center gap-2">
                                            @if(!empty($s['gambar']))
                                                <img src="{{ $staticBase }}/{{ $s['gambar'] }}"
                                                    class="w-7 h-7 rounded-full object-cover border border-slate-200 flex-shrink-0"
                                                    onerror="this.style.display='none';this.nextElementSibling.style.display='flex'">
                                                <div class="w-7 h-7 rounded-full {{ $color }} text-slate-800 items-center justify-center font-bold text-[10px] shadow-sm flex-shrink-0" style="display:none">{{ $initials }}</div>
                                            @else
                                                <div class="w-7 h-7 rounded-full {{ $color }} text-slate-800 flex items-center justify-center font-bold text-[10px] shadow-sm flex-shrink-0">{{ $initials }}</div>
                                            @endif
                                            <div>
                                                <p class="font-bold text-slate-800 text-[11px] group-hover:text-[#2563eb] transition">{{ $s['nama'] ?? '-' }}</p>
                                                <p class="text-[9px] text-slate-400 font-mono mt-0.5">{{ $s['nipd'] ?? '-' }} / {{ $s['nisn'] ?? '-' }}</p>
                                            </div>
                                        </div>
                                    </td>
                                    <td class="py-2.5 px-3">
                                        <div class="flex items-center gap-1.5">
                                            <span class="bg-blue-50 text-[#2563eb] px-1.5 py-0.5 rounded text-[9px] font-bold">{{ $s['kelas'] ?? '-' }}</span>
                                            <span class="text-[10px] {{ ($s['jenis_kelamin'] ?? '') === 'L' ? 'text-blue-500' : 'text-pink-500' }} font-medium">
                                                {{ ($s['jenis_kelamin'] ?? '') === 'L' ? 'Laki-laki' : 'Perempuan' }}
                                            </span>
                                        </div>
                                    </td>
                                    <td class="py-2.5 px-3 text-[10px] text-slate-600 font-medium">
                                        {{ $s['tempat_lahir'] ?? '-' }}, {{ $tgl }}
                                    </td>
                                    <td class="py-2.5 px-3">
                                        <p class="font-bold text-slate-700 text-[11px]">{{ $s['nama_ayah'] ?? '-' }}</p>
                                        <p class="text-[9px] text-slate-500">{{ $s['pekerjaan_ayah'] ?? '-' }}</p>
                                    </td>
                                    <td class="py-2.5 px-3">
                                        <p class="font-bold text-slate-700 text-[11px]">{{ $s['nama_ibu'] ?? '-' }}</p>
                                        <p class="text-[9px] text-slate-500">{{ $s['pekerjaan_ibu'] ?? '-' }}</p>
                                    </td>
                                    <td class="py-2.5 px-3 text-slate-400 font-bold text-[11px]">
                                        @if(!empty($s['nama_wali']))
                                            <p class="font-bold text-slate-700 text-[11px]">{{ $s['nama_wali'] }}</p>
                                            <p class="text-[9px] text-slate-500">{{ $s['pekerjaan_wali'] ?? '-' }}</p>
                                        @else
                                            -
                                        @endif
                                    </td>
                                    <td class="py-2.5 px-3">
                                        <p class="font-bold text-[#16a34a] text-[11px] tracking-wide">{{ $s['no_telepon'] ?? '-' }}</p>
                                        <p class="text-[9px] text-slate-500 mt-0.5 max-w-[120px] truncate" title="{{ $s['alamat'] ?? '' }}">{{ $s['alamat'] ?? '-' }}</p>
                                    </td>
                                    <td class="py-2.5 pr-5 pl-3 text-right">
                                        <div class="flex justify-end gap-1">
                                            <button type="button" onclick='openEditModal({{ json_encode($s) }})'
                                                class="w-7 h-7 rounded bg-slate-50 text-slate-400 hover:bg-slate-100 hover:text-[#2563eb] transition flex items-center justify-center text-[11px]" title="Edit">
                                                <i class="fa-regular fa-pen-to-square"></i>
                                            </button>
                                            <button type="button" onclick="confirmDelete({{ $s['id'] }})"
                                                class="w-7 h-7 rounded bg-slate-50 text-slate-400 hover:bg-slate-100 hover:text-[#ef4444] transition flex items-center justify-center text-[11px]" title="Hapus">
                                                <i class="fa-regular fa-trash-can"></i>
                                            </button>
                                        </div>
                                    </td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="8" class="text-center p-8 text-slate-400 text-[11px]">
                                        <i class="fa-regular fa-folder-open text-3xl mb-2 block"></i>
                                        Tidak ada data siswa.
                                    </td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>

                {{-- Pagination yang Direvisi --}}
                @if(!empty($pagination) && ($pagination['totalPages'] ?? 1) > 1)
                    <div class="flex flex-col md:flex-row justify-between items-center p-3 lg:p-4 border-t border-slate-100 bg-white">
                        <p class="text-slate-500 text-[10px] mb-3 md:mb-0">
                            Halaman <span class="font-bold">{{ $pagination['page'] ?? 1 }}</span> dari <span class="font-bold">{{ $pagination['totalPages'] ?? 1 }}</span>
                            &mdash; Total <span class="font-bold">{{ $pagination['total'] ?? 0 }}</span> data
                        </p>
                        <div class="flex items-center gap-1 flex-wrap justify-center">
                            {{-- Tombol Prev --}}
                            @if(($pagination['page'] ?? 1) > 1)
                                <a href="{{ route('bk.biodata', array_merge(request()->query(), ['page' => $pagination['page'] - 1])) }}"
                                   class="ajax-link px-2.5 py-1.5 border border-slate-200 rounded text-slate-600 text-[10px] font-bold hover:bg-slate-50 transition">Prev</a>
                            @else
                                <button disabled class="px-2.5 py-1.5 border border-slate-200 rounded text-slate-400 text-[10px] font-bold opacity-50 cursor-not-allowed">Prev</button>
                            @endif

                            {{-- Logika Jendela Angka Pagination --}}
                            @php
                                $currentPage = $pagination['page'] ?? 1;
                                $lastPage    = $pagination['totalPages'] ?? 1;

                                $start = max(1, $currentPage - 2);
                                $end   = min($lastPage, $currentPage + 2);

                                if ($start === 1) {
                                    $end = min(5, $lastPage);
                                } elseif ($end === $lastPage) {
                                    $start = max(1, $lastPage - 4);
                                }
                            @endphp

                            @if($start > 1)
                                <a href="{{ route('bk.biodata', array_merge(request()->query(), ['page' => 1])) }}" class="ajax-link w-7 h-7 rounded border border-slate-200 text-slate-600 hover:bg-slate-50 text-[10px] font-bold flex items-center justify-center transition">1</a>
                                @if($start > 2)
                                    <span class="text-slate-400 text-[10px] px-1">...</span>
                                @endif
                            @endif

                            @for($p = $start; $p <= $end; $p++)
                                <a href="{{ route('bk.biodata', array_merge(request()->query(), ['page' => $p])) }}"
                                   class="ajax-link w-7 h-7 rounded border text-[10px] font-bold flex items-center justify-center transition
                                   {{ $p == $currentPage ? 'bg-[#2563eb] text-white border-blue-600 shadow-sm' : 'bg-white border-slate-200 text-slate-600 hover:bg-slate-50' }}">
                                    {{ $p }}
                                </a>
                            @endfor

                            @if($end < $lastPage)
                                @if($end < $lastPage - 1)
                                    <span class="text-slate-400 text-[10px] px-1">...</span>
                                @endif
                                <a href="{{ route('bk.biodata', array_merge(request()->query(), ['page' => $lastPage])) }}" class="ajax-link w-7 h-7 rounded border border-slate-200 text-slate-600 hover:bg-slate-50 text-[10px] font-bold flex items-center justify-center transition">{{ $lastPage }}</a>
                            @endif

                            {{-- Tombol Next --}}
                            @if($currentPage < $lastPage)
                                <a href="{{ route('bk.biodata', array_merge(request()->query(), ['page' => $currentPage + 1])) }}"
                                   class="ajax-link px-2.5 py-1.5 border border-slate-200 rounded text-slate-600 text-[10px] font-bold hover:bg-slate-50 transition">Next</a>
                            @else
                                <button disabled class="px-2.5 py-1.5 border border-slate-200 rounded text-slate-400 text-[10px] font-bold opacity-50 cursor-not-allowed">Next</button>
                            @endif
                        </div>
                    </div>
                @endif
            </div>

        </div>
    </div>

    {{-- ===== MODAL TAMBAH ===== --}}
    <div id="modalTambah" style="display:none" class="fixed inset-0 bg-black/50 items-center justify-center z-50 backdrop-blur-sm">
        <div class="bg-white rounded-2xl w-full max-w-4xl shadow-2xl flex flex-col max-h-[90vh] mx-4">
            <div class="p-4 border-b border-slate-100 flex justify-between items-center bg-slate-50 rounded-t-2xl">
                <h3 class="font-bold text-slate-800 text-sm">Input Biodata Lengkap Siswa</h3>
                <button onclick="closeModal('modalTambah')" class="text-slate-400 hover:text-red-500 transition w-6 h-6 rounded flex items-center justify-center hover:bg-slate-200 text-[10px]">
                    <i class="fa-solid fa-xmark"></i>
                </button>
            </div>
            <div class="p-5 overflow-y-auto custom-scroll space-y-5">
                <form id="formTambah" method="POST" action="{{ route('bk.biodata.store') }}" enctype="multipart/form-data">
                    @csrf

                    <h4 class="text-[11px] font-bold text-[#2563eb] uppercase border-l-4 border-[#2563eb] pl-2">A. Identitas Siswa</h4>

                    {{-- Pilih Tahun Ajaran --}}
                    <div class="bg-blue-50 border border-blue-100 rounded-xl p-3 mb-2">
                        <label class="text-[10px] font-bold text-slate-600 uppercase block mb-1">
                            <i class="fa-solid fa-calendar-alt text-[#2563eb] mr-1"></i> Tahun Ajaran *
                        </label>
                        <select name="id_tahun_ajaran" required
                            class="w-full border border-blue-200 rounded-lg p-2 text-[11px] bg-white outline-none focus:ring-2 focus:ring-blue-300 font-bold text-slate-700">
                            <option value="">-- Pilih Tahun Ajaran --</option>
                            @foreach($tahunAjaranList as $ta)
                                <option value="{{ $ta['id'] }}" {{ ($ta['status'] ?? '') === 'aktif' ? 'selected' : '' }}>
                                    {{ $ta['tahun_ajaran'] }} &mdash; Semester {{ $ta['semester'] }}
                                    @if(($ta['status'] ?? '') === 'aktif') &bull; Aktif @endif
                                </option>
                            @endforeach
                        </select>
                    </div>

                    <div class="flex flex-col md:flex-row gap-5">
                        {{-- Foto --}}
                        <div class="w-full md:w-1/5 flex flex-col items-center">
                            <label class="w-24 h-24 rounded-full border-2 border-dashed border-slate-300 flex flex-col items-center justify-center text-slate-400 hover:bg-slate-50 cursor-pointer bg-slate-50/50 relative overflow-hidden transition">
                                <i class="fa-solid fa-camera mb-1 text-xl text-[#2563eb]"></i>
                                <span class="text-[9px] font-bold uppercase tracking-widest">Foto</span>
                                <input type="file" name="gambar" accept=".jpg,.jpeg,.png" class="absolute inset-0 opacity-0 cursor-pointer">
                            </label>
                            <p class="text-[9px] text-slate-400 mt-1 text-center">JPG/PNG. Maks 2MB.</p>
                        </div>

                        <div class="flex-1 space-y-3">
                            <div class="grid grid-cols-2 gap-3">
                                <div>
                                    <label class="text-[10px] font-bold text-slate-500 uppercase block mb-1">Nama Lengkap *</label>
                                    <input type="text" name="nama" required class="w-full border border-slate-200 rounded-lg p-2 text-[11px] outline-none focus:ring-2 focus:ring-blue-300">
                                </div>
                                <div>
                                    <label class="text-[10px] font-bold text-slate-500 uppercase block mb-1">Kelas *</label>
                                    <select name="kelas" required class="w-full border border-slate-200 rounded-lg p-2 text-[11px] bg-white outline-none focus:ring-2 focus:ring-blue-300">
                                        <option value="">Pilih Kelas</option>
                                        @foreach($kelasList as $k)
                                            <option value="{{ $k }}">{{ $k }}</option>
                                        @endforeach
                                    </select>
                                </div>
                            </div>
                            {{-- NIPD, NIK, NISN --}}
                            <div class="grid grid-cols-3 gap-3">
                                <div>
                                    <label class="text-[10px] font-bold text-slate-500 uppercase block mb-1">NIPD *</label>
                                    <input type="text" name="nipd" required class="w-full border border-slate-200 rounded-lg p-2 text-[11px] outline-none focus:ring-2 focus:ring-blue-300">
                                </div>
                                <div>
                                    <label class="text-[10px] font-bold text-slate-500 uppercase block mb-1">NIK *</label>
                                    <input type="text" name="nik" required class="w-full border border-slate-200 rounded-lg p-2 text-[11px] outline-none focus:ring-2 focus:ring-blue-300">
                                </div>
                                <div>
                                    <label class="text-[10px] font-bold text-slate-500 uppercase block mb-1">NISN *</label>
                                    <input type="text" name="nisn" required class="w-full border border-slate-200 rounded-lg p-2 text-[11px] outline-none focus:ring-2 focus:ring-blue-300">
                                </div>
                            </div>
                            <div class="grid grid-cols-2 gap-3">
                                <div>
                                    <label class="text-[10px] font-bold text-slate-500 uppercase block mb-1">Jenis Kelamin *</label>
                                    <select name="jenis_kelamin" required class="w-full border border-slate-200 rounded-lg p-2 text-[11px] bg-white outline-none focus:ring-2 focus:ring-blue-300">
                                        <option value="">Pilih</option>
                                        <option value="L">Laki-laki</option>
                                        <option value="P">Perempuan</option>
                                    </select>
                                </div>
                                <div>
                                    <label class="text-[10px] font-bold text-slate-500 uppercase block mb-1">Agama *</label>
                                    <select name="agama" required class="w-full border border-slate-200 rounded-lg p-2 text-[11px] bg-white outline-none focus:ring-2 focus:ring-blue-300">
                                        <option value="">Pilih</option>
                                        <option value="Islam">Islam</option>
                                        <option value="Kristen">Kristen</option>
                                        <option value="Katolik">Katolik</option>
                                        <option value="Hindu">Hindu</option>
                                        <option value="Buddha">Buddha</option>
                                        <option value="Konghucu">Konghucu</option>
                                    </select>
                                </div>
                            </div>
                            <div class="grid grid-cols-2 gap-3">
                                <div>
                                    <label class="text-[10px] font-bold text-slate-500 uppercase block mb-1">Tempat Lahir</label>
                                    <input type="text" name="tempat_lahir" class="w-full border border-slate-200 rounded-lg p-2 text-[11px] outline-none focus:ring-2 focus:ring-blue-300">
                                </div>
                                <div>
                                    <label class="text-[10px] font-bold text-slate-500 uppercase block mb-1">Tanggal Lahir</label>
                                    <input type="date" name="tanggal_lahir" class="w-full border border-slate-200 rounded-lg p-2 text-[11px] outline-none focus:ring-2 focus:ring-blue-300 text-slate-600">
                                </div>
                            </div>
                        </div>
                    </div>

                    {{-- Alamat lengkap --}}
                    <h4 class="text-[11px] font-bold text-[#2563eb] uppercase border-l-4 border-[#2563eb] pl-2 pt-2">B. Alamat</h4>
                    <div>
                        <label class="text-[10px] font-bold text-slate-500 uppercase block mb-1">Alamat</label>
                        <textarea name="alamat" rows="2" class="w-full border border-slate-200 rounded-lg p-2 text-[11px] outline-none focus:ring-2 focus:ring-blue-300"></textarea>
                    </div>
                    <div class="grid grid-cols-2 md:grid-cols-4 gap-3">
                        <div>
                            <label class="text-[10px] font-bold text-slate-500 uppercase block mb-1">RT</label>
                            <input type="text" name="rt" class="w-full border border-slate-200 rounded-lg p-2 text-[11px] outline-none focus:ring-2 focus:ring-blue-300">
                        </div>
                        <div>
                            <label class="text-[10px] font-bold text-slate-500 uppercase block mb-1">RW</label>
                            <input type="text" name="rw" class="w-full border border-slate-200 rounded-lg p-2 text-[11px] outline-none focus:ring-2 focus:ring-blue-300">
                        </div>
                        <div>
                            <label class="text-[10px] font-bold text-slate-500 uppercase block mb-1">Dusun</label>
                            <input type="text" name="dusun" class="w-full border border-slate-200 rounded-lg p-2 text-[11px] outline-none focus:ring-2 focus:ring-blue-300">
                        </div>
                        <div>
                            <label class="text-[10px] font-bold text-slate-500 uppercase block mb-1">Kode Pos</label>
                            <input type="text" name="kode_pos" class="w-full border border-slate-200 rounded-lg p-2 text-[11px] outline-none focus:ring-2 focus:ring-blue-300">
                        </div>
                    </div>
                    <div class="grid grid-cols-2 gap-3">
                        <div>
                            <label class="text-[10px] font-bold text-slate-500 uppercase block mb-1">Kelurahan / Desa</label>
                            <input type="text" name="kelurahan" class="w-full border border-slate-200 rounded-lg p-2 text-[11px] outline-none focus:ring-2 focus:ring-blue-300">
                        </div>
                        <div>
                            <label class="text-[10px] font-bold text-slate-500 uppercase block mb-1">Kecamatan</label>
                            <input type="text" name="kecamatan" class="w-full border border-slate-200 rounded-lg p-2 text-[11px] outline-none focus:ring-2 focus:ring-blue-300">
                        </div>
                    </div>

                    <h4 class="text-[11px] font-bold text-[#2563eb] uppercase border-l-4 border-[#2563eb] pl-2 pt-2">C. Data Orang Tua / Wali</h4>
                    <div class="grid grid-cols-2 gap-3">
                        <div>
                            <label class="text-[10px] font-bold text-slate-500 uppercase block mb-1">Nama Ayah</label>
                            <input type="text" name="nama_ayah" class="w-full border border-slate-200 rounded-lg p-2 text-[11px] outline-none focus:ring-2 focus:ring-blue-300">
                        </div>
                        <div>
                            <label class="text-[10px] font-bold text-slate-500 uppercase block mb-1">Pekerjaan Ayah</label>
                            <input type="text" name="pekerjaan_ayah" class="w-full border border-slate-200 rounded-lg p-2 text-[11px] outline-none focus:ring-2 focus:ring-blue-300">
                        </div>
                        <div>
                            <label class="text-[10px] font-bold text-slate-500 uppercase block mb-1">Nama Ibu</label>
                            <input type="text" name="nama_ibu" class="w-full border border-slate-200 rounded-lg p-2 text-[11px] outline-none focus:ring-2 focus:ring-blue-300">
                        </div>
                        <div>
                            <label class="text-[10px] font-bold text-slate-500 uppercase block mb-1">Pekerjaan Ibu</label>
                            <input type="text" name="pekerjaan_ibu" class="w-full border border-slate-200 rounded-lg p-2 text-[11px] outline-none focus:ring-2 focus:ring-blue-300">
                        </div>
                        <div>
                            <label class="text-[10px] font-bold text-slate-500 uppercase block mb-1">Nama Wali</label>
                            <input type="text" name="nama_wali" class="w-full border border-slate-200 rounded-lg p-2 text-[11px] outline-none focus:ring-2 focus:ring-blue-300">
                        </div>
                        <div>
                            <label class="text-[10px] font-bold text-slate-500 uppercase block mb-1">Pekerjaan Wali</label>
                            <input type="text" name="pekerjaan_wali" class="w-full border border-slate-200 rounded-lg p-2 text-[11px] outline-none focus:ring-2 focus:ring-blue-300">
                        </div>
                        <div>
                            <label class="text-[10px] font-bold text-slate-500 uppercase block mb-1">No. Telepon / WA</label>
                            <input type="text" name="no_telepon" placeholder="08xxxxxxxxxx" class="w-full border border-slate-200 rounded-lg p-2 text-[11px] outline-none focus:ring-2 focus:ring-blue-300 font-bold text-slate-700">
                        </div>
                        <div>
                            <label class="text-[10px] font-bold text-slate-500 uppercase block mb-1">Penghasilan Orang Tua</label>
                            <input type="number" name="penghasilan_orang_tua" placeholder="0" class="w-full border border-slate-200 rounded-lg p-2 text-[11px] outline-none focus:ring-2 focus:ring-blue-300">
                        </div>
                    </div>

                    <div class="flex justify-end gap-2 pt-3 border-t border-slate-100">
                        <button type="button" onclick="closeModal('modalTambah')"
                            class="px-4 py-1.5 border border-slate-300 rounded-lg font-bold text-[11px] text-slate-600 hover:bg-white transition">Batal</button>
                        <button type="submit"
                            class="px-5 py-1.5 bg-[#2563eb] text-white rounded-lg font-bold text-[11px] shadow-sm hover:bg-blue-700 transition">Simpan Data</button>
                    </div>
                </form>
            </div>
        </div>
    </div>

    {{-- ===== MODAL EDIT ===== --}}
    <div id="modalEdit" style="display:none" class="fixed inset-0 bg-black/50 items-center justify-center z-50 backdrop-blur-sm">
        <div class="bg-white rounded-2xl w-full max-w-4xl shadow-2xl flex flex-col max-h-[90vh] mx-4">
            <div class="p-4 border-b border-slate-100 flex justify-between items-center bg-slate-50 rounded-t-2xl">
                <h3 class="font-bold text-slate-800 text-sm">Edit Biodata Siswa</h3>
                <button onclick="closeModal('modalEdit')" class="text-slate-400 hover:text-red-500 transition w-6 h-6 rounded flex items-center justify-center hover:bg-slate-200 text-[10px]">
                    <i class="fa-solid fa-xmark"></i>
                </button>
            </div>
            <div class="p-5 overflow-y-auto custom-scroll space-y-4">
                <form id="editForm" method="POST" action="" enctype="multipart/form-data">
                    @csrf

                    <div class="flex flex-col md:flex-row gap-5">
                        <div class="w-full md:w-1/5 flex flex-col items-center">
                            <div id="editFotoPreview" class="w-24 h-24 rounded-full border-2 border-dashed border-slate-300 bg-slate-50 flex items-center justify-center overflow-hidden">
                                <i class="fa-solid fa-user text-2xl text-slate-300"></i>
                            </div>
                            <label class="mt-2 cursor-pointer text-[10px] text-blue-500 font-bold hover:underline">
                                Ganti Foto
                                <input type="file" name="gambar" accept=".jpg,.jpeg,.png" class="hidden" onchange="previewEditFoto(this)">
                            </label>
                        </div>
                        <div class="flex-1 space-y-3">
                            <div class="grid grid-cols-2 gap-3">
                                <div>
                                    <label class="text-[10px] font-bold text-slate-500 uppercase block mb-1">Nama Lengkap *</label>
                                    <input type="text" name="nama" id="editNama" required class="w-full border border-slate-200 rounded-lg p-2 text-[11px] outline-none focus:ring-2 focus:ring-blue-300">
                                </div>
                                <div>
                                    <label class="text-[10px] font-bold text-slate-500 uppercase block mb-1">Kelas *</label>
                                    <select name="kelas" id="editKelas" required class="w-full border border-slate-200 rounded-lg p-2 text-[11px] bg-white outline-none focus:ring-2 focus:ring-blue-300">
                                        @foreach($kelasList as $k)
                                            <option value="{{ $k }}">{{ $k }}</option>
                                        @endforeach
                                    </select>
                                </div>
                            </div>
                            <div class="grid grid-cols-3 gap-3">
                                <div>
                                    <label class="text-[10px] font-bold text-slate-500 uppercase block mb-1">NIPD *</label>
                                    <input type="text" name="nipd" id="editNipd" required class="w-full border border-slate-200 rounded-lg p-2 text-[11px] outline-none focus:ring-2 focus:ring-blue-300">
                                </div>
                                <div>
                                    <label class="text-[10px] font-bold text-slate-500 uppercase block mb-1">NIK *</label>
                                    <input type="text" name="nik" id="editNik" required class="w-full border border-slate-200 rounded-lg p-2 text-[11px] outline-none focus:ring-2 focus:ring-blue-300">
                                </div>
                                <div>
                                    <label class="text-[10px] font-bold text-slate-500 uppercase block mb-1">NISN *</label>
                                    <input type="text" name="nisn" id="editNisn" required class="w-full border border-slate-200 rounded-lg p-2 text-[11px] outline-none focus:ring-2 focus:ring-blue-300">
                                </div>
                            </div>
                            <div class="grid grid-cols-2 gap-3">
                                <div>
                                    <label class="text-[10px] font-bold text-slate-500 uppercase block mb-1">Jenis Kelamin *</label>
                                    <select name="jenis_kelamin" id="editJenisKelamin" required class="w-full border border-slate-200 rounded-lg p-2 text-[11px] bg-white outline-none focus:ring-2 focus:ring-blue-300">
                                        <option value="L">Laki-laki</option>
                                        <option value="P">Perempuan</option>
                                    </select>
                                </div>
                                <div>
                                    <label class="text-[10px] font-bold text-slate-500 uppercase block mb-1">Agama *</label>
                                    <select name="agama" id="editAgama" required class="w-full border border-slate-200 rounded-lg p-2 text-[11px] bg-white outline-none focus:ring-2 focus:ring-blue-300">
                                        <option value="Islam">Islam</option>
                                        <option value="Kristen">Kristen</option>
                                        <option value="Katolik">Katolik</option>
                                        <option value="Hindu">Hindu</option>
                                        <option value="Buddha">Buddha</option>
                                        <option value="Konghucu">Konghucu</option>
                                    </select>
                                </div>
                            </div>
                            <div class="grid grid-cols-2 gap-3">
                                <div>
                                    <label class="text-[10px] font-bold text-slate-500 uppercase block mb-1">Tempat Lahir</label>
                                    <input type="text" name="tempat_lahir" id="editTempatLahir" class="w-full border border-slate-200 rounded-lg p-2 text-[11px] outline-none focus:ring-2 focus:ring-blue-300">
                                </div>
                                <div>
                                    <label class="text-[10px] font-bold text-slate-500 uppercase block mb-1">Tanggal Lahir</label>
                                    <input type="date" name="tanggal_lahir" id="editTanggalLahir" class="w-full border border-slate-200 rounded-lg p-2 text-[11px] outline-none focus:ring-2 focus:ring-blue-300 text-slate-600">
                                </div>
                            </div>
                        </div>
                    </div>

                    {{-- Alamat --}}
                    <div class="pt-2 border-t border-slate-100 space-y-3">
                        <div>
                            <label class="text-[10px] font-bold text-slate-500 uppercase block mb-1">Alamat</label>
                            <textarea name="alamat" id="editAlamat" rows="2" class="w-full border border-slate-200 rounded-lg p-2 text-[11px] outline-none focus:ring-2 focus:ring-blue-300"></textarea>
                        </div>
                        <div class="grid grid-cols-2 md:grid-cols-4 gap-3">
                            <div>
                                <label class="text-[10px] font-bold text-slate-500 uppercase block mb-1">RT</label>
                                <input type="text" name="rt" id="editRt" class="w-full border border-slate-200 rounded-lg p-2 text-[11px] outline-none focus:ring-2 focus:ring-blue-300">
                            </div>
                            <div>
                                <label class="text-[10px] font-bold text-slate-500 uppercase block mb-1">RW</label>
                                <input type="text" name="rw" id="editRw" class="w-full border border-slate-200 rounded-lg p-2 text-[11px] outline-none focus:ring-2 focus:ring-blue-300">
                            </div>
                            <div>
                                <label class="text-[10px] font-bold text-slate-500 uppercase block mb-1">Dusun</label>
                                <input type="text" name="dusun" id="editDusun" class="w-full border border-slate-200 rounded-lg p-2 text-[11px] outline-none focus:ring-2 focus:ring-blue-300">
                            </div>
                            <div>
                                <label class="text-[10px] font-bold text-slate-500 uppercase block mb-1">Kode Pos</label>
                                <input type="text" name="kode_pos" id="editKodePos" class="w-full border border-slate-200 rounded-lg p-2 text-[11px] outline-none focus:ring-2 focus:ring-blue-300">
                            </div>
                        </div>
                        <div class="grid grid-cols-2 gap-3">
                            <div>
                                <label class="text-[10px] font-bold text-slate-500 uppercase block mb-1">Kelurahan / Desa</label>
                                <input type="text" name="kelurahan" id="editKelurahan" class="w-full border border-slate-200 rounded-lg p-2 text-[11px] outline-none focus:ring-2 focus:ring-blue-300">
                            </div>
                            <div>
                                <label class="text-[10px] font-bold text-slate-500 uppercase block mb-1">Kecamatan</label>
                                <input type="text" name="kecamatan" id="editKecamatan" class="w-full border border-slate-200 rounded-lg p-2 text-[11px] outline-none focus:ring-2 focus:ring-blue-300">
                            </div>
                        </div>
                    </div>

                    {{-- Data Ortu --}}
                    <div class="grid grid-cols-2 gap-3 pt-2 border-t border-slate-100">
                        <div>
                            <label class="text-[10px] font-bold text-slate-500 uppercase block mb-1">Nama Ayah</label>
                            <input type="text" name="nama_ayah" id="editNamaAyah" class="w-full border border-slate-200 rounded-lg p-2 text-[11px] outline-none focus:ring-2 focus:ring-blue-300">
                        </div>
                        <div>
                            <label class="text-[10px] font-bold text-slate-500 uppercase block mb-1">Pekerjaan Ayah</label>
                            <input type="text" name="pekerjaan_ayah" id="editPekerjaanAyah" class="w-full border border-slate-200 rounded-lg p-2 text-[11px] outline-none focus:ring-2 focus:ring-blue-300">
                        </div>
                        <div>
                            <label class="text-[10px] font-bold text-slate-500 uppercase block mb-1">Nama Ibu</label>
                            <input type="text" name="nama_ibu" id="editNamaIbu" class="w-full border border-slate-200 rounded-lg p-2 text-[11px] outline-none focus:ring-2 focus:ring-blue-300">
                        </div>
                        <div>
                            <label class="text-[10px] font-bold text-slate-500 uppercase block mb-1">Pekerjaan Ibu</label>
                            <input type="text" name="pekerjaan_ibu" id="editPekerjaanIbu" class="w-full border border-slate-200 rounded-lg p-2 text-[11px] outline-none focus:ring-2 focus:ring-blue-300">
                        </div>
                        <div>
                            <label class="text-[10px] font-bold text-slate-500 uppercase block mb-1">Nama Wali</label>
                            <input type="text" name="nama_wali" id="editNamaWali" class="w-full border border-slate-200 rounded-lg p-2 text-[11px] outline-none focus:ring-2 focus:ring-blue-300">
                        </div>
                        <div>
                            <label class="text-[10px] font-bold text-slate-500 uppercase block mb-1">Pekerjaan Wali</label>
                            <input type="text" name="pekerjaan_wali" id="editPekerjaanWali" class="w-full border border-slate-200 rounded-lg p-2 text-[11px] outline-none focus:ring-2 focus:ring-blue-300">
                        </div>
                        <div>
                            <label class="text-[10px] font-bold text-slate-500 uppercase block mb-1">No. Telepon</label>
                            <input type="text" name="no_telepon" id="editNoTelepon" class="w-full border border-slate-200 rounded-lg p-2 text-[11px] outline-none focus:ring-2 focus:ring-blue-300 font-bold text-slate-700">
                        </div>
                        <div>
                            <label class="text-[10px] font-bold text-slate-500 uppercase block mb-1">Penghasilan Orang Tua</label>
                            <input type="number" name="penghasilan_orang_tua" id="editPenghasilan" class="w-full border border-slate-200 rounded-lg p-2 text-[11px] outline-none focus:ring-2 focus:ring-blue-300">
                        </div>
                    </div>

                    <div class="flex justify-end gap-2 pt-3 border-t border-slate-100">
                        <button type="button" onclick="closeModal('modalEdit')"
                            class="px-4 py-1.5 border border-slate-300 rounded-lg font-bold text-[11px] text-slate-600 hover:bg-white transition">Batal</button>
                        <button type="submit"
                            class="px-5 py-1.5 bg-[#2563eb] text-white rounded-lg font-bold text-[11px] shadow-sm hover:bg-blue-700 transition">Simpan Perubahan</button>
                    </div>
                </form>
            </div>
        </div>
    </div>

    {{-- ===== MODAL IMPORT ===== --}}
    <div id="modalImport" style="display:none" class="fixed inset-0 bg-black/50 items-center justify-center z-50 backdrop-blur-sm">
        <div class="bg-white rounded-2xl w-full max-w-sm shadow-2xl p-5 mx-4">
            <div class="flex justify-between items-center mb-4 pb-3 border-b border-slate-100">
                <h3 class="font-bold text-sm text-slate-800">Import Data Siswa</h3>
                <button onclick="closeModal('modalImport')" class="text-slate-400 hover:text-red-500 transition w-6 h-6 rounded flex items-center justify-center hover:bg-slate-100 text-[10px]">
                    <i class="fa-solid fa-xmark"></i>
                </button>
            </div>
            <form method="POST" action="{{ route('bk.biodata.import') }}" enctype="multipart/form-data">
                @csrf
                <div class="space-y-4">
                    <div class="bg-amber-50 border border-amber-200 rounded-lg p-2.5 text-[10px] text-amber-700 flex items-start gap-1.5">
                        <i class="fa-solid fa-circle-info mt-0.5"></i>
                        <span>Gunakan template yang sudah disediakan. Pastikan format tahun ajaran sesuai (contoh: 2025/2026).</span>
                    </div>
                    <div>
                        <label class="text-[10px] font-bold text-slate-500 uppercase block mb-1.5">File Excel (.xlsx)</label>
                        <label class="border-2 border-dashed border-slate-300 rounded-xl p-5 text-center hover:bg-slate-50 hover:border-blue-400 transition cursor-pointer block">
                            <input type="file" name="file" accept=".xlsx,.xls" required class="hidden"
                                onchange="document.getElementById('importFileName').textContent = this.files[0]?.name ?? 'Pilih file'">
                            <i class="fa-solid fa-cloud-arrow-up text-2xl text-slate-300 mb-1 block"></i>
                            <p id="importFileName" class="text-[11px] text-slate-600 font-bold">Klik untuk pilih file</p>
                            <p class="text-[9px] text-slate-400 mt-0.5">Format: XLSX, XLS</p>
                        </label>
                    </div>
                    <div class="flex justify-end gap-2 pt-2">
                        <button type="button" onclick="closeModal('modalImport')"
                            class="px-4 py-1.5 rounded-lg border border-slate-200 text-slate-500 text-[11px] font-bold hover:bg-slate-50 transition">Batal</button>
                        <button type="submit"
                            class="px-5 py-1.5 rounded-lg bg-[#16a34a] text-white text-[11px] font-bold hover:bg-green-700 shadow-sm transition">Import Sekarang</button>
                    </div>
                </div>
            </form>
        </div>
    </div>

    <form id="deleteForm" method="POST" action="" class="hidden">@csrf</form>

    @push('scripts')
    <script>
        const staticBase = "{{ rtrim(preg_replace('#/api$#', '', env('API_BASE_URL')), '/') }}";

        // ── SCRIPT AJAX EVENT DELEGATION ────────────────────────
        document.addEventListener('DOMContentLoaded', () => {

            // Tangkap Ganti Filter Kelas
            document.addEventListener('change', e => {
                if (e.target.matches('.auto-submit')) {
                    const form = e.target.closest('form');
                    if (form) executeAjaxFilter(form);
                }
            });

            // Tangkap Form Submit (Pencarian Text)
            document.addEventListener('submit', e => {
                const form = e.target;
                if (form.id === 'filterFormBiodata') {
                    e.preventDefault();
                    executeAjaxFilter(form);
                }
            });

            // Tangkap Pagination Links
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
            params.set('page', 1); // kembalikan ke hal 1 saat filter diganti

            reloadTableData(url.pathname + '?' + params.toString());
        }

        async function reloadTableData(url) {
            const container = document.getElementById('table-container');
            container.style.opacity = '0.5';
            container.style.pointerEvents = 'none';

            try {
                const res = await fetch(url, { headers: { 'X-Requested-With': 'XMLHttpRequest' } });
                const html = await res.text();

                // Gunakan parseFromString yang valid
                const doc = new DOMParser().parseFromString(html, 'text/html');

                // Update Tabel & Paginasi
                const newContainer = doc.getElementById('table-container');
                if (newContainer) {
                    container.innerHTML = newContainer.innerHTML;
                    window.history.pushState({}, '', url);
                } else {
                    window.location.href = url; // Fallback
                }

                // Update Link Download Excel agar tersinkronisasi
                const newExcel = doc.getElementById('btn-excel');
                if(newExcel) document.getElementById('btn-excel').href = newExcel.href;

            } catch (e) {
                console.error("Gagal reload data:", e);
            } finally {
                container.style.opacity = '1';
                container.style.pointerEvents = 'auto';
            }
        }


        // ── SCRIPT BAWAAN ────────────────────────
        function openModal(id)  { document.getElementById(id).style.display = 'flex'; }
        function closeModal(id) { document.getElementById(id).style.display = 'none'; }

        ['modalTambah','modalEdit','modalImport'].forEach(id => {
            document.getElementById(id).addEventListener('click', function(e) {
                if (e.target === this) closeModal(id);
            });
        });

        function openEditModal(s) {
            document.getElementById('editForm').action = `/bk/biodata/${s.id}/update`;

            // Identitas
            document.getElementById('editNama').value         = s.nama           ?? '';
            document.getElementById('editKelas').value        = s.kelas          ?? '';
            document.getElementById('editNipd').value         = s.nipd           ?? '';
            document.getElementById('editNik').value          = s.nik            ?? '';
            document.getElementById('editNisn').value         = s.nisn           ?? '';
            document.getElementById('editJenisKelamin').value = s.jenis_kelamin  ?? 'L';
            document.getElementById('editAgama').value        = s.agama          ?? 'Islam';
            document.getElementById('editTempatLahir').value  = s.tempat_lahir   ?? '';
            document.getElementById('editTanggalLahir').value = s.tanggal_lahir
                ? String(s.tanggal_lahir).substring(0, 10) : '';

            // Alamat
            document.getElementById('editAlamat').value    = s.alamat    ?? '';
            document.getElementById('editRt').value        = s.rt        ?? '';
            document.getElementById('editRw').value        = s.rw        ?? '';
            document.getElementById('editDusun').value     = s.dusun     ?? '';
            document.getElementById('editKelurahan').value = s.kelurahan ?? '';
            document.getElementById('editKecamatan').value = s.kecamatan ?? '';
            document.getElementById('editKodePos').value   = s.kode_pos  ?? '';

            // Ortu
            document.getElementById('editNamaAyah').value      = s.nama_ayah      ?? '';
            document.getElementById('editPekerjaanAyah').value = s.pekerjaan_ayah ?? '';
            document.getElementById('editNamaIbu').value       = s.nama_ibu       ?? '';
            document.getElementById('editPekerjaanIbu').value  = s.pekerjaan_ibu  ?? '';
            document.getElementById('editNamaWali').value      = s.nama_wali      ?? '';
            document.getElementById('editPekerjaanWali').value = s.pekerjaan_wali ?? '';
            document.getElementById('editNoTelepon').value     = s.no_telepon     ?? '';
            document.getElementById('editPenghasilan').value   = s.penghasilan_orang_tua ?? 0;

            // Foto
            const preview = document.getElementById('editFotoPreview');
            preview.innerHTML = s.gambar
                ? `<img src="${staticBase}/${s.gambar}" class="w-full h-full object-cover rounded-full">`
                : `<i class="fa-solid fa-user text-2xl text-slate-300"></i>`;

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

        @if(session('success'))
            Swal.fire({ icon: 'success', title: 'Berhasil!', text: {!! json_encode(session('success')) !!}, showConfirmButton: false, timer: 2000 });
        @endif
        @if(session('error'))
            Swal.fire({ icon: 'error', title: 'Gagal!', text: {!! json_encode(session('error')) !!}, confirmButtonColor: '#ef4444' });
        @endif
        @if($errors->any())
            Swal.fire({
                icon: 'error', title: 'Peringatan Validasi!',
                html: `<div class="text-left text-[11px] text-red-600 mt-2"><ul class="list-disc pl-5">@foreach ($errors->all() as $error)<li>{{ $error }}</li>@endforeach</ul></div>`,
                confirmButtonColor: '#ef4444',
            });
        @endif
    </script>
    @endpush

</x-layout-app>
