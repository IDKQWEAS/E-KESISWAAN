<x-layout-app title="Monitoring Home Visit" :role="$role">

    <div class="w-full space-y-4 fade-in pb-8">

        {{-- ── HEADER & SUMMARY ─────────────────────────────────────── --}}
        <div class="flex flex-wrap justify-between items-start gap-4">
            <div>
                <h3 class="font-bold text-lg text-slate-800 mb-0.5">Monitoring Home Visit</h3>
                <p class="text-[11px] text-slate-500">Jadwal dan status kunjungan rumah siswa.</p>
            </div>

            {{-- Summary di kanan atas --}}
            <div class="flex gap-2.5">
                <div class="bg-green-50 border border-green-200 rounded-xl px-4 py-2 flex items-center gap-2.5">
                    <div class="w-7 h-7 rounded-full bg-green-200 flex items-center justify-center flex-shrink-0">
                        <i class="fa-solid fa-circle-check text-green-700 text-[10px]"></i>
                    </div>
                    <div>
                        <p class="text-base font-extrabold text-green-700 leading-none">{{ count($visitSelesai) }}</p>
                        <p class="text-[9px] text-green-600 font-bold mt-0.5 uppercase tracking-wide">Terlaksana</p>
                    </div>
                </div>
                <div class="bg-orange-50 border border-orange-200 rounded-xl px-4 py-2 flex items-center gap-2.5">
                    <div class="w-7 h-7 rounded-full bg-orange-200 flex items-center justify-center flex-shrink-0">
                        <i class="fa-solid fa-clock text-orange-600 text-[10px]"></i>
                    </div>
                    <div>
                        <p class="text-base font-extrabold text-orange-600 leading-none">{{ count($visitPending) }}</p>
                        <p class="text-[9px] text-orange-500 font-bold mt-0.5 uppercase tracking-wide">Pending</p>
                    </div>
                </div>
            </div>
        </div>

        {{-- ── FILTER KELAS — Menggunakan AJAX seperti menu lain ── --}}
        <div class="flex items-center gap-2">
            <form id="filterFormVisit" method="GET" action="{{ route('kepsek.visit') }}" class="relative w-full md:w-auto">
                <select name="kelas" class="auto-submit w-full md:w-36 appearance-none bg-white border border-slate-200 hover:border-slate-300 rounded-lg py-1.5 pl-3 pr-8 text-[11px] font-bold text-slate-600 outline-none focus:ring-2 focus:ring-blue-500/20 cursor-pointer shadow-sm transition-all h-[32px]">
                    <option value="">Semua Kelas</option>
                    @foreach(['7A','7B','7C','7D','7E','7F','7G', '8A','8B','8C','8D','8E','8F','8G', '9A','9B','9C','9D','9E','9F','9G'] as $k)
                        <option value="{{ $k }}" {{ $filterKelas == $k ? 'selected' : '' }}>Kelas {{ $k }}</option>
                    @endforeach
                </select>
                <i class="fa-solid fa-chevron-down absolute right-3 top-1/2 -translate-y-1/2 text-[9px] text-slate-400 pointer-events-none"></i>
            </form>

            @if($filterKelas)
                <button type="button" onclick="window.location='{{ route('kepsek.visit') }}'" class="px-3 py-1.5 text-[11px] text-slate-500 bg-white border border-slate-200 rounded-lg hover:bg-slate-50 font-bold h-[32px] flex items-center shadow-sm transition">
                    Reset
                </button>
            @endif
        </div>

        {{-- ── GRID UTAMA (Bungkus id data-container) ───────────────────────────────────────────── --}}
        <div id="data-container" class="grid grid-cols-1 lg:grid-cols-2 gap-5 items-start transition-opacity duration-300">

            {{-- Sudah Terlaksana --}}
            <div class="bg-white rounded-2xl shadow-sm border border-slate-200 overflow-hidden flex flex-col w-full">
                <div class="p-4 bg-[#f0fdf4] border-b border-[#dcfce7] flex justify-between items-center shadow-sm">
                    <h4 class="font-bold text-green-800 text-[12px]">Sudah Terlaksana</h4>
                    <span class="bg-green-200 text-green-800 px-2 py-0.5 rounded text-[9px] font-bold shadow-sm">
                        {{ count($visitSelesai) }} Selesai
                    </span>
                </div>

                <div class="p-3.5 space-y-2.5 overflow-y-auto custom-scroll" style="max-height:400px">
                    @forelse($visitSelesai as $visit)
                        @php
                            $tanggal = \Carbon\Carbon::parse($visit['tanggal'])->timezone('Asia/Jakarta')->translatedFormat('d M Y');
                            $inisial = strtoupper(substr($visit['nama_siswa'] ?? 'XX', 0, 2));
                        @endphp
                        <div
                            class="p-3.5 border border-slate-100 rounded-xl flex justify-between items-center bg-white shadow-sm hover:border-green-300 hover:bg-green-50/30 transition cursor-pointer group"
                            onclick="openDetailVisit({{ $visit['id'] }})"
                        >
                            <div class="flex items-center gap-3">
                                <div class="w-8 h-8 rounded-full bg-green-100 flex items-center justify-center text-[10px] font-bold text-green-700 flex-shrink-0">
                                    {{ $inisial }}
                                </div>
                                <div>
                                    <p class="text-[12px] font-bold text-slate-700 group-hover:text-green-700 transition">{{ $visit['nama_siswa'] ?? '-' }}</p>
                                    <p class="text-[10px] text-slate-400 mt-0.5">{{ $tanggal }} &bull; Kelas {{ $visit['kelas'] ?? '-' }}</p>
                                </div>
                            </div>
                            <i class="fa-solid fa-circle-check text-green-500 text-lg flex-shrink-0"></i>
                        </div>
                    @empty
                        <div class="py-10 text-center text-slate-400 text-[11px]">
                            <i class="fa-regular fa-calendar-xmark text-3xl mb-2 block text-slate-300"></i>
                            Belum ada kunjungan yang diselesaikan.
                        </div>
                    @endforelse
                </div>
            </div>

            {{-- Rencana Kunjungan --}}
            <div class="bg-white rounded-2xl shadow-sm border border-slate-200 overflow-hidden flex flex-col w-full">
                <div class="p-4 bg-orange-50 border-b border-orange-100 flex justify-between items-center shadow-sm">
                    <h4 class="font-bold text-orange-800 text-[12px]">Rencana Kunjungan</h4>
                    <span class="bg-orange-200 text-orange-800 px-2 py-0.5 rounded text-[9px] font-bold shadow-sm">
                        {{ count($visitPending) }} Pending
                    </span>
                </div>

                <div class="p-3.5 space-y-2.5 overflow-y-auto custom-scroll" style="max-height:400px">
                    @forelse($visitPending as $visit)
                        @php
                            $tanggal = \Carbon\Carbon::parse($visit['tanggal'])->timezone('Asia/Jakarta')->translatedFormat('d M Y');
                            $inisial = strtoupper(substr($visit['nama_siswa'] ?? 'XX', 0, 2));
                        @endphp
                        <div
                            class="p-3.5 border border-orange-100 bg-orange-50/30 rounded-xl flex justify-between items-center hover:bg-orange-50 transition cursor-pointer shadow-sm group"
                            onclick="openDetailVisit({{ $visit['id'] }})"
                        >
                            <div class="flex gap-3 items-center">
                                <div class="w-8 h-8 rounded-full bg-red-100 flex items-center justify-center text-[10px] font-bold text-red-600 flex-shrink-0">
                                    {{ $inisial }}
                                </div>
                                <div>
                                    <p class="text-[12px] font-bold text-slate-700 group-hover:text-orange-700 transition">{{ $visit['nama_siswa'] ?? '-' }}</p>
                                    <p class="text-[10px] text-red-500 font-medium mt-0.5">Kelas {{ $visit['kelas'] ?? '-' }}</p>
                                </div>
                            </div>
                            <span class="text-[10px] font-bold text-slate-500 bg-white px-2 py-1 rounded border border-slate-200 shadow-sm flex-shrink-0 flex items-center gap-1.5">
                                <i class="fa-regular fa-clock text-slate-400"></i> {{ $tanggal }}
                            </span>
                        </div>
                    @empty
                        <div class="py-10 text-center text-slate-400 text-[11px]">
                            <i class="fa-regular fa-calendar-check text-3xl mb-2 block text-slate-300"></i>
                            Tidak ada rencana kunjungan tertunda.
                        </div>
                    @endforelse
                </div>
            </div>

        </div>
    </div>

    {{-- ── MODAL DETAIL ──────────────────────────────────────────────── --}}
    <div id="modalDetailVisit" class="fixed inset-0 bg-black/60 hidden items-center justify-center z-50 backdrop-blur-sm"
         onclick="if(event.target===this) toggleModal('modalDetailVisit')">
        <div class="bg-white rounded-2xl w-full max-w-md shadow-2xl mx-4 overflow-hidden">

            <div class="p-4 border-b border-slate-100 flex justify-between items-center bg-slate-50">
                <h3 class="font-bold text-slate-800 text-[13px]">Detail Home Visit</h3>
                <button
                    onclick="toggleModal('modalDetailVisit')"
                    class="w-6 h-6 rounded border border-slate-200 bg-white flex items-center justify-center hover:bg-slate-100 transition text-[10px]"
                >
                    <i class="fa-solid fa-xmark text-slate-500"></i>
                </button>
            </div>

            {{-- Loading --}}
            <div id="modal-loading" class="p-10 text-center text-slate-400 text-[11px]">
                <svg class="animate-spin h-6 w-6 mx-auto mb-2 text-blue-500" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24">
                    <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle>
                    <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8v8z"></path>
                </svg>
                Memuat data...
            </div>

            {{-- Content --}}
            <div id="modal-content" class="hidden p-5 space-y-4">

                {{-- Identitas siswa --}}
                <div class="flex items-center gap-3">
                    <div id="modal-inisial" class="w-10 h-10 rounded-full bg-blue-600 text-white flex items-center justify-center font-bold text-base flex-shrink-0 shadow-sm"></div>
                    <div>
                        <p id="modal-nama"  class="font-bold text-slate-800 text-sm"></p>
                        <p id="modal-kelas" class="text-[11px] text-slate-500 mt-0.5"></p>
                    </div>
                </div>

                {{-- Info kunjungan --}}
                <div class="bg-slate-50 rounded-xl p-4 border border-slate-100 space-y-3">
                    <div class="grid grid-cols-2 gap-3">
                        <div>
                            <p class="text-[9px] text-slate-400 font-bold uppercase mb-1">ID Kunjungan</p>
                            <p id="modal-id" class="text-slate-700 font-bold text-[11px] font-mono"></p>
                        </div>
                        <div>
                            <p class="text-[9px] text-slate-400 font-bold uppercase mb-1">Status</p>
                            <p id="modal-status" class="text-[11px] font-bold"></p>
                        </div>
                    </div>
                    <div>
                        <p class="text-[9px] text-slate-400 font-bold uppercase mb-1">Tanggal Kunjungan</p>
                        <p id="modal-tanggal" class="text-slate-800 font-bold text-[11px]"></p>
                    </div>
                    <div class="grid grid-cols-2 gap-3 border-t border-slate-200 pt-3">
                        <div>
                            <p class="text-[9px] text-slate-400 font-bold uppercase mb-1">Dicatat Pada</p>
                            <p id="modal-created" class="text-slate-600 text-[10px]"></p>
                        </div>
                        <div>
                            <p class="text-[9px] text-slate-400 font-bold uppercase mb-1">Diperbarui</p>
                            <p id="modal-updated" class="text-slate-600 text-[10px]"></p>
                        </div>
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

        function formatTanggal(str) {
            if (!str) return '-';
            const datePart = str.split('T')[0].split(' ')[0];
            const [y, m, d] = datePart.split('-');
            const dateObj = new Date(+y, +m - 1, +d);
            return dateObj.toLocaleDateString('id-ID', {
                day: 'numeric', month: 'long', year: 'numeric'
            });
        }

        function formatDatetime(str) {
            if (!str) return '-';
            const d = new Date(str);
            return d.toLocaleDateString('id-ID', {
                day: 'numeric', month: 'long', year: 'numeric'
            }) + ', ' + d.toLocaleTimeString('id-ID', {
                hour: '2-digit', minute: '2-digit'
            });
        }

        async function openDetailVisit(id) {
            const loading = document.getElementById('modal-loading');
            const content = document.getElementById('modal-content');

            loading.innerHTML = `
                <svg class="animate-spin h-6 w-6 mx-auto mb-2 text-blue-500" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24">
                    <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle>
                    <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8v8z"></path>
                </svg>
                Memuat data...
            `;
            loading.classList.remove('hidden');
            content.classList.add('hidden');
            toggleModal('modalDetailVisit');

            try {
                const res = await fetch('/kepsek/visit/' + id + '/detail');
                if (!res.ok) throw new Error('Gagal');

                const data   = await res.json();
                const nama   = data.nama_siswa ?? data.nama ?? '-';
                const status = data.status ?? '-';

                document.getElementById('modal-id').innerText      = '#' + (data.id ?? '-');
                document.getElementById('modal-inisial').innerText = nama.substring(0, 2).toUpperCase();
                document.getElementById('modal-nama').innerText    = nama;
                document.getElementById('modal-kelas').innerText   = 'Kelas ' + (data.kelas ?? '-');
                document.getElementById('modal-tanggal').innerText = formatTanggal(data.tanggal);
                document.getElementById('modal-created').innerText = formatDatetime(data.created_at);
                document.getElementById('modal-updated').innerText = formatDatetime(data.updated_at);

                const statusEl     = document.getElementById('modal-status');
                statusEl.innerText = status;
                statusEl.className = status === 'Sudah Terlaksana'
                    ? 'text-[11px] font-bold text-green-600'
                    : 'text-[11px] font-bold text-orange-500';

                loading.classList.add('hidden');
                content.classList.remove('hidden');

            } catch (e) {
                loading.innerHTML = '<p class="text-red-400 text-[11px]">Gagal memuat data.</p>';
            }
        }

        // ── SCRIPT AJAX EVENT DELEGATION ────────────────────────
        document.addEventListener('DOMContentLoaded', () => {
            document.addEventListener('change', e => {
                if (e.target.matches('.auto-submit')) {
                    const form = e.target.closest('form');
                    if (form) executeAjaxFilter(form);
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
            params.set('page', 1);

            reloadContainerData(url.pathname + '?' + params.toString());
        }

        async function reloadContainerData(url) {
            const container = document.getElementById('data-container');
            container.style.opacity = '0.5';
            container.style.pointerEvents = 'none';

            try {
                const res = await fetch(url, { headers: { 'X-Requested-With': 'XMLHttpRequest' } });
                const html = await res.text();

                const doc = new DOMParser().parseFromString(html, 'text/html');
                const newContainer = doc.getElementById('data-container');

                if (newContainer) {
                    container.innerHTML = newContainer.innerHTML;
                    window.history.pushState({}, '', url);
                } else {
                    window.location.href = url;
                }
            } catch (e) {
                console.error("Gagal reload data:", e);
            } finally {
                container.style.opacity = '1';
                container.style.pointerEvents = 'auto';
            }
        }
    </script>
    @endpush

</x-layout-app>
