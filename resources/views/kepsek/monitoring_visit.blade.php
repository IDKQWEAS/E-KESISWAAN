<x-layout-app title="Monitoring Home Visit" :role="$role">

    <div class="w-full space-y-6 fade-in">

        <div class="flex flex-wrap justify-between items-start gap-4">
            <div>
                <h3 class="font-bold text-2xl text-slate-800 mb-1">Monitoring Home Visit</h3>
                <p class="text-sm text-slate-500">Jadwal dan status kunjungan rumah siswa.</p>
            </div>

            {{-- Summary di kanan atas --}}
            <div class="flex gap-3">
                <div class="bg-green-50 border border-green-200 rounded-2xl px-5 py-3 flex items-center gap-3">
                    <div class="w-8 h-8 rounded-full bg-green-200 flex items-center justify-center flex-shrink-0">
                        <i class="fa-solid fa-circle-check text-green-700 text-xs"></i>
                    </div>
                    <div>
                        <p class="text-xl font-extrabold text-green-700 leading-none">{{ count($visitSelesai) }}</p>
                        <p class="text-[10px] text-green-600 font-bold mt-0.5">Terlaksana</p>
                    </div>
                </div>
                <div class="bg-orange-50 border border-orange-200 rounded-2xl px-5 py-3 flex items-center gap-3">
                    <div class="w-8 h-8 rounded-full bg-orange-200 flex items-center justify-center flex-shrink-0">
                        <i class="fa-solid fa-clock text-orange-600 text-xs"></i>
                    </div>
                    <div>
                        <p class="text-xl font-extrabold text-orange-600 leading-none">{{ count($visitPending) }}</p>
                        <p class="text-[10px] text-orange-500 font-bold mt-0.5">Pending</p>
                    </div>
                </div>
            </div>
        </div>

        {{-- Grid --}}
        <div class="grid grid-cols-1 lg:grid-cols-2 gap-6">

            {{-- Sudah Terlaksana --}}
            <div class="bg-white rounded-[24px] shadow-sm border border-slate-200 overflow-hidden flex flex-col">
                <div class="p-5 bg-[#f0fdf4] border-b border-[#dcfce7] flex justify-between items-center">
                    <h4 class="font-bold text-green-800 text-sm">Sudah Terlaksana</h4>
                    <span class="bg-green-200 text-green-800 px-2 py-0.5 rounded text-[10px] font-bold">
                        {{ count($visitSelesai) }} Selesai
                    </span>
                </div>

                <div class="p-4 space-y-3 overflow-y-auto" style="max-height:420px">
                    @forelse($visitSelesai as $visit)
                        @php
                            $tanggal = date('d M Y', strtotime($visit['tanggal']));
                            $inisial = strtoupper(substr($visit['nama_siswa'] ?? 'XX', 0, 2));
                        @endphp
                        <div
                            class="p-4 border border-slate-100 rounded-[16px] flex justify-between items-center bg-white shadow-sm hover:border-green-300 hover:bg-green-50/30 transition cursor-pointer"
                            data-id="{{ $visit['id'] }}"
                            onclick="openDetailVisit(this.dataset.id)"
                        >
                            <div class="flex items-center gap-3">
                                <div class="w-9 h-9 rounded-full bg-green-100 flex items-center justify-center text-xs font-bold text-green-700 flex-shrink-0">
                                    {{ $inisial }}
                                </div>
                                <div>
                                    <p class="text-sm font-bold text-slate-700">{{ $visit['nama_siswa'] ?? '-' }}</p>
                                    <p class="text-[10px] text-slate-400">{{ $tanggal }} &bull; Kelas {{ $visit['kelas'] ?? '-' }}</p>
                                </div>
                            </div>
                            <i class="fa-solid fa-circle-check text-green-500 text-xl flex-shrink-0"></i>
                        </div>
                    @empty
                        <div class="py-10 text-center text-slate-400 text-sm">
                            <i class="fa-regular fa-calendar-xmark text-3xl mb-2 block"></i>
                            Belum ada kunjungan yang diselesaikan.
                        </div>
                    @endforelse
                </div>
            </div>

            {{-- Rencana Kunjungan --}}
            <div class="bg-white rounded-[24px] shadow-sm border border-slate-200 overflow-hidden flex flex-col">
                <div class="p-5 bg-orange-50 border-b border-orange-100 flex justify-between items-center">
                    <h4 class="font-bold text-orange-800 text-sm">Rencana Kunjungan</h4>
                    <span class="bg-orange-200 text-orange-800 px-2 py-0.5 rounded text-[10px] font-bold">
                        {{ count($visitPending) }} Pending
                    </span>
                </div>

                <div class="p-4 space-y-3 overflow-y-auto" style="max-height:420px">
                    @forelse($visitPending as $visit)
                        @php
                            $tanggal = date('d M Y', strtotime($visit['tanggal']));
                            $inisial = strtoupper(substr($visit['nama_siswa'] ?? 'XX', 0, 2));
                        @endphp
                        <div
                            class="p-4 border border-orange-100 bg-orange-50/30 rounded-xl flex justify-between items-center hover:bg-orange-50 transition cursor-pointer"
                            data-id="{{ $visit['id'] }}"
                            onclick="openDetailVisit(this.dataset.id)"
                        >
                            <div class="flex gap-3 items-center">
                                <div class="w-9 h-9 rounded-full bg-red-100 flex items-center justify-center text-xs font-bold text-red-600 flex-shrink-0">
                                    {{ $inisial }}
                                </div>
                                <div>
                                    <p class="text-sm font-bold text-slate-700">{{ $visit['nama_siswa'] ?? '-' }}</p>
                                    <p class="text-[10px] text-red-500">Kelas {{ $visit['kelas'] ?? '-' }}</p>
                                </div>
                            </div>
                            <span class="text-[11px] font-bold text-slate-500 bg-white px-2 py-1 rounded-md border border-slate-200 shadow-sm flex-shrink-0">
                                {{ $tanggal }}
                            </span>
                        </div>
                    @empty
                        <div class="py-10 text-center text-slate-400 text-sm">
                            <i class="fa-regular fa-calendar-check text-3xl mb-2 block"></i>
                            Tidak ada rencana kunjungan tertunda.
                        </div>
                    @endforelse
                </div>
            </div>

        </div>
    </div>

    {{-- Modal Detail --}}
    <div id="modalDetailVisit" class="fixed inset-0 bg-black/60 hidden items-center justify-center z-50 backdrop-blur-sm">
        <div class="bg-white rounded-3xl w-full max-w-md shadow-2xl mx-4 overflow-hidden">

            <div class="p-5 border-b border-slate-100 flex justify-between items-center">
                <h3 class="font-bold text-slate-800">Detail Home Visit</h3>
                <button
                    onclick="toggleModal('modalDetailVisit')"
                    class="w-8 h-8 rounded-full bg-slate-100 flex items-center justify-center hover:bg-slate-200 transition"
                >
                    <i class="fa-solid fa-xmark text-slate-600"></i>
                </button>
            </div>

            {{-- Loading --}}
            <div id="modal-loading" class="p-10 text-center text-slate-400 text-sm">
                <svg class="animate-spin h-6 w-6 mx-auto mb-2 text-blue-500" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24">
                    <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle>
                    <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8v8z"></path>
                </svg>
                Memuat data...
            </div>

            {{-- Content --}}
            <div id="modal-content" class="hidden p-6 space-y-5">

                {{-- Identitas siswa --}}
                <div class="flex items-center gap-4">
                    <div id="modal-inisial" class="w-12 h-12 rounded-full bg-[#2563eb] text-white flex items-center justify-center font-bold text-lg flex-shrink-0"></div>
                    <div>
                        <p id="modal-nama"  class="font-bold text-slate-800 text-base"></p>
                        <p id="modal-kelas" class="text-xs text-slate-500 mt-0.5"></p>
                    </div>
                </div>

                {{-- Info kunjungan --}}
                <div class="bg-slate-50 rounded-2xl p-4 border border-slate-100 space-y-4">

                    <div class="grid grid-cols-2 gap-4">
                        <div>
                            <p class="text-[10px] text-slate-400 font-bold uppercase mb-1">ID Kunjungan</p>
                            <p id="modal-id" class="text-slate-700 font-bold text-sm font-mono"></p>
                        </div>
                        <div>
                            <p class="text-[10px] text-slate-400 font-bold uppercase mb-1">Status</p>
                            <p id="modal-status" class="text-sm font-bold"></p>
                        </div>
                    </div>

                    <div>
                        <p class="text-[10px] text-slate-400 font-bold uppercase mb-1">Tanggal Kunjungan</p>
                        <p id="modal-tanggal" class="text-slate-800 font-bold text-sm"></p>
                    </div>

                    <div class="grid grid-cols-2 gap-4 border-t border-slate-200 pt-4">
                        <div>
                            <p class="text-[10px] text-slate-400 font-bold uppercase mb-1">Dicatat Pada</p>
                            <p id="modal-created" class="text-slate-600 text-xs"></p>
                        </div>
                        <div>
                            <p class="text-[10px] text-slate-400 font-bold uppercase mb-1">Diperbarui</p>
                            <p id="modal-updated" class="text-slate-600 text-xs"></p>
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
            return new Date(str).toLocaleDateString('id-ID', {
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
            document.getElementById('modal-loading').classList.remove('hidden');
            document.getElementById('modal-content').classList.add('hidden');
            toggleModal('modalDetailVisit');

            try {
                const res = await fetch('/kepsek/visit/' + id + '/detail');
                if (!res.ok) throw new Error('Gagal');

                const data = await res.json();
                const nama   = data.nama_siswa ?? data.nama ?? '-';
                const status = data.status ?? '-';

                document.getElementById('modal-id').innerText      = '#' + (data.id ?? '-');
                document.getElementById('modal-inisial').innerText = nama.substring(0, 2).toUpperCase();
                document.getElementById('modal-nama').innerText    = nama;
                document.getElementById('modal-kelas').innerText   = 'Kelas ' + (data.kelas ?? '-');
                document.getElementById('modal-tanggal').innerText = formatTanggal(data.tanggal);
                document.getElementById('modal-created').innerText = formatDatetime(data.created_at);
                document.getElementById('modal-updated').innerText = formatDatetime(data.updated_at);

                const statusEl    = document.getElementById('modal-status');
                statusEl.innerText = status;
                statusEl.className = status === 'Sudah Terlaksana'
                    ? 'text-sm font-bold text-green-600'
                    : 'text-sm font-bold text-orange-500';

                document.getElementById('modal-loading').classList.add('hidden');
                document.getElementById('modal-content').classList.remove('hidden');

            } catch (e) {
                console.error(e);
                document.getElementById('modal-loading').innerText = 'Gagal memuat data.';
            }
        }
    </script>
    @endpush

</x-layout-app>
