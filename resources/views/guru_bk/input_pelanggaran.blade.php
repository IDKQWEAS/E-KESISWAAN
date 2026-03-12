<x-layout-app title="Input Pelanggaran" :role="$role">

    <div class="space-y-6 fade-in">

        {{-- Tab --}}
        <div class="flex gap-4 border-b border-slate-200">
            <button onclick="switchInputTab('data')" id="tab-btn-data"
                class="px-6 py-2 text-sm font-bold border-b-2 border-primary text-primary transition-all">
                Data Pelanggaran
            </button>
            <button onclick="switchInputTab('panduan')" id="tab-btn-panduan"
                class="px-6 py-2 text-sm font-bold border-b-2 border-transparent text-slate-500 hover:text-slate-800 transition-all">
                Panduan Poin
            </button>
        </div>

        {{-- ── TAB DATA PELANGGARAN ────────────────────────────────── --}}
        <div id="view-pelanggaran-data" class="space-y-6">

            <div class="bg-white p-6 rounded-3xl shadow-card border border-slate-200 flex justify-between items-center">
                <div>
                    <h3 class="font-bold text-lg text-slate-800 flex items-center gap-2">
                        <i class="fa-solid fa-gavel text-red-500"></i> Manajemen Pelanggaran
                    </h3>
                    <p class="text-xs text-slate-500 mt-1">Data pelanggaran siswa tercatat.</p>
                </div>
                <button
                    onclick="toggleModal('modalInputPelanggaran')"
                    class="px-6 py-3 bg-red-600 hover:bg-red-700 text-white rounded-xl text-xs font-bold transition flex items-center gap-2 shadow-lg shadow-red-500/30">
                    <i class="fa-solid fa-plus"></i> Tambah Pelanggaran
                </button>
            </div>

            <div class="bg-white rounded-3xl shadow-card border border-slate-200 overflow-hidden">
                <div class="p-6 bg-slate-50 border-b border-slate-200 flex justify-between items-center">
                    <h4 class="font-bold text-slate-700 text-sm">Riwayat Pelanggaran</h4>
                    <form method="GET" action="{{ route('bk.pelanggaran') }}" class="flex gap-2">
                        <select name="kelas" onchange="this.form.submit()"
                            class="border border-slate-200 rounded-xl px-3 py-2 text-xs bg-white outline-none font-medium text-slate-600">
                            <option value="">Semua Kelas</option>
                            @foreach(['7A','7B','7C','7D','7E','7F','7G','8A','8B','8C','8D','8E','8F','8G','9A','9B','9C','9D','9E','9F','9G'] as $k)
                                <option value="{{ $k }}" {{ $kelas == $k ? 'selected' : '' }}>{{ $k }}</option>
                            @endforeach
                        </select>
                        @if($kelas)
                            <button type="button"
                                onclick="window.location='{{ route('bk.pelanggaran') }}'"
                                class="px-3 py-2 text-xs text-slate-500 bg-white border border-slate-200 rounded-xl hover:bg-slate-50">
                                Reset
                            </button>
                        @endif
                    </form>
                </div>

                <div class="overflow-x-auto">
                    <table class="w-full text-left text-sm">
                        <thead class="bg-white border-b border-slate-100">
                            <tr>
                                <th class="px-6 py-4 text-slate-400 text-[10px] uppercase font-bold tracking-wider">Tanggal</th>
                                <th class="px-6 py-4 text-slate-400 text-[10px] uppercase font-bold tracking-wider">Siswa</th>
                                <th class="px-6 py-4 text-slate-400 text-[10px] uppercase font-bold tracking-wider">Pelanggaran</th>
                                <th class="px-6 py-4 text-slate-400 text-[10px] uppercase font-bold tracking-wider">Kronologi</th>
                                <th class="px-6 py-4 text-slate-400 text-[10px] uppercase font-bold tracking-wider">Poin</th>
                                <th class="px-6 py-4 text-right text-slate-400 text-[10px] uppercase font-bold tracking-wider">Aksi</th>
                            </tr>
                        </thead>
                        <tbody class="divide-y divide-slate-50">
                            @forelse($pelanggaran as $p)
                                @php
                                    $rawDate = $p['tanggal'] ?? null;
                                    $tglInput = $rawDate ? \Carbon\Carbon::parse($rawDate)->timezone('Asia/Jakarta')->format('Y-m-d') : '';
                                    $tglDisplay = $rawDate ? \Carbon\Carbon::parse($rawDate)->timezone('Asia/Jakarta')->format('d M Y') : '-';
                                @endphp
                                <tr class="hover:bg-slate-50 transition">
                                    <td class="px-6 py-4 text-xs text-slate-500 font-mono">
                                        {{ $tglDisplay }}
                                    </td>
                                    <td class="px-6 py-4 font-bold text-slate-800 text-sm">
                                        {{ $p['nama_siswa'] ?? '-' }}
                                        <span class="ml-1 text-xs font-normal text-slate-400">({{ $p['kelas'] ?? '-' }})</span>
                                    </td>
                                    <td class="px-6 py-4">
                                        <span class="bg-red-50 text-red-600 border border-red-100 px-3 py-1 rounded-lg text-xs font-bold">
                                            {{ $p['pelanggaran'] ?? '-' }}
                                        </span>
                                    </td>
                                    <td class="px-6 py-4 text-xs text-slate-600 italic max-w-xs truncate">
                                        {{ $p['keterangan'] ?? '-' }}
                                    </td>
                                    <td class="px-6 py-4 font-bold text-red-600">+{{ $p['poin'] ?? 0 }}</td>
                                    <td class="px-6 py-4 text-right space-x-1">
                                        <button
                                            data-id="{{ $p['id'] }}"
                                            data-tanggal="{{ $tglInput }}"
                                            data-keterangan="{{ $p['keterangan'] ?? '' }}"
                                            data-nama="{{ $p['nama_siswa'] ?? '-' }}"
                                            data-kelas="{{ $p['kelas'] ?? '-' }}"
                                            data-pelanggaran="{{ $p['pelanggaran'] ?? '-' }}"
                                            data-poin="{{ $p['poin'] ?? 0 }}"
                                            onclick="openEditPelanggaran(this)"
                                            class="text-slate-400 hover:text-blue-600 p-2 rounded-lg hover:bg-blue-50 transition">
                                            <i class="fa-solid fa-pen-to-square"></i>
                                        </button>
                                        <button
                                            onclick="confirmDeletePelanggaran('{{ $p['id'] }}')"
                                            class="text-slate-400 hover:text-red-600 p-2 rounded-lg hover:bg-red-50 transition">
                                            <i class="fa-solid fa-trash-can"></i>
                                        </button>
                                    </td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="6" class="px-6 py-10 text-center text-sm text-slate-400">
                                        Belum ada data pelanggaran.
                                    </td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>

                <div class="p-4 border-t border-slate-100 flex justify-between items-center text-xs text-slate-500">
                    <span>Menampilkan {{ count($pelanggaran) }} dari {{ $pagination['total'] ?? 0 }} data</span>
                    @if(($pagination['totalPages'] ?? 1) > 1)
                        <div class="flex gap-1">
                            <a href="{{ route('bk.pelanggaran', ['page' => max(1, $page - 1), 'kelas' => $kelas]) }}"
                                class="px-3 py-1 bg-white border border-slate-200 rounded-lg hover:bg-slate-50 {{ $page <= 1 ? 'opacity-50 pointer-events-none' : '' }}">Prev</a>
                            @for($i = 1; $i <= ($pagination['totalPages'] ?? 1); $i++)
                                <a href="{{ route('bk.pelanggaran', ['page' => $i, 'kelas' => $kelas]) }}"
                                    class="px-3 py-1 rounded-lg {{ $i == $page ? 'bg-primary text-white' : 'bg-white border border-slate-200 hover:bg-slate-50' }}">{{ $i }}</a>
                            @endfor
                            <a href="{{ route('bk.pelanggaran', ['page' => min($pagination['totalPages'] ?? 1, $page + 1), 'kelas' => $kelas]) }}"
                                class="px-3 py-1 bg-white border border-slate-200 rounded-lg hover:bg-slate-50 {{ $page >= ($pagination['totalPages'] ?? 1) ? 'opacity-50 pointer-events-none' : '' }}">Next</a>
                        </div>
                    @endif
                </div>
            </div>
        </div>

        {{-- ── TAB PANDUAN POIN ────────────────────────────────────── --}}
        <div id="view-pelanggaran-panduan" class="hidden">
            <div class="bg-blue-50 border border-blue-100 rounded-3xl p-8">
                <div class="flex justify-between items-center mb-6">
                    <h3 class="font-bold text-blue-800 text-lg">
                        <i class="fa-solid fa-book-open mr-2"></i>Panduan Poin Pelanggaran
                    </h3>
                    <button
                        onclick="openModalJenis('add')"
                        class="bg-blue-200 text-blue-800 px-4 py-2 rounded-xl hover:bg-blue-300 font-bold text-sm">
                        <i class="fa-solid fa-plus mr-1"></i> Tambah Aturan
                    </button>
                </div>

                <ul class="text-sm space-y-3 text-blue-900">
                    @forelse($jenisList as $j)
                        <li class="flex justify-between items-center border-b border-blue-200 pb-3 group">
                            <span class="font-medium">
                                {{ $j['pelanggaran'] }}
                                <b class="ml-2">{{ $j['poin'] }} Poin</b>
                            </span>
                            <div class="hidden group-hover:flex gap-2">
                                <button
                                    data-id="{{ $j['id'] }}"
                                    data-nama="{{ $j['pelanggaran'] }}"
                                    data-poin="{{ $j['poin'] }}"
                                    onclick="openModalJenis('edit', this)"
                                    class="text-blue-600 hover:text-blue-800 p-1">
                                    <i class="fa-solid fa-pen"></i>
                                </button>
                                <button
                                    onclick="confirmDeleteJenis('{{ $j['id'] }}')"
                                    class="text-red-400 hover:text-red-600 p-1">
                                    <i class="fa-solid fa-trash"></i>
                                </button>
                            </div>
                        </li>
                    @empty
                        <li class="text-center py-4 text-blue-700 text-sm">Belum ada jenis pelanggaran.</li>
                    @endforelse
                </ul>

                <div class="mt-6 pt-4 border-t border-blue-200 text-center">
                    <p class="text-xs font-bold text-red-600 uppercase">
                        Catatan: 100 Poin = Siswa Dikembalikan ke Orang Tua
                    </p>
                </div>
            </div>
        </div>
    </div>

    {{-- ── MODAL INPUT PELANGGARAN ─────────────────────────────────────── --}}
    <div id="modalInputPelanggaran" class="fixed inset-0 bg-black/50 hidden items-center justify-center z-50 backdrop-blur-sm">
        <div class="bg-white rounded-3xl w-full max-w-lg shadow-2xl mx-4">
            <div class="flex justify-between items-center p-6 border-b border-slate-100">
                <h3 class="font-bold text-slate-800 text-lg">Input Pelanggaran Baru</h3>
                <button onclick="toggleModal('modalInputPelanggaran')"
                    class="w-8 h-8 rounded-full bg-slate-100 flex items-center justify-center hover:bg-slate-200">
                    <i class="fa-solid fa-xmark text-slate-600"></i>
                </button>
            </div>
            <div class="p-6 space-y-5">
                <div class="grid grid-cols-2 gap-4">
                    <div>
                        <label class="block text-xs font-bold text-slate-700 uppercase mb-2">Kelas</label>
                        <select id="inputKelas" onchange="filterSiswaByKelas()"
                            class="w-full border border-slate-300 rounded-xl px-4 py-3 text-sm focus:ring-2 focus:ring-blue-500/30 outline-none">
                            <option value="">-- Pilih --</option>
                            @foreach(['7A','7B','7C','7D','7E','7F','7G','8A','8B','8C','8D','8E','8F','8G','9A','9B','9C','9D','9E','9F','9G'] as $k)
                                <option value="{{ $k }}">{{ $k }}</option>
                            @endforeach
                        </select>
                    </div>
                    <div>
                        <label class="block text-xs font-bold text-slate-700 uppercase mb-2">Siswa</label>
                        <select id="inputSiswa"
                            class="w-full border border-slate-300 rounded-xl px-4 py-3 text-sm focus:ring-2 focus:ring-blue-500/30 outline-none">
                            <option value="">-- Pilih Kelas Dulu --</option>
                        </select>
                    </div>
                </div>
                <div>
                    <label class="block text-xs font-bold text-slate-700 uppercase mb-2">Tanggal Kejadian</label>
                    <input type="date" id="inputTanggal"
                        class="w-full border border-slate-300 rounded-xl px-4 py-3 text-sm focus:ring-2 focus:ring-blue-500/30 outline-none" />
                </div>
                <div>
                    <label class="block text-xs font-bold text-slate-700 uppercase mb-2">Jenis Pelanggaran</label>
                    <select id="inputJenis"
                        class="w-full border border-slate-300 rounded-xl px-4 py-3 text-sm focus:ring-2 focus:ring-blue-500/30 outline-none">
                        <option value="">-- Pilih --</option>
                        @foreach($jenisList as $j)
                            <option value="{{ $j['id'] }}">{{ $j['pelanggaran'] }} (+{{ $j['poin'] }})</option>
                        @endforeach
                    </select>
                </div>
                <div>
                    <label class="block text-xs font-bold text-slate-700 uppercase mb-2">Kronologi</label>
                    <textarea id="inputKeterangan" rows="3"
                        class="w-full border border-slate-300 rounded-xl px-4 py-3 text-sm focus:ring-2 focus:ring-blue-500/30 outline-none resize-none"
                        placeholder="Ceritakan kronologi kejadian..."></textarea>
                </div>
            </div>
            <div class="p-6 border-t border-slate-100 flex justify-end gap-3 bg-slate-50/50 rounded-b-3xl">
                <button onclick="toggleModal('modalInputPelanggaran')"
                    class="px-6 py-2.5 border border-slate-300 bg-white text-slate-700 rounded-xl text-sm font-bold hover:bg-slate-50">
                    Batal
                </button>
                <button onclick="submitInputPelanggaran()"
                    class="px-6 py-2.5 bg-red-600 text-white rounded-xl text-sm font-bold hover:bg-red-700 shadow-lg shadow-red-500/30">
                    Simpan Data
                </button>
            </div>
        </div>
    </div>

    {{-- ── MODAL EDIT PELANGGARAN ──────────────────────────────────────── --}}
    <div id="modalEditPelanggaran" class="fixed inset-0 bg-black/50 hidden items-center justify-center z-50 backdrop-blur-sm">
        <div class="bg-white rounded-3xl w-full max-w-lg shadow-2xl mx-4">
            <div class="flex justify-between items-center p-6 border-b border-slate-100">
                <h3 class="font-bold text-slate-800 text-lg">Edit Pelanggaran</h3>
                <button onclick="toggleModal('modalEditPelanggaran')"
                    class="w-8 h-8 rounded-full bg-slate-100 flex items-center justify-center hover:bg-slate-200">
                    <i class="fa-solid fa-xmark text-slate-600"></i>
                </button>
            </div>
            <div class="p-6 space-y-5">
                <input type="hidden" id="editPelanggaranId" />

                {{-- Nama Siswa & Kelas — disabled --}}
                <div class="grid grid-cols-2 gap-4">
                    <div>
                        <label class="block text-xs font-bold text-slate-700 uppercase mb-2">Nama Siswa</label>
                        <input type="text" id="editNamaSiswa" disabled
                            class="w-full border border-slate-200 rounded-xl px-4 py-3 text-sm bg-slate-50 text-slate-500 cursor-not-allowed outline-none" />
                    </div>
                    <div>
                        <label class="block text-xs font-bold text-slate-700 uppercase mb-2">Kelas</label>
                        <input type="text" id="editKelas" disabled
                            class="w-full border border-slate-200 rounded-xl px-4 py-3 text-sm bg-slate-50 text-slate-500 cursor-not-allowed outline-none" />
                    </div>
                </div>

                {{-- Jenis Pelanggaran — disabled --}}
                <div>
                    <label class="block text-xs font-bold text-slate-700 uppercase mb-2">Jenis Pelanggaran</label>
                    <input type="text" id="editJenisPelanggaran" disabled
                        class="w-full border border-slate-200 rounded-xl px-4 py-3 text-sm bg-slate-50 text-slate-500 cursor-not-allowed outline-none" />
                </div>

                {{-- Tanggal — editable --}}
                <div>
                    <label class="block text-xs font-bold text-slate-700 uppercase mb-2">Tanggal Kejadian</label>
                    <input type="date" id="editTanggal"
                        class="w-full border border-slate-300 rounded-xl px-4 py-3 text-sm focus:ring-2 focus:ring-blue-500/30 outline-none" />
                </div>

                {{-- Kronologi — editable --}}
                <div>
                    <label class="block text-xs font-bold text-slate-700 uppercase mb-2">Kronologi</label>
                    <textarea id="editKeterangan" rows="3"
                        class="w-full border border-slate-300 rounded-xl px-4 py-3 text-sm focus:ring-2 focus:ring-blue-500/30 outline-none resize-none"
                        placeholder="Ceritakan kronologi kejadian..."></textarea>
                </div>
            </div>
            <div class="p-6 border-t border-slate-100 flex justify-end gap-3 bg-slate-50/50 rounded-b-3xl">
                <button onclick="toggleModal('modalEditPelanggaran')"
                    class="px-6 py-2.5 border border-slate-300 rounded-xl font-bold text-sm text-slate-600 hover:bg-slate-50">
                    Batal
                </button>
                <button onclick="submitEditPelanggaran()"
                    class="px-6 py-2.5 bg-blue-600 text-white rounded-xl font-bold text-sm hover:bg-blue-700 shadow-lg">
                    Update
                </button>
            </div>
        </div>
    </div>

    {{-- ── MODAL JENIS PELANGGARAN ─────────────────────────────────────── --}}
    <div id="modalJenis" class="fixed inset-0 bg-black/60 hidden items-center justify-center z-50 backdrop-blur-sm">
        <div class="bg-white rounded-2xl w-full max-w-sm p-6 shadow-2xl mx-4">
            <h3 id="modalJenisTitle" class="font-bold text-lg text-slate-800 mb-5 text-center uppercase tracking-wide">
                Tambah Jenis Pelanggaran
            </h3>
            <input type="hidden" id="jenisId" />
            <div class="space-y-4">
                <div>
                    <label class="text-xs font-bold text-slate-500 uppercase block mb-1">Nama Pelanggaran</label>
                    <input type="text" id="jenisNama"
                        class="w-full border border-slate-200 p-3 rounded-xl text-sm outline-none focus:ring-2 focus:ring-blue-500/30" />
                </div>
                <div>
                    <label class="text-xs font-bold text-slate-500 uppercase block mb-1">Poin Sanksi</label>
                    <input type="number" id="jenisPoin" min="0"
                        class="w-full border border-slate-200 p-3 rounded-xl text-sm outline-none focus:ring-2 focus:ring-blue-500/30" />
                </div>
                <div class="flex gap-3 pt-2">
                    <button onclick="toggleModal('modalJenis')"
                        class="flex-1 border border-slate-200 py-2.5 rounded-xl font-bold text-sm text-slate-600 hover:bg-slate-50">
                        Batal
                    </button>
                    <button onclick="submitJenis()"
                        class="flex-1 bg-blue-600 text-white py-2.5 rounded-xl font-bold text-sm hover:bg-blue-700">
                        Simpan
                    </button>
                </div>
            </div>
        </div>
    </div>

    @push('scripts')
    <script>
        const allSiswa  = @json($siswaList);
        const csrfToken = '{{ csrf_token() }}';

        // ── Fix SweetAlert sidebar shift ───────────────────────────────
        document.addEventListener('DOMContentLoaded', () => {
            const style = document.createElement('style');
            style.innerHTML = `
                body.swal2-shown,
                html.swal2-shown { overflow: auto !important; padding-right: 0 !important; }
                body.swal2-height-auto { height: auto !important; }
                .swal2-container { position: fixed !important; }
            `;
            document.head.appendChild(style);
        });

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

        function toggleModal(id) {
            const el = document.getElementById(id);
            el.classList.toggle('hidden');
            el.classList.toggle('flex');
        }

        function switchInputTab(tab) {
            const isData = tab === 'data';
            document.getElementById('view-pelanggaran-data').classList.toggle('hidden', !isData);
            document.getElementById('view-pelanggaran-panduan').classList.toggle('hidden', isData);

            const btnData    = document.getElementById('tab-btn-data');
            const btnPanduan = document.getElementById('tab-btn-panduan');

            btnData.className    = 'px-6 py-2 text-sm font-bold border-b-2 transition-all ' + (isData ? 'border-primary text-primary' : 'border-transparent text-slate-500 hover:text-slate-800');
            btnPanduan.className = 'px-6 py-2 text-sm font-bold border-b-2 transition-all ' + (!isData ? 'border-primary text-primary' : 'border-transparent text-slate-500 hover:text-slate-800');
        }

        function filterSiswaByKelas() {
            const kelas    = document.getElementById('inputKelas').value;
            const select   = document.getElementById('inputSiswa');
            const filtered = allSiswa.filter(s => s.kelas === kelas);

            select.innerHTML = '<option value="">-- Pilih Siswa --</option>';
            filtered.forEach(s => {
                const opt       = document.createElement('option');
                opt.value       = s.id;
                opt.textContent = s.nama;
                select.appendChild(opt);
            });
        }

        // ── Submit Input Pelanggaran ───────────────────────────────────
        async function submitInputPelanggaran() {
            const id_siswa             = document.getElementById('inputSiswa').value;
            const id_jenis_pelanggaran = document.getElementById('inputJenis').value;
            const tanggal              = document.getElementById('inputTanggal').value;
            const keterangan           = document.getElementById('inputKeterangan').value;

            if (!id_siswa || !id_jenis_pelanggaran || !tanggal) {
                SwalFixed.fire('Perhatian', 'Siswa, jenis pelanggaran, dan tanggal wajib diisi.', 'warning');
                return;
            }

            try {
                const res  = await fetch('{{ route("bk.pelanggaran.store") }}', {
                    method: 'POST',
                    headers: { 'Content-Type': 'application/json', 'X-CSRF-TOKEN': csrfToken },
                    body: JSON.stringify({ id_siswa, id_jenis_pelanggaran, tanggal, keterangan }),
                });
                const data = await res.json();

                if (data.success) {
                    toggleModal('modalInputPelanggaran');
                    SwalFixed.fire({ title: 'Sukses!', text: data.message, icon: 'success', timer: 1500, showConfirmButton: false })
                        .then(() => location.reload());
                } else {
                    SwalFixed.fire('Gagal', data.message, 'error');
                }
            } catch (e) {
                SwalFixed.fire('Error', 'Terjadi kesalahan.', 'error');
            }
        }

        // ── Open Edit Pelanggaran ──────────────────────────────────────
        function openEditPelanggaran(btn) {
            document.getElementById('editPelanggaranId').value    = btn.dataset.id;
            document.getElementById('editNamaSiswa').value        = btn.dataset.nama ?? '-';
            document.getElementById('editKelas').value            = btn.dataset.kelas ?? '-';
            document.getElementById('editJenisPelanggaran').value = btn.dataset.pelanggaran ?? '-';
            document.getElementById('editTanggal').value          = btn.dataset.tanggal ?? '';
            document.getElementById('editKeterangan').value       = btn.dataset.keterangan ?? '';
            toggleModal('modalEditPelanggaran');
        }

        // ── Submit Edit Pelanggaran ────────────────────────────────────
        async function submitEditPelanggaran() {
            const id         = document.getElementById('editPelanggaranId').value;
            const tanggal    = document.getElementById('editTanggal').value;
            const keterangan = document.getElementById('editKeterangan').value;

            if (!tanggal) {
                SwalFixed.fire('Perhatian', 'Tanggal wajib diisi.', 'warning');
                return;
            }

            try {
                const res  = await fetch(`/bk/input-pelanggaran/${id}/update`, {
                    method: 'POST',
                    headers: { 'Content-Type': 'application/json', 'X-CSRF-TOKEN': csrfToken },
                    body: JSON.stringify({ tanggal, keterangan }),
                });
                const data = await res.json();

                if (data.success) {
                    toggleModal('modalEditPelanggaran');
                    SwalFixed.fire({ title: 'Diperbarui!', text: data.message, icon: 'success', timer: 1500, showConfirmButton: false })
                        .then(() => location.reload());
                } else {
                    SwalFixed.fire('Gagal', data.message, 'error');
                }
            } catch (e) {
                SwalFixed.fire('Error', 'Terjadi kesalahan.', 'error');
            }
        }

        // ── Confirm Delete Pelanggaran ─────────────────────────────────
        function confirmDeletePelanggaran(id) {
            SwalFixed.fire({
                title: 'Hapus Data Ini?',
                text: 'Tindakan ini tidak dapat dibatalkan!',
                icon: 'warning',
                showCancelButton: true,
                confirmButtonColor: '#ef4444',
                cancelButtonColor: '#64748b',
                confirmButtonText: 'Ya, Hapus',
                cancelButtonText: 'Batal',
            }).then(async result => {
                if (result.isConfirmed) {
                    try {
                        const res  = await fetch(`/bk/input-pelanggaran/${id}/delete`, {
                            method: 'POST',
                            headers: { 'Content-Type': 'application/json', 'X-CSRF-TOKEN': csrfToken },
                        });
                        const data = await res.json();

                        if (data.success) {
                            SwalFixed.fire({ title: 'Terhapus!', icon: 'success', timer: 1200, showConfirmButton: false })
                                .then(() => location.reload());
                        } else {
                            SwalFixed.fire('Gagal', data.message, 'error');
                        }
                    } catch (e) {
                        SwalFixed.fire('Error', 'Terjadi kesalahan.', 'error');
                    }
                }
            });
        }

        // ── Jenis Pelanggaran ──────────────────────────────────────────
        function openModalJenis(mode, btn = null) {
            document.getElementById('jenisId').value   = btn ? btn.dataset.id : '';
            document.getElementById('jenisNama').value = btn ? btn.dataset.nama : '';
            document.getElementById('jenisPoin').value = btn ? btn.dataset.poin : '';
            document.getElementById('modalJenisTitle').innerText = mode === 'edit'
                ? 'EDIT JENIS PELANGGARAN' : 'TAMBAH JENIS PELANGGARAN';
            toggleModal('modalJenis');
        }

        async function submitJenis() {
            const id     = document.getElementById('jenisId').value;
            const nama   = document.getElementById('jenisNama').value.trim();
            const poin   = document.getElementById('jenisPoin').value;
            const isEdit = !!id;

            if (!nama || poin === '') {
                SwalFixed.fire('Perhatian', 'Nama dan poin wajib diisi.', 'warning');
                return;
            }

            try {
                const url = isEdit
                    ? `/bk/jenis-pelanggaran/${id}/update`
                    : '{{ route("bk.jenis.store") }}';

                const res  = await fetch(url, {
                    method: 'POST',
                    headers: { 'Content-Type': 'application/json', 'X-CSRF-TOKEN': csrfToken },
                    body: JSON.stringify({ pelanggaran: nama, poin: parseInt(poin) }),
                });
                const data = await res.json();

                if (data.success) {
                    toggleModal('modalJenis');
                    SwalFixed.fire({ title: 'Berhasil!', text: data.message, icon: 'success', timer: 1500, showConfirmButton: false })
                        .then(() => location.reload());
                } else {
                    SwalFixed.fire('Gagal', data.message, 'error');
                }
            } catch (e) {
                SwalFixed.fire('Error', 'Terjadi kesalahan.', 'error');
            }
        }

        function confirmDeleteJenis(id) {
            if (!id) {
                SwalFixed.fire('Error', 'ID tidak ditemukan.', 'error');
                return;
            }
            SwalFixed.fire({
                title: 'Hapus Jenis Pelanggaran?',
                text: 'Semua data pelanggaran terkait bisa terdampak!',
                icon: 'warning',
                showCancelButton: true,
                confirmButtonColor: '#ef4444',
                cancelButtonColor: '#64748b',
                confirmButtonText: 'Ya, Hapus',
                cancelButtonText: 'Batal',
            }).then(async result => {
                if (result.isConfirmed) {
                    try {
                        const res  = await fetch(`/bk/jenis-pelanggaran/${id}/delete`, {
                            method: 'POST',
                            headers: { 'Content-Type': 'application/json', 'X-CSRF-TOKEN': csrfToken },
                        });
                        const data = await res.json();

                        if (data.success) {
                            SwalFixed.fire({ title: 'Terhapus!', icon: 'success', timer: 1200, showConfirmButton: false })
                                .then(() => location.reload());
                        } else {
                            SwalFixed.fire('Gagal', data.message, 'error');
                        }
                    } catch (e) {
                        SwalFixed.fire('Error', 'Terjadi kesalahan.', 'error');
                    }
                }
            });
        }
    </script>
    @endpush

</x-layout-app>
