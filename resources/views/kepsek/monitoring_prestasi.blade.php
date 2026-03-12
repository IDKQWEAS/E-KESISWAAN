<x-layout-app title="Monitoring Prestasi" :role="$role">

    <div class="w-full space-y-6 fade-in">

        <div class="bg-white rounded-3xl border border-slate-200 shadow-card p-6 space-y-6">

        {{-- Header & Filter --}}
        <div class="flex flex-wrap justify-between items-end gap-3">
            <div>
                <h2 class="text-2xl font-bold text-slate-800">Galeri Prestasi Siswa</h2>
                <p class="text-slate-500 text-sm mt-1">Monitoring pencapaian akademik dan non-akademik.</p>
            </div>

            <form method="GET" action="{{ route('kepsek.prestasi') }}" id="formFilter" class="flex flex-wrap gap-2 items-center">

                {{-- Preserve id_tahun_ajaran dari header global --}}
                @if($reqIdTa)
                    <input type="hidden" name="id_tahun_ajaran" value="{{ $reqIdTa }}">
                @endif
                <input type="hidden" name="page" value="1">

                {{-- Filter Kategori --}}
                <select name="kategori" onchange="document.getElementById('formFilter').submit()"
                    class="border border-slate-200 p-2.5 rounded-xl text-sm font-bold text-slate-600 bg-white outline-none cursor-pointer shadow-sm">
                    <option value="">Semua Kategori</option>
                    <option value="akademik"     {{ $filterKategori === 'akademik'     ? 'selected' : '' }}>Akademik</option>
                    <option value="non-akademik" {{ $filterKategori === 'non-akademik' ? 'selected' : '' }}>Non-Akademik</option>
                </select>

                {{-- Filter Tingkat --}}
                <select name="tingkat" onchange="document.getElementById('formFilter').submit()"
                    class="border border-slate-200 p-2.5 rounded-xl text-sm font-bold text-slate-600 bg-white outline-none cursor-pointer shadow-sm">
                    <option value="">Semua Tingkat</option>
                    @foreach(['kecamatan','kabupaten/kota','provinsi','nasional','internasional'] as $t)
                        <option value="{{ $t }}" {{ $filterTingkat === $t ? 'selected' : '' }}>
                            {{ ucfirst($t) }}
                        </option>
                    @endforeach
                </select>
            </form>
        </div>

        {{-- Grid Prestasi --}}
        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">

            @forelse($prestasi as $item)
                @php
                    $tanggal       = date('d M Y', strtotime($item['tanggal']));
                    $kategoriLabel = ucfirst($item['kategori'] ?? '');
                    $tingkatLabel  = ucfirst($item['tingkat']  ?? '');
                    $gambarRaw     = $item['gambar'] ?? '';

                    if ($gambarRaw) {
                        $imgUrl = str_starts_with($gambarRaw, 'uploads/')
                            ? env('API_BASE_URL') . '/' . $gambarRaw
                            : env('API_BASE_URL') . '/uploads/' . $gambarRaw;
                    } else {
                        $imgUrl = 'https://images.unsplash.com/photo-1571260899304-425eee4c7efc?w=500&q=80';
                    }

                    $tingkatColor = match($item['tingkat'] ?? '') {
                        'internasional' => 'bg-purple-500 text-white',
                        'nasional'      => 'bg-red-500 text-white',
                        'provinsi'      => 'bg-blue-500 text-white',
                        'kabupaten/kota'=> 'bg-green-500 text-white',
                        default         => 'bg-yellow-500 text-black',
                    };
                @endphp

                <div
                    class="group bg-white rounded-[20px] border border-slate-200 overflow-hidden hover:shadow-xl transition-all duration-300 cursor-pointer"
                    data-prestasi='@json($item)'
                    data-img="{{ $imgUrl }}"
                    onclick="openDetailPrestasi(this)"
                >
                    <div class="h-48 overflow-hidden relative bg-slate-100">
                        <img
                            src="{{ $imgUrl }}"
                            class="w-full h-full object-cover group-hover:scale-105 transition duration-700"
                            onerror="this.src='https://images.unsplash.com/photo-1571260899304-425eee4c7efc?w=500&q=80'"
                        >
                        <div class="absolute inset-0 bg-gradient-to-t from-black/70 to-transparent"></div>
                        <div class="absolute bottom-4 left-4 text-white">
                            <span class="{{ $tingkatColor }} text-[10px] font-bold px-2 py-0.5 rounded uppercase tracking-wide">
                                {{ $tingkatLabel }}
                            </span>
                            <div class="text-xs font-bold opacity-90 uppercase mt-1">
                                {{ $kategoriLabel }}
                            </div>
                        </div>
                    </div>

                    <div class="p-5">
                        <h3 class="font-bold text-base text-slate-800 mb-2 leading-tight group-hover:text-primary transition">
                            {{ $item['peringkat'] ?? '' }} — {{ $item['nama_lomba'] ?? '' }}
                        </h3>
                        <p class="text-sm text-slate-600 font-medium mb-1 flex items-center gap-2">
                            <i class="fa-solid fa-user-graduate text-slate-400"></i>
                            {{ $item['nama_siswa'] ?? '-' }}
                        </p>
                        @if(!empty($item['penyelenggara']))
                            <p class="text-xs text-slate-400 mb-3 flex items-center gap-2">
                                <i class="fa-solid fa-building text-slate-300"></i>
                                {{ $item['penyelenggara'] }}
                            </p>
                        @endif
                        <div class="flex justify-between items-center border-t border-slate-100 pt-3">
                            <span class="text-xs text-slate-400 font-mono">
                                <i class="fa-regular fa-calendar mr-1"></i>
                                {{ $tanggal }}
                            </span>
                            <span class="text-primary text-xs font-bold flex items-center gap-1 group-hover:translate-x-1 transition">
                                Detail <i class="fa-solid fa-arrow-right"></i>
                            </span>
                        </div>
                    </div>
                </div>

            @empty
                <div class="col-span-full py-12 text-center text-slate-500 font-medium bg-white rounded-3xl border border-slate-200">
                    <i class="fa-solid fa-trophy text-3xl text-slate-200 mb-3 block"></i>
                    @if($filterKategori || $filterTingkat)
                        Tidak ada data prestasi untuk filter yang dipilih.
                    @else
                        Belum ada data prestasi yang tercatat pada tahun ajaran ini.
                    @endif
                </div>
            @endforelse

        </div>

        {{-- Pagination --}}
        @if(($pagination['totalPages'] ?? 1) > 1)
        <div class="flex justify-between items-center text-xs text-slate-500 pt-2">
            <span>
                Halaman <span class="font-bold">{{ $page }}</span> dari
                <span class="font-bold">{{ $pagination['totalPages'] ?? 1 }}</span>
                &mdash; Total <span class="font-bold">{{ $pagination['total'] ?? 0 }}</span> prestasi
            </span>
            <div class="flex gap-1">
                @if($page > 1)
                    <a href="{{ request()->fullUrlWithQuery(['page' => $page - 1]) }}"
                       class="px-3 py-1 bg-white border border-slate-200 rounded-lg hover:bg-slate-50">Prev</a>
                @else
                    <button disabled class="px-3 py-1 bg-white border border-slate-200 rounded-lg opacity-40 cursor-not-allowed">Prev</button>
                @endif

                @for($p = max(1, $page - 2); $p <= min($pagination['totalPages'] ?? 1, $page + 2); $p++)
                    <a href="{{ request()->fullUrlWithQuery(['page' => $p]) }}"
                       class="px-3 py-1 rounded-lg {{ $p === $page ? 'bg-primary text-white' : 'bg-white border border-slate-200 hover:bg-slate-50' }}">
                        {{ $p }}
                    </a>
                @endfor

                @if($page < ($pagination['totalPages'] ?? 1))
                    <a href="{{ request()->fullUrlWithQuery(['page' => $page + 1]) }}"
                       class="px-3 py-1 bg-white border border-slate-200 rounded-lg hover:bg-slate-50">Next</a>
                @else
                    <button disabled class="px-3 py-1 bg-white border border-slate-200 rounded-lg opacity-40 cursor-not-allowed">Next</button>
                @endif
            </div>
        </div>
        @endif

        </div>{{-- /card --}}

    </div>

    {{-- Modal Detail Prestasi --}}
    <div
        id="modalDetailPrestasi"
        class="fixed inset-0 bg-black/60 hidden items-center justify-center z-50 backdrop-blur-sm"
    >
        <div class="bg-white rounded-3xl w-full max-w-4xl shadow-2xl flex overflow-hidden relative mx-4" style="max-height:90vh">

            <button
                onclick="toggleModal('modalDetailPrestasi')"
                class="absolute top-4 right-4 z-10 w-8 h-8 rounded-full bg-black/50 text-white flex items-center justify-center hover:bg-black/70 transition"
            >
                <i class="fa-solid fa-xmark"></i>
            </button>

            <div class="w-2/5 bg-slate-100 relative hidden md:flex flex-shrink-0">
                <img
                    id="modal-img"
                    src=""
                    class="w-full h-full object-cover"
                    onerror="this.src='https://images.unsplash.com/photo-1571260899304-425eee4c7efc?w=500&q=80'"
                >
            </div>

            <div class="w-full md:w-3/5 p-8 overflow-y-auto flex flex-col gap-5">
                <div>
                    <div class="flex gap-2 mb-3">
                        <span id="modal-kategori" class="bg-blue-100 text-blue-700 text-xs font-bold px-3 py-1 rounded-full uppercase tracking-wide inline-block"></span>
                        <span id="modal-tingkat-badge" class="bg-yellow-100 text-yellow-700 text-xs font-bold px-3 py-1 rounded-full uppercase tracking-wide inline-block"></span>
                    </div>
                    <h2 id="modal-judul" class="text-2xl font-extrabold text-slate-900 leading-tight mb-1"></h2>
                    <p  id="modal-tanggal" class="text-slate-500 font-medium flex items-center gap-2 text-sm"></p>
                </div>

                <div class="bg-slate-50 p-4 rounded-xl border border-slate-200">
                    <p class="text-[10px] text-slate-400 font-bold uppercase mb-2">Siswa Berprestasi</p>
                    <div class="flex items-center gap-3">
                        <div id="modal-inisial" class="w-10 h-10 rounded-full bg-primary text-white flex items-center justify-center font-bold shadow-md flex-shrink-0"></div>
                        <div>
                            <p id="modal-siswa" class="font-bold text-slate-800 text-sm"></p>
                            <p id="modal-nisn"  class="text-xs text-slate-500"></p>
                        </div>
                    </div>
                </div>

                <div class="grid grid-cols-2 gap-4">
                    <div>
                        <p class="text-[10px] text-slate-400 font-bold uppercase mb-1">Tingkat</p>
                        <p id="modal-tingkat" class="text-slate-800 font-bold text-sm uppercase"></p>
                    </div>
                    <div>
                        <p class="text-[10px] text-slate-400 font-bold uppercase mb-1">Tahun Ajaran</p>
                        <p id="modal-tahun" class="text-slate-800 font-bold text-sm"></p>
                    </div>
                    <div class="col-span-2">
                        <p class="text-[10px] text-slate-400 font-bold uppercase mb-1">Penyelenggara</p>
                        <p id="modal-penyelenggara" class="text-slate-800 text-sm"></p>
                    </div>
                    <div class="col-span-2" id="wrap-keterangan">
                        <p class="text-[10px] text-slate-400 font-bold uppercase mb-1">Keterangan</p>
                        <p id="modal-keterangan" class="text-slate-600 text-sm leading-relaxed"></p>
                    </div>
                </div>
            </div>
        </div>
    </div>

    @push('scripts')
    <script>
        function toggleModal(id) {
            const el = document.getElementById(id);
            el.classList.toggle('hidden');
            el.classList.toggle('flex');
        }

        function buildImgUrl(gambar) {
            const base = "{{ env('API_BASE_URL') }}";
            if (!gambar) return 'https://images.unsplash.com/photo-1571260899304-425eee4c7efc?w=500&q=80';
            if (gambar.startsWith('uploads/')) return base + '/' + gambar;
            return base + '/uploads/' + gambar;
        }

        function openDetailPrestasi(element) {
            const data   = JSON.parse(element.getAttribute('data-prestasi'));
            const imgUrl = element.getAttribute('data-img');

            const dateObj       = new Date(data.tanggal);
            const formattedDate = dateObj.toLocaleDateString('id-ID', {
                day: 'numeric', month: 'short', year: 'numeric'
            });

            const capitalize = (str) => str ? str.charAt(0).toUpperCase() + str.slice(1) : '-';

            document.getElementById('modal-img').src                  = imgUrl || buildImgUrl(data.gambar);
            document.getElementById('modal-judul').innerText          = (data.peringkat ?? '') + ' — ' + (data.nama_lomba ?? '');
            document.getElementById('modal-kategori').innerText       = capitalize(data.kategori);
            document.getElementById('modal-tingkat-badge').innerText  = capitalize(data.tingkat);
            document.getElementById('modal-tanggal').innerHTML        =
                '<i class="fa-regular fa-calendar text-blue-500 mr-2"></i>' + formattedDate;
            document.getElementById('modal-siswa').innerText          = data.nama_siswa ?? '-';
            document.getElementById('modal-nisn').innerText           = 'NISN: ' + (data.nisn ?? '-');
            document.getElementById('modal-inisial').innerText        = (data.nama_siswa ?? 'XX').substring(0, 2).toUpperCase();
            document.getElementById('modal-tingkat').innerText        = capitalize(data.tingkat);
            document.getElementById('modal-tahun').innerText          = data.tahun_ajaran ?? '-';
            document.getElementById('modal-penyelenggara').innerText  = data.penyelenggara ?? '-';

            const keterangan = data.keterangan ?? '';
            document.getElementById('wrap-keterangan').style.display = keterangan ? 'block' : 'none';
            document.getElementById('modal-keterangan').innerText    = keterangan;

            toggleModal('modalDetailPrestasi');
        }

        // Tutup modal saat klik backdrop
        document.getElementById('modalDetailPrestasi').addEventListener('click', function(e) {
            if (e.target === this) toggleModal('modalDetailPrestasi');
        });
    </script>
    @endpush

</x-layout-app>
