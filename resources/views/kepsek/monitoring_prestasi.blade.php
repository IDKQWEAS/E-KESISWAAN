<x-layout-app title="Monitoring Prestasi" :role="$role">

    <div class="w-full space-y-4 fade-in pb-8">

        <div class="bg-white rounded-2xl border border-slate-200 shadow-sm p-5 space-y-5">

        {{-- Header & Filter --}}
        <div class="flex flex-wrap justify-between items-end gap-3 border-b border-slate-100 pb-4">
            <div>
                <h2 class="text-lg font-bold text-slate-800">Galeri Prestasi Siswa</h2>
                <p class="text-slate-500 text-[11px] mt-0.5">Monitoring pencapaian akademik dan non-akademik.</p>
            </div>

            <form method="GET" action="{{ route('kepsek.prestasi') }}" id="formFilter" class="flex flex-wrap gap-2 items-center">
                {{-- Preserve id_tahun_ajaran dari header global --}}
                @if($reqIdTa)
                    <input type="hidden" name="id_tahun_ajaran" value="{{ $reqIdTa }}">
                @endif
                <input type="hidden" name="page" value="1">

                {{-- Filter Kategori --}}
                <div class="relative">
                    <select name="kategori" class="auto-submit appearance-none border border-slate-200 pl-3 pr-8 py-1.5 rounded-lg text-[11px] font-bold text-slate-600 bg-white outline-none cursor-pointer focus:ring-2 focus:ring-blue-500/20 shadow-sm h-[32px]">
                        <option value="">Semua Kategori</option>
                        <option value="akademik"     {{ $filterKategori === 'akademik'     ? 'selected' : '' }}>Akademik</option>
                        <option value="non-akademik" {{ $filterKategori === 'non-akademik' ? 'selected' : '' }}>Non-Akademik</option>
                    </select>
                    <i class="fa-solid fa-chevron-down absolute right-3 top-1/2 -translate-y-1/2 text-[9px] text-slate-400 pointer-events-none"></i>
                </div>

                {{-- Filter Tingkat --}}
                <div class="relative">
                    <select name="tingkat" class="auto-submit appearance-none border border-slate-200 pl-3 pr-8 py-1.5 rounded-lg text-[11px] font-bold text-slate-600 bg-white outline-none cursor-pointer focus:ring-2 focus:ring-blue-500/20 shadow-sm h-[32px]">
                        <option value="">Semua Tingkat</option>
                        @foreach(['kecamatan','kabupaten/kota','provinsi','nasional','internasional'] as $t)
                            <option value="{{ $t }}" {{ $filterTingkat === $t ? 'selected' : '' }}>
                                {{ ucfirst($t) }}
                            </option>
                        @endforeach
                    </select>
                    <i class="fa-solid fa-chevron-down absolute right-3 top-1/2 -translate-y-1/2 text-[9px] text-slate-400 pointer-events-none"></i>
                </div>
            </form>
        </div>

        {{-- BUNGKUS DENGAN AJAX ID --}}
        <div id="prestasi-container" class="transition-opacity duration-300">

            {{-- Grid Prestasi --}}
            <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 gap-5">

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
                        class="group bg-white rounded-2xl border border-slate-200 overflow-hidden hover:shadow-lg transition-all duration-300 cursor-pointer flex flex-col"
                        data-prestasi='@json($item)'
                        data-img="{{ $imgUrl }}"
                        onclick="openDetailPrestasi(this)"
                    >
                        <div class="h-40 overflow-hidden relative bg-slate-100 flex-shrink-0">
                            <img
                                src="{{ $imgUrl }}"
                                class="w-full h-full object-cover group-hover:scale-105 transition duration-700"
                                onerror="this.src='https://images.unsplash.com/photo-1571260899304-425eee4c7efc?w=500&q=80'"
                            >
                            <div class="absolute inset-0 bg-gradient-to-t from-black/70 to-transparent"></div>
                            <div class="absolute bottom-3 left-3 text-white">
                                <span class="{{ $tingkatColor }} text-[9px] font-bold px-2 py-0.5 rounded uppercase tracking-wide shadow-sm">
                                    {{ $tingkatLabel }}
                                </span>
                                <div class="text-[11px] font-bold opacity-90 uppercase mt-1">
                                    {{ $kategoriLabel }}
                                </div>
                            </div>
                        </div>

                        <div class="p-4 flex-1 flex flex-col justify-between">
                            <div>
                                <h3 class="font-bold text-sm text-slate-800 mb-1.5 leading-tight group-hover:text-primary transition line-clamp-2">
                                    {{ $item['peringkat'] ?? '' }} — {{ $item['nama_lomba'] ?? '' }}
                                </h3>
                                <p class="text-[11px] text-slate-600 font-medium mb-1 flex items-center gap-1.5">
                                    <i class="fa-solid fa-user-graduate text-slate-400"></i>
                                    <span class="truncate">{{ $item['nama_siswa'] ?? '-' }}</span>
                                </p>
                                @if(!empty($item['penyelenggara']))
                                    <p class="text-[10px] text-slate-400 mb-3 flex items-start gap-1.5">
                                        <i class="fa-solid fa-building text-slate-300 mt-0.5"></i>
                                        <span class="line-clamp-1">{{ $item['penyelenggara'] }}</span>
                                    </p>
                                @endif
                            </div>

                            <div class="flex justify-between items-center border-t border-slate-100 pt-2.5 mt-auto">
                                <span class="text-[10px] text-slate-400 font-mono flex items-center gap-1">
                                    <i class="fa-regular fa-calendar"></i> {{ $tanggal }}
                                </span>
                                <span class="text-primary text-[10px] font-bold flex items-center gap-1 group-hover:translate-x-1 transition">
                                    Detail <i class="fa-solid fa-arrow-right"></i>
                                </span>
                            </div>
                        </div>
                    </div>

                @empty
                    <div class="col-span-full py-12 text-center text-slate-500 font-medium bg-slate-50 rounded-2xl border border-slate-200 border-dashed">
                        <i class="fa-solid fa-trophy text-3xl text-slate-300 mb-2 block"></i>
                        <span class="text-[11px]">
                            @if($filterKategori || $filterTingkat)
                                Tidak ada data prestasi untuk filter yang dipilih.
                            @else
                                Belum ada data prestasi yang tercatat pada tahun ajaran ini.
                            @endif
                        </span>
                    </div>
                @endforelse

            </div>

            {{-- Pagination --}}
            @if(($pagination['totalPages'] ?? 1) > 1)
            <div class="flex flex-col md:flex-row justify-between items-center text-[10px] text-slate-500 pt-5 mt-4 border-t border-slate-100">
                <span class="mb-2 md:mb-0">
                    Halaman <span class="font-bold">{{ $page }}</span> dari
                    <span class="font-bold">{{ $pagination['totalPages'] ?? 1 }}</span>
                    &mdash; Total <span class="font-bold">{{ $pagination['total'] ?? 0 }}</span> prestasi
                </span>
                <div class="flex gap-1">
                    @if($page > 1)
                        <a href="{{ request()->fullUrlWithQuery(['page' => $page - 1]) }}"
                           class="ajax-link px-2.5 py-1.5 bg-white border border-slate-200 rounded-lg hover:bg-slate-50 transition">Prev</a>
                    @else
                        <button disabled class="px-2.5 py-1.5 bg-white border border-slate-200 rounded-lg opacity-40 cursor-not-allowed">Prev</button>
                    @endif

                    @for($p = max(1, $page - 2); $p <= min($pagination['totalPages'] ?? 1, $page + 2); $p++)
                        <a href="{{ request()->fullUrlWithQuery(['page' => $p]) }}"
                           class="ajax-link px-2.5 py-1.5 rounded-lg transition {{ $p === $page ? 'bg-[#2563eb] text-white shadow-sm font-bold' : 'bg-white border border-slate-200 hover:bg-slate-50' }}">
                            {{ $p }}
                        </a>
                    @endfor

                    @if($page < ($pagination['totalPages'] ?? 1))
                        <a href="{{ request()->fullUrlWithQuery(['page' => $page + 1]) }}"
                           class="ajax-link px-2.5 py-1.5 bg-white border border-slate-200 rounded-lg hover:bg-slate-50 transition">Next</a>
                    @else
                        <button disabled class="px-2.5 py-1.5 bg-white border border-slate-200 rounded-lg opacity-40 cursor-not-allowed">Next</button>
                    @endif
                </div>
            </div>
            @endif

        </div>{{-- /prestasi-container --}}

        </div>{{-- /card utama --}}

    </div>

    {{-- Modal Detail Prestasi --}}
    <div
        id="modalDetailPrestasi"
        class="fixed inset-0 bg-black/60 hidden items-center justify-center z-50 backdrop-blur-sm"
    >
        <div class="bg-white rounded-2xl w-full max-w-3xl shadow-2xl flex overflow-hidden relative mx-4" style="max-height:85vh">

            <button
                onclick="toggleModal('modalDetailPrestasi')"
                class="absolute top-3 right-3 z-10 w-7 h-7 rounded-lg bg-black/50 text-white flex items-center justify-center hover:bg-black/70 transition text-[11px]"
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

            <div class="w-full md:w-3/5 p-6 overflow-y-auto custom-scroll flex flex-col gap-4">
                <div>
                    <div class="flex gap-2 mb-2">
                        <span id="modal-kategori" class="bg-blue-100 text-blue-700 text-[10px] font-bold px-2.5 py-0.5 rounded border border-blue-200 uppercase tracking-wide inline-block"></span>
                        <span id="modal-tingkat-badge" class="bg-yellow-100 text-yellow-700 text-[10px] font-bold px-2.5 py-0.5 rounded border border-yellow-200 uppercase tracking-wide inline-block"></span>
                    </div>
                    <h2 id="modal-judul" class="text-lg font-extrabold text-slate-900 leading-tight mb-1"></h2>
                    <p  id="modal-tanggal" class="text-slate-500 font-medium flex items-center gap-1.5 text-[11px]"></p>
                </div>

                <div class="bg-slate-50 p-3.5 rounded-xl border border-slate-200 shadow-inner">
                    <p class="text-[9px] text-slate-400 font-bold uppercase mb-1.5">Siswa Berprestasi</p>
                    <div class="flex items-center gap-2.5">
                        <div id="modal-inisial" class="w-9 h-9 rounded-full bg-primary text-white flex items-center justify-center font-bold text-xs shadow-sm flex-shrink-0"></div>
                        <div>
                            <p id="modal-siswa" class="font-bold text-slate-800 text-[13px]"></p>
                            <p id="modal-nisn"  class="text-[10px] text-slate-500 font-mono"></p>
                        </div>
                    </div>
                </div>

                <div class="grid grid-cols-2 gap-4">
                    <div>
                        <p class="text-[9px] text-slate-400 font-bold uppercase mb-0.5">Tingkat</p>
                        <p id="modal-tingkat" class="text-slate-800 font-bold text-xs uppercase"></p>
                    </div>
                    <div>
                        <p class="text-[9px] text-slate-400 font-bold uppercase mb-0.5">Tahun Ajaran</p>
                        <p id="modal-tahun" class="text-slate-800 font-bold text-xs"></p>
                    </div>
                    <div class="col-span-2">
                        <p class="text-[9px] text-slate-400 font-bold uppercase mb-0.5">Penyelenggara</p>
                        <p id="modal-penyelenggara" class="text-slate-800 text-xs"></p>
                    </div>
                    <div class="col-span-2" id="wrap-keterangan">
                        <p class="text-[9px] text-slate-400 font-bold uppercase mb-0.5">Keterangan</p>
                        <p id="modal-keterangan" class="text-slate-600 text-[11px] leading-relaxed"></p>
                    </div>
                </div>
            </div>
        </div>
    </div>

    @push('scripts')
    <script>
        // ── SCRIPT AJAX EVENT DELEGATION ────────────────────────
        document.addEventListener('DOMContentLoaded', () => {

            // Tangkap Ganti Filter Dropdown
            document.addEventListener('change', e => {
                if (e.target.matches('.auto-submit')) {
                    const form = e.target.closest('form');
                    if (form) executeAjaxFilter(form);
                }
            });

            // Tangkap Pagination Links
            document.addEventListener('click', e => {
                const link = e.target.closest('.ajax-link');
                if (link) {
                    e.preventDefault();
                    reloadContainerData(link.href);
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
            params.set('page', 1); // Reset page ke 1 saat filter diganti

            reloadContainerData(url.pathname + '?' + params.toString());
        }

        async function reloadContainerData(url) {
            const container = document.getElementById('prestasi-container');
            container.style.opacity = '0.5';
            container.style.pointerEvents = 'none';

            try {
                const res = await fetch(url, { headers: { 'X-Requested-With': 'XMLHttpRequest' } });
                const html = await res.text();

                const doc = new DOMParser().parseFromString(html, 'text/html');
                const newContainer = doc.getElementById('prestasi-container');

                if (newContainer) {
                    container.innerHTML = newContainer.innerHTML;
                    window.history.pushState({}, '', url);
                } else {
                    window.location.href = url; // Fallback
                }
            } catch (e) {
                console.error("Gagal reload data:", e);
            } finally {
                container.style.opacity = '1';
                container.style.pointerEvents = 'auto';
            }
        }

        // ── SCRIPT MODAL BAWAAN ────────────────────────
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
                '<i class="fa-regular fa-calendar text-blue-500 mr-1.5"></i>' + formattedDate;
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

        document.getElementById('modalDetailPrestasi').addEventListener('click', function(e) {
            if (e.target === this) toggleModal('modalDetailPrestasi');
        });
    </script>
    @endpush

</x-layout-app>
