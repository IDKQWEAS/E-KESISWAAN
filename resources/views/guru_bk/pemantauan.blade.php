<x-layout-app title="Pemantauan Siswa" :role="$role">

    <div class="space-y-6 fade-in">

        {{-- Header Card --}}
        <div class="bg-white p-6 rounded-3xl shadow-card border border-slate-200 flex flex-col sm:flex-row justify-between items-start sm:items-center gap-4">
            <div>
                <h3 class="font-bold text-lg text-slate-800">Daftar Siswa Dalam Pantauan</h3>
                <p class="text-xs text-slate-400 mt-0.5">Diurutkan berdasarkan akumulasi poin pelanggaran tertinggi.</p>
            </div>
            <form method="GET" action="{{ route('bk.pemantauan') }}" class="flex gap-2 w-full sm:w-auto">
                <div class="relative flex-1 sm:w-64">
                    <i class="fa-solid fa-search absolute left-4 top-1/2 -translate-y-1/2 text-slate-400 text-xs pointer-events-none"></i>
                    <input
                        type="text"
                        name="search"
                        value="{{ $search }}"
                        placeholder="Cari Siswa..."
                        class="pl-10 pr-4 py-2.5 bg-slate-50 border border-slate-200 rounded-xl text-xs w-full focus:ring-2 focus:ring-primary/50 outline-none transition font-medium"
                    />
                </div>
                <button type="submit" class="px-4 py-2.5 bg-primary text-white rounded-xl text-xs font-bold hover:bg-blue-700 transition">Cari</button>
                @if($search)
                    <a href="{{ route('bk.pemantauan') }}" class="px-4 py-2.5 bg-white border border-slate-200 text-slate-500 rounded-xl text-xs font-bold hover:bg-slate-50 transition">Reset</a>
                @endif
            </form>
        </div>

        {{-- Daftar Siswa --}}
        <div class="space-y-4">
            @forelse($siswaList as $s)
                @php
                    $poin = $s['total_poin'] ?? 0;
                    $barColor   = $poin >= 75 ? 'bg-red-500'    : ($poin >= 40 ? 'bg-orange-400' : ($poin >= 15 ? 'bg-yellow-400' : 'bg-green-400'));
                    $badgeColor = $poin >= 75 ? 'bg-red-100 text-red-600 border-red-200' : ($poin >= 40 ? 'bg-orange-100 text-orange-600 border-orange-200' : ($poin >= 15 ? 'bg-yellow-100 text-yellow-700 border-yellow-200' : 'bg-green-100 text-green-700 border-green-200'));
                    $hoverColor = $poin >= 75 ? 'group-hover:text-red-600' : ($poin >= 40 ? 'group-hover:text-orange-600' : ($poin >= 15 ? 'group-hover:text-yellow-600' : 'group-hover:text-green-600'));

                    $riwayat  = is_string($s['riwayat_pelanggaran'] ?? null) ? json_decode($s['riwayat_pelanggaran'], true) : ($s['riwayat_pelanggaran'] ?? []);
                    $riwayat  = array_values(array_filter($riwayat ?? []));
                    $ringkasan = collect($riwayat)->pluck('pelanggaran')->filter()->unique()->take(3)->implode(', ');
                    $extra     = count($riwayat) > 3 ? ', ...' : '';
                @endphp

                <div
                    class="bg-white p-5 rounded-2xl border border-slate-200 shadow-sm hover:shadow-md transition cursor-pointer group flex items-center justify-between"
                    onclick="bukaDetailSiswa('{{ $s['id'] }}', this)"
                    data-id="{{ $s['id'] }}"
                    data-nama="{{ $s['nama_siswa'] }}"
                    data-kelas="{{ $s['kelas'] }}"
                    data-poin="{{ $poin }}"
                    data-nisn="{{ $s['nisn'] ?? '-' }}"
                >
                    <div class="flex items-center gap-4">
                        <div class="w-1.5 h-12 {{ $barColor }} rounded-full flex-shrink-0"></div>
                        <div>
                            <h4 class="font-bold text-slate-800 text-lg {{ $hoverColor }} transition">
                                {{ $s['nama_siswa'] }}
                                <span class="ml-2 bg-slate-100 text-slate-500 text-[10px] px-2 py-0.5 rounded font-bold align-middle">{{ $s['kelas'] }}</span>
                            </h4>
                            <p class="text-xs text-slate-500 line-clamp-1 mt-0.5">
                                @if($ringkasan)
                                    {{ $ringkasan }}{{ $extra }}
                                @else
                                    <span class="italic text-slate-300">Belum ada pelanggaran tercatat.</span>
                                @endif
                            </p>
                        </div>
                    </div>
                    <div class="flex items-center gap-4 flex-shrink-0">
                        <span class="font-bold px-3 py-1 rounded-lg text-xs border {{ $badgeColor }}">{{ $poin }} Poin</span>
                        <button class="w-8 h-8 rounded-full bg-slate-50 text-slate-400 hover:bg-slate-100 flex items-center justify-center transition">
                            <i class="fa-solid fa-chevron-right"></i>
                        </button>
                    </div>
                </div>

            @empty
                <div class="bg-white rounded-3xl border border-slate-200 p-12 text-center">
                    <i class="fa-solid fa-users-slash text-4xl text-slate-200 mb-3 block"></i>
                    <p class="text-slate-400 text-sm font-medium">
                        {{ $search ? 'Tidak ada siswa yang cocok dengan pencarian.' : 'Belum ada data siswa.' }}
                    </p>
                </div>
            @endforelse
        </div>

        {{-- Pagination --}}
        @if(($pagination['totalPages'] ?? 1) > 0)
            <div class="bg-white p-4 rounded-2xl border border-slate-200 shadow-sm flex justify-between items-center text-xs text-slate-500">
                <span class="font-medium">Menampilkan {{ count($siswaList) }} dari {{ $pagination['total'] ?? 0 }} data</span>
                <div class="flex gap-1">
                    <a href="{{ route('bk.pemantauan', ['page' => max(1, $page - 1), 'search' => $search]) }}"
                        class="px-3 py-1.5 bg-white border border-slate-200 rounded-lg hover:bg-slate-50 text-slate-600 transition {{ $page <= 1 ? 'opacity-50 pointer-events-none' : '' }}">Prev</a>
                    @for($i = 1; $i <= ($pagination['totalPages'] ?? 1); $i++)
                        <a href="{{ route('bk.pemantauan', ['page' => $i, 'search' => $search]) }}"
                            class="px-3 py-1.5 rounded-lg {{ $i == $page ? 'bg-primary text-white shadow-md font-bold' : 'bg-white border border-slate-200 hover:bg-slate-50 text-slate-600' }}">{{ $i }}</a>
                    @endfor
                    <a href="{{ route('bk.pemantauan', ['page' => min($pagination['totalPages'] ?? 1, $page + 1), 'search' => $search]) }}"
                        class="px-3 py-1.5 bg-white border border-slate-200 rounded-lg hover:bg-slate-50 text-slate-600 transition {{ $page >= ($pagination['totalPages'] ?? 1) ? 'opacity-50 pointer-events-none' : '' }}">Next</a>
                </div>
            </div>
        @endif

    </div>

    {{-- ── MODAL DETAIL SISWA ──────────────────────────────────────────── --}}
    <div id="modalDetailSiswa" class="fixed inset-0 bg-navy-900/40 hidden items-center justify-center z-50 backdrop-blur-sm">
        <div class="bg-white rounded-3xl w-full max-w-5xl shadow-2xl mx-4 overflow-hidden max-h-[90vh] flex flex-col">

            {{-- Header --}}
            <div class="p-6 border-b border-slate-100 flex justify-between items-center bg-slate-50 flex-shrink-0">
                <div class="flex items-center gap-4">
                    <div id="modalAvatar"
                        class="w-16 h-16 rounded-full bg-blue-600 text-white flex items-center justify-center text-2xl font-bold shadow-lg flex-shrink-0">--</div>
                    <div>
                        <h2 id="modalNama" class="text-2xl font-bold text-slate-800">-</h2>
                        <div class="flex gap-2 mt-1 flex-wrap">
                            <span id="modalKelas" class="bg-slate-200 text-slate-600 px-2 py-0.5 rounded text-xs font-bold">-</span>
                            <span id="modalBadgePoin" class="bg-red-100 text-red-600 px-2 py-0.5 rounded text-xs font-bold">0 Poin</span>
                        </div>
                    </div>
                </div>
                <button onclick="tutupModal()" class="text-slate-400 hover:text-slate-600 transition">
                    <i class="fa-solid fa-xmark text-2xl"></i>
                </button>
            </div>

            {{-- Loading --}}
            <div id="modalLoading" class="flex-1 flex items-center justify-center py-16">
                <div class="text-center">
                    <i class="fa-solid fa-spinner fa-spin text-3xl text-primary mb-3 block"></i>
                    <p class="text-sm text-slate-400">Memuat data...</p>
                </div>
            </div>

            {{-- Content --}}
            <div id="modalContent" class="hidden flex-1 overflow-y-auto custom-scroll">
                <div class="p-8 grid grid-cols-1 md:grid-cols-2 gap-8">

                    {{-- Kiri: Data Orang Tua --}}
                    <div class="space-y-6">
                        <div class="bg-white border border-slate-200 rounded-2xl p-6 shadow-sm">
                            <h4 class="font-bold text-slate-800 mb-4 text-sm uppercase tracking-wide">Data Orang Tua / Wali</h4>
                            <div id="modalOrangTua" class="space-y-3 text-sm text-slate-600">
                                <p class="text-slate-300 italic text-xs">Memuat...</p>
                            </div>
                        </div>
                    </div>

                    {{-- Kanan: Kronologi + Riwayat --}}
                    <div class="space-y-5">

                        {{-- Catatan Kronologi (keterangan pelanggaran terbaru) --}}
                        <div id="modalKronologiWrap" class="hidden">
                            <div class="flex items-center gap-2 mb-2">
                                <i class="fa-solid fa-comment-dots text-blue-500 text-xs"></i>
                                <span class="text-xs font-bold text-slate-600 uppercase tracking-wide">Catatan Kronologi</span>
                            </div>
                            <div class="bg-blue-50 border border-blue-100 rounded-2xl p-4">
                                <p id="modalKronologiTanggal" class="text-blue-600 font-bold text-sm mb-1"></p>
                                <p id="modalKronologiTeks" class="text-slate-700 text-sm italic leading-relaxed"></p>
                            </div>
                        </div>

                        {{-- Riwayat Pelanggaran --}}
                        <div>
                            <h4 class="font-bold text-red-600 mb-3 flex items-center gap-2 text-sm">
                                <i class="fa-solid fa-triangle-exclamation"></i> Riwayat Pelanggaran
                            </h4>
                            <div class="bg-red-50 border border-red-100 rounded-2xl overflow-hidden">
                                <table class="w-full text-left text-xs">
                                    <thead class="bg-red-100 text-red-800">
                                        <tr>
                                            <th class="p-3">Tanggal</th>
                                            <th class="p-3">Kasus</th>
                                            <th class="p-3 text-right">Poin</th>
                                        </tr>
                                    </thead>
                                    <tbody id="modalRiwayatBody" class="divide-y divide-red-100 text-slate-600">
                                        <tr><td colspan="3" class="p-4 text-center text-slate-300 italic">Memuat...</td></tr>
                                    </tbody>
                                </table>
                            </div>
                        </div>

                    </div>
                </div>
            </div>

        </div>
    </div>

    @push('scripts')
    <script>
        const csrfToken = '{{ csrf_token() }}';

        const SwalFixed = Swal.mixin({
            didOpen: () => {
                document.body.style.setProperty('padding-right', '0px', 'important');
                document.documentElement.style.setProperty('padding-right', '0px', 'important');
            },
            willClose: () => {
                document.body.style.setProperty('padding-right', '0px', 'important');
                document.documentElement.style.setProperty('padding-right', '0px', 'important');
            },
            didClose: () => {
                document.body.style.setProperty('padding-right', '0px', 'important');
                document.documentElement.style.setProperty('padding-right', '0px', 'important');
            },
        });

        function tutupModal() {
            const el = document.getElementById('modalDetailSiswa');
            el.classList.add('hidden');
            el.classList.remove('flex');
        }

        // Tutup saat klik backdrop
        document.getElementById('modalDetailSiswa').addEventListener('click', function(e) {
            if (e.target === this) tutupModal();
        });

        async function bukaDetailSiswa(id, card) {
            // Isi header langsung dari data-* (instan, tanpa tunggu fetch)
            const nama  = card.dataset.nama  ?? '-';
            const kelas = card.dataset.kelas ?? '-';
            const poin  = parseInt(card.dataset.poin ?? '0');
            const nisn  = card.dataset.nisn  ?? '-';

            const inisial = nama.trim().split(/\s+/).slice(0, 2).map(w => w[0]?.toUpperCase() ?? '').join('');
            document.getElementById('modalAvatar').textContent = inisial || '--';
            document.getElementById('modalNama').textContent   = nama;
            document.getElementById('modalKelas').textContent  = 'Kelas ' + kelas;

            const badge = document.getElementById('modalBadgePoin');
            badge.textContent  = 'Total Poin: ' + poin;
            badge.className    = 'px-2 py-0.5 rounded text-xs font-bold ' +
                (poin >= 75 ? 'bg-red-100 text-red-600' :
                 poin >= 40 ? 'bg-orange-100 text-orange-600' :
                 poin >= 15 ? 'bg-yellow-100 text-yellow-700' :
                              'bg-green-100 text-green-700');

            // Tampilkan modal dengan loading
            document.getElementById('modalLoading').classList.remove('hidden');
            document.getElementById('modalContent').classList.add('hidden');
            document.getElementById('modalDetailSiswa').classList.remove('hidden');
            document.getElementById('modalDetailSiswa').classList.add('flex');

            try {
                const res  = await fetch(`/bk/pemantauan/${id}/detail`, {
                    headers: { 'X-CSRF-TOKEN': csrfToken }
                });
                const data = await res.json();

                renderOrangTua(data.siswa);
                renderKronologi(data.pelanggaran ?? []);
                renderRiwayat(data.pelanggaran ?? []);

                document.getElementById('modalLoading').classList.add('hidden');
                document.getElementById('modalContent').classList.remove('hidden');

            } catch (e) {
                document.getElementById('modalLoading').innerHTML =
                    '<p class="text-red-400 text-sm py-8 text-center px-6">Gagal memuat data detail.</p>';
            }
        }

        function renderOrangTua(siswa) {
            const wrap = document.getElementById('modalOrangTua');
            if (!siswa) {
                wrap.innerHTML = '<p class="text-slate-300 italic text-xs">Data tidak tersedia.</p>';
                return;
            }

            // Field dari siswa_controller.js: nama_ayah, pekerjaan_ayah, nama_ibu, pekerjaan_ibu,
            // nama_wali, pekerjaan_wali, no_telepon, alamat
            const baris = (label, value) => value
                ? `<div class="flex gap-3">
                       <span class="w-16 text-[10px] font-bold text-slate-400 uppercase tracking-wide shrink-0 pt-0.5">${label}</span>
                       <span class="text-slate-700 text-sm">${value}</span>
                   </div>`
                : `<div class="flex gap-3">
                       <span class="w-16 text-[10px] font-bold text-slate-400 uppercase tracking-wide shrink-0 pt-0.5">${label}</span>
                       <span class="text-slate-300 text-sm">-</span>
                   </div>`;

            const ayah = [siswa.nama_ayah, siswa.pekerjaan_ayah ? `(${siswa.pekerjaan_ayah})` : ''].filter(Boolean).join(' ') || null;
            const ibu  = [siswa.nama_ibu,  siswa.pekerjaan_ibu  ? `(${siswa.pekerjaan_ibu})`  : ''].filter(Boolean).join(' ') || null;
            const wali = [siswa.nama_wali, siswa.pekerjaan_wali ? `(${siswa.pekerjaan_wali})` : ''].filter(Boolean).join(' ') || null;

            let html = baris('Ayah', ayah) + baris('Ibu', ibu) + baris('Wali', wali);

            if (siswa.alamat) {
                html += `<div class="pt-2 mt-1 border-t border-slate-100">
                    <p class="text-[10px] font-bold text-slate-400 uppercase tracking-wide mb-1">Alamat</p>
                    <p class="text-slate-700 text-sm">${siswa.alamat}</p>
                </div>`;
            }

            if (siswa.no_telepon) {
                html += `<div class="pt-1">
                    <p class="text-[10px] font-bold text-slate-400 uppercase tracking-wide mb-1">Kontak</p>
                    <span class="bg-green-100 text-green-700 px-2 py-0.5 rounded text-xs font-mono font-bold">${siswa.no_telepon}</span>
                </div>`;
            }

            wrap.innerHTML = html;
        }

        function renderKronologi(pelanggaran) {
            // Tampilkan keterangan dari pelanggaran TERBARU yang ada keterangannya
            const wrap  = document.getElementById('modalKronologiWrap');
            const latest = pelanggaran.find(p => p.keterangan && p.keterangan.trim() !== '');

            if (!latest) {
                wrap.classList.add('hidden');
                return;
            }

            wrap.classList.remove('hidden');
            const tgl = latest.tanggal
                ? new Date(latest.tanggal).toLocaleDateString('id-ID', { day: 'numeric', month: 'long', year: 'numeric' })
                : '-';
            document.getElementById('modalKronologiTanggal').textContent = tgl;
            document.getElementById('modalKronologiTeks').textContent    = `"${latest.keterangan}"`;
        }

        function renderRiwayat(pelanggaran) {
            const tbody = document.getElementById('modalRiwayatBody');
            if (!pelanggaran.length) {
                tbody.innerHTML = '<tr><td colspan="3" class="p-4 text-center text-slate-300 italic">Belum ada riwayat pelanggaran.</td></tr>';
                return;
            }

            tbody.innerHTML = pelanggaran.map(p => {
                const tgl = p.tanggal
                    ? new Date(p.tanggal).toLocaleDateString('id-ID', { day: '2-digit', month: 'short', year: 'numeric' })
                    : '-';
                return `<tr>
                    <td class="p-3 font-mono text-slate-500">${tgl}</td>
                    <td class="p-3 font-bold text-slate-700">${p.pelanggaran ?? '-'}
                        ${p.keterangan ? `<p class="font-normal text-slate-400 text-[10px] mt-0.5 italic line-clamp-1">${p.keterangan}</p>` : ''}
                    </td>
                    <td class="p-3 text-right font-bold text-red-600">+${p.poin ?? 0}</td>
                </tr>`;
            }).join('');
        }
    </script>
    @endpush

</x-layout-app>
