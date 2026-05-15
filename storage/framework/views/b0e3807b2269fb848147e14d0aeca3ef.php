<?php if (isset($component)) { $__componentOriginal1517f3a8d67063730434eec5aab2d6f3 = $component; } ?>
<?php if (isset($attributes)) { $__attributesOriginal1517f3a8d67063730434eec5aab2d6f3 = $attributes; } ?>
<?php $component = Illuminate\View\AnonymousComponent::resolve(['view' => 'components.layout-app','data' => ['title' => 'Pemantauan Siswa','role' => $role]] + (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag ? $attributes->all() : [])); ?>
<?php $component->withName('layout-app'); ?>
<?php if ($component->shouldRender()): ?>
<?php $__env->startComponent($component->resolveView(), $component->data()); ?>
<?php if (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag): ?>
<?php $attributes = $attributes->except(\Illuminate\View\AnonymousComponent::ignoredParameterNames()); ?>
<?php endif; ?>
<?php $component->withAttributes(['title' => 'Pemantauan Siswa','role' => \Illuminate\View\Compilers\BladeCompiler::sanitizeComponentAttribute($role)]); ?>

    <div class="space-y-4 fade-in pb-6">

        
        <div id="list-container" class="bg-white rounded-2xl shadow-sm border border-slate-200 overflow-hidden transition-opacity duration-300 flex flex-col">

            
            <div class="p-4 lg:p-5 border-b border-slate-100 bg-slate-50 flex flex-col lg:flex-row justify-between items-start lg:items-center gap-4">
                <div>
                    <h3 class="font-bold text-base text-slate-800">Daftar Siswa Dalam Pantauan</h3>
                    <p class="text-[11px] text-slate-500 mt-0.5">Diurutkan berdasarkan akumulasi poin tertinggi.</p>
                </div>

                
                <form id="filterFormPemantauan" method="GET" action="<?php echo e(route('bk.pemantauan')); ?>" class="flex flex-wrap sm:flex-nowrap gap-2 w-full lg:w-auto items-center">

                    
                    <?php if(request('id_tahun_ajaran')): ?>
                        <input type="hidden" name="id_tahun_ajaran" value="<?php echo e(request('id_tahun_ajaran')); ?>">
                    <?php endif; ?>

                    <div class="relative w-full sm:w-32">
                        <select name="kelas" class="auto-submit appearance-none pl-3 pr-8 py-1.5 bg-white border border-slate-200 rounded-lg text-[11px] w-full focus:ring-2 focus:ring-blue-500/20 outline-none transition font-bold text-slate-600 h-[32px] cursor-pointer shadow-sm">
                            <option value="">Semua Kelas</option>
                            <?php $__currentLoopData = ['7A','7B','7C','7D','7E','7F','7G','8A','8B','8C','8D','8E','8F','8G','9A','9B','9C','9D','9E','9F','9G']; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $k): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                <option value="<?php echo e($k); ?>" <?php echo e(request('kelas') == $k ? 'selected' : ''); ?>><?php echo e($k); ?></option>
                            <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                        </select>
                        <i class="fa-solid fa-chevron-down absolute right-3 top-1/2 -translate-y-1/2 text-slate-400 text-[9px] pointer-events-none"></i>
                    </div>

                    <div class="relative w-full sm:w-56">
                        <i class="fa-solid fa-search absolute left-3 top-1/2 -translate-y-1/2 text-slate-400 text-[10px] pointer-events-none"></i>
                        <input
                            type="text" name="search" value="<?php echo e(request('search')); ?>" placeholder="Cari Siswa..."
                            class="pl-8 pr-3 py-1.5 bg-white border border-slate-200 rounded-lg text-[11px] w-full focus:ring-2 focus:ring-blue-500/20 outline-none transition font-medium h-[32px] shadow-sm"
                        />
                    </div>

                    <button type="submit" class="px-4 bg-[#2563eb] text-white rounded-lg text-[11px] font-bold hover:bg-blue-700 transition shadow-sm h-[32px] flex items-center justify-center shrink-0">
                        Cari
                    </button>

                    <?php if(request('search') || request('kelas')): ?>
                        <a href="<?php echo e(route('bk.pemantauan', request('id_tahun_ajaran') ? ['id_tahun_ajaran' => request('id_tahun_ajaran')] : [])); ?>"
                            class="ajax-link px-3 bg-white border border-slate-200 text-slate-500 rounded-lg text-[11px] font-bold hover:bg-slate-50 transition flex items-center justify-center h-[32px] shrink-0 shadow-sm">
                            Reset
                        </a>
                    <?php endif; ?>
                </form>
            </div>

            
            <div class="p-4 lg:p-5 space-y-3 bg-white flex-1">
                <?php
                    $safeSiswaList = is_array($siswaList ?? null) ? $siswaList : [];
                ?>

                <?php $__empty_1 = true; $__currentLoopData = $safeSiswaList; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $s): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); $__empty_1 = false; ?>
                    <?php
                        $poin = $s['total_poin'] ?? 0;
                        $barColor   = $poin >= 75 ? 'bg-red-500'    : ($poin >= 40 ? 'bg-orange-400' : ($poin >= 15 ? 'bg-yellow-400' : 'bg-green-400'));
                        $badgeColor = $poin >= 75 ? 'bg-red-100 text-red-600 border-red-200' : ($poin >= 40 ? 'bg-orange-100 text-orange-600 border-orange-200' : ($poin >= 15 ? 'bg-yellow-100 text-yellow-700 border-yellow-200' : 'bg-green-100 text-green-700 border-green-200'));
                        $hoverColor = $poin >= 75 ? 'group-hover:text-red-600' : ($poin >= 40 ? 'group-hover:text-orange-600' : ($poin >= 15 ? 'group-hover:text-yellow-600' : 'group-hover:text-green-600'));

                        $rawRiwayat = is_string($s['riwayat_pelanggaran'] ?? null) ? json_decode($s['riwayat_pelanggaran'], true) : ($s['riwayat_pelanggaran'] ?? []);
                        $riwayat    = array_values(array_filter($rawRiwayat ?? []));
                        $ringkasan  = collect($riwayat)->pluck('pelanggaran')->filter()->unique()->take(3)->implode(', ');
                        $extra      = count($riwayat) > 3 ? ', ...' : '';
                    ?>

                    <div class="bg-white p-4 rounded-xl border border-slate-200 shadow-sm hover:shadow-md transition cursor-pointer group flex items-center justify-between"
                        onclick="bukaDetailSiswa('<?php echo e($s['id']); ?>', this)" data-id="<?php echo e($s['id']); ?>" data-nama="<?php echo e($s['nama_siswa']); ?>" data-kelas="<?php echo e($s['kelas']); ?>" data-poin="<?php echo e($poin); ?>" data-nisn="<?php echo e($s['nisn'] ?? '-'); ?>">
                        <div class="flex items-center gap-3">
                            <div class="w-1.5 h-10 <?php echo e($barColor); ?> rounded-full flex-shrink-0"></div>
                            <div>
                                <h4 class="font-bold text-slate-800 text-[13px] <?php echo e($hoverColor); ?> transition">
                                    <?php echo e($s['nama_siswa']); ?>

                                    <span class="ml-1.5 bg-slate-100 text-slate-500 text-[9px] px-1.5 py-0.5 rounded font-bold align-middle border border-slate-200"><?php echo e($s['kelas']); ?></span>
                                </h4>
                                <p class="text-[11px] text-slate-500 line-clamp-1 mt-0.5">
                                    <?php if($ringkasan): ?> <?php echo e($ringkasan); ?><?php echo e($extra); ?> <?php else: ?> <span class="italic text-slate-300">Belum ada pelanggaran tercatat.</span> <?php endif; ?>
                                </p>
                            </div>
                        </div>
                        <div class="flex items-center gap-3 flex-shrink-0">
                            <span class="font-bold px-2.5 py-1 rounded-md text-[10px] border <?php echo e($badgeColor); ?>"><?php echo e($poin); ?> Poin</span>
                            <button class="w-7 h-7 rounded-full bg-slate-50 text-slate-400 hover:bg-slate-100 hover:text-blue-500 flex items-center justify-center transition text-[10px]">
                                <i class="fa-solid fa-chevron-right"></i>
                            </button>
                        </div>
                    </div>
                <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); if ($__empty_1): ?>
                    <div class="py-12 text-center bg-slate-50 rounded-xl border border-slate-200 border-dashed">
                        <i class="fa-solid fa-users-slash text-3xl text-slate-300 mb-2 block"></i>
                        <p class="text-slate-400 text-[11px] font-medium"><?php echo e(request('search') || request('kelas') ? 'Tidak ada siswa yang cocok dengan filter.' : 'Belum ada data siswa.'); ?></p>
                    </div>
                <?php endif; ?>
            </div>

            
            <?php if(($pagination['totalPages'] ?? 1) > 0): ?>
                <div class="p-4 border-t border-slate-100 bg-slate-50 flex flex-col md:flex-row justify-between items-center text-[10px] text-slate-500 gap-3">
                    <span class="font-medium">Menampilkan <?php echo e(count($safeSiswaList)); ?> dari <?php echo e($pagination['total'] ?? 0); ?> data</span>
                    <div class="flex gap-1">
                        <?php if(($pagination['page'] ?? 1) > 1): ?>
                            <a href="<?php echo e(route('bk.pemantauan', array_merge(request()->query(), ['page' => max(1, request('page', 1) - 1)]))); ?>" class="ajax-link px-2.5 py-1.5 bg-white border border-slate-200 rounded-lg text-slate-600 hover:bg-slate-100 transition font-bold shadow-sm">Prev</a>
                        <?php else: ?>
                            <button disabled class="px-2.5 py-1.5 bg-white border border-slate-200 rounded-lg text-slate-400 opacity-50 font-bold cursor-not-allowed shadow-sm">Prev</button>
                        <?php endif; ?>

                        <?php for($i = max(1, ($pagination['page'] ?? 1) - 2); $i <= min($pagination['totalPages'] ?? 1, ($pagination['page'] ?? 1) + 2); $i++): ?>
                            <a href="<?php echo e(route('bk.pemantauan', array_merge(request()->query(), ['page' => $i]))); ?>" class="ajax-link px-2.5 py-1.5 rounded-lg border transition font-bold shadow-sm <?php echo e($i == request('page', 1) ? 'bg-[#2563eb] text-white border-blue-600' : 'bg-white border-slate-200 hover:bg-slate-100 text-slate-600'); ?>"><?php echo e($i); ?></a>
                        <?php endfor; ?>

                        <?php if(($pagination['page'] ?? 1) < ($pagination['totalPages'] ?? 1)): ?>
                            <a href="<?php echo e(route('bk.pemantauan', array_merge(request()->query(), ['page' => min($pagination['totalPages'] ?? 1, request('page', 1) + 1)]))); ?>" class="ajax-link px-2.5 py-1.5 bg-white border border-slate-200 rounded-lg text-slate-600 hover:bg-slate-100 transition font-bold shadow-sm">Next</a>
                        <?php else: ?>
                            <button disabled class="px-2.5 py-1.5 bg-white border border-slate-200 rounded-lg text-slate-400 opacity-50 font-bold cursor-not-allowed shadow-sm">Next</button>
                        <?php endif; ?>
                    </div>
                </div>
            <?php endif; ?>

        </div>

    </div>

    
    <div id="modalDetailSiswa" class="fixed inset-0 bg-navy-900/50 hidden items-center justify-center z-50 backdrop-blur-sm">
        <div class="bg-white rounded-2xl w-full max-w-4xl shadow-2xl mx-4 overflow-hidden max-h-[90vh] flex flex-col">

            
            <div class="p-4 lg:p-5 border-b border-slate-100 flex justify-between items-center bg-slate-50 flex-shrink-0">
                <div class="flex items-center gap-3">
                    <div id="modalAvatar"
                        class="w-10 h-10 rounded-full bg-blue-600 text-white flex items-center justify-center text-lg font-bold shadow-md flex-shrink-0">--</div>
                    <div>
                        <h2 id="modalNama" class="text-sm font-bold text-slate-800">-</h2>
                        <div class="flex gap-2 mt-0.5 flex-wrap">
                            <span id="modalKelas" class="bg-white border border-slate-200 text-slate-600 px-2 py-0.5 rounded text-[9px] font-bold shadow-sm">-</span>
                            <span id="modalBadgePoin" class="bg-red-100 text-red-600 px-2 py-0.5 rounded text-[9px] font-bold border border-red-200 shadow-sm">0 Poin</span>
                        </div>
                    </div>
                </div>
                <button onclick="tutupModal()" class="text-slate-400 hover:text-red-500 transition w-7 h-7 flex items-center justify-center rounded bg-white border border-slate-200 hover:bg-red-50 shadow-sm">
                    <i class="fa-solid fa-xmark text-[11px]"></i>
                </button>
            </div>

            
            <div id="modalLoading" class="flex-1 flex items-center justify-center py-12">
                <div class="text-center">
                    <i class="fa-solid fa-spinner fa-spin text-2xl text-[#2563eb] mb-2 block"></i>
                    <p class="text-[11px] text-slate-400">Memuat data...</p>
                </div>
            </div>

            
            <div id="modalContent" class="hidden flex-1 overflow-y-auto custom-scroll">
                <div class="p-5 lg:p-6 grid grid-cols-1 md:grid-cols-2 gap-5 lg:gap-6">

                    
                    <div class="space-y-4">
                        <div class="bg-white border border-slate-200 rounded-xl p-5 shadow-sm">
                            <h4 class="font-bold text-slate-800 mb-3 text-[11px] uppercase tracking-wide flex items-center gap-1.5">
                                <i class="fa-solid fa-users text-blue-500"></i> Data Orang Tua / Wali
                            </h4>
                            <div id="modalOrangTua" class="space-y-2.5 text-[11px] text-slate-600">
                                <p class="text-slate-300 italic text-[10px]">Memuat...</p>
                            </div>
                        </div>
                    </div>

                    
                    <div class="space-y-4">

                        
                        <div id="modalKronologiWrap" class="hidden">
                            <div class="flex items-center gap-1.5 mb-2">
                                <i class="fa-solid fa-comment-dots text-blue-500 text-[10px]"></i>
                                <span class="text-[10px] font-bold text-slate-600 uppercase tracking-wide">Catatan Kronologi Terakhir</span>
                            </div>
                            <div class="bg-blue-50 border border-blue-100 rounded-xl p-3.5 shadow-inner">
                                <p id="modalKronologiTanggal" class="text-blue-600 font-bold text-[11px] mb-1"></p>
                                <p id="modalKronologiTeks" class="text-slate-700 text-[11px] italic leading-relaxed"></p>
                            </div>
                        </div>

                        
                        <div>
                            <h4 class="font-bold text-red-600 mb-2.5 flex items-center gap-1.5 text-[11px]">
                                <i class="fa-solid fa-triangle-exclamation"></i> Riwayat Pelanggaran
                            </h4>
                            <div class="bg-red-50 border border-red-100 rounded-xl overflow-hidden shadow-sm">
                                <table class="w-full text-left text-[10px]">
                                    <thead class="bg-red-100 text-red-800 border-b border-red-200">
                                        <tr>
                                            <th class="p-2.5 font-bold pl-4">Tanggal</th>
                                            <th class="p-2.5 font-bold">Kasus</th>
                                            <th class="p-2.5 text-right font-bold pr-4">Poin</th>
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

    <?php $__env->startPush('scripts'); ?>
    <script>
        const csrfToken = '<?php echo e(csrf_token()); ?>';

        // ── SCRIPT AJAX EVENT DELEGATION ────────────────────────────────────
        document.addEventListener('DOMContentLoaded', () => {

            // 1. Tangkap Dropdown Filter Kelas (auto-submit)
            document.addEventListener('change', e => {
                if (e.target.matches('.auto-submit')) {
                    const form = e.target.closest('form');
                    if (form) executeAjaxFilter(form);
                }
            });

            // 2. Tangkap Form Submit (Untuk input Pencarian/Tombol Cari)
            document.addEventListener('submit', e => {
                const form = e.target;
                if (form.id === 'filterFormPemantauan') {
                    e.preventDefault();
                    executeAjaxFilter(form);
                }
            });

            // 3. Tangkap Pagination Links & Reset Buttons
            document.addEventListener('click', e => {
                const link = e.target.closest('.ajax-link');
                if (link) {
                    e.preventDefault();
                    reloadListData(link.href);
                }
            });
        });

        function executeAjaxFilter(form) {
            const url = new URL(form.action);
            const params = new URLSearchParams(window.location.search);
            const formData = new FormData(form);

            // Perbarui params URL dengan value dari Form (termasuk kelas & search)
            for (const [key, value] of formData.entries()) {
                if (value) params.set(key, value);
                else params.delete(key);
            }
            params.set('page', 1); // Reset page 1 setiap kali filter baru

            reloadListData(url.pathname + '?' + params.toString());
        }

        async function reloadListData(url) {
            const container = document.getElementById('list-container');
            container.style.opacity = '0.5';
            container.style.pointerEvents = 'none';

            try {
                const res = await fetch(url, { headers: { 'X-Requested-With': 'XMLHttpRequest' } });
                const html = await res.text();

                const doc = new DOMParser().parseFromString(html, 'text/html');
                const newContainer = doc.getElementById('list-container');

                if (newContainer) {
                    // Update seluruh isi satu card (termasuk form & pagination di dalamnya)
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

        // ── SCRIPT MODAL DETAIL SISWA ──────────────────────────────────────────
        function tutupModal() {
            const el = document.getElementById('modalDetailSiswa');
            el.classList.add('hidden');
            el.classList.remove('flex');
        }

        document.getElementById('modalDetailSiswa').addEventListener('click', function(e) {
            if (e.target === this) tutupModal();
        });

        async function bukaDetailSiswa(id, card) {
            const nama  = card.dataset.nama  ?? '-';
            const kelas = card.dataset.kelas ?? '-';
            const poin  = parseInt(card.dataset.poin ?? '0');

            // Mengambil NISN dari data atribut card (baru)
            const nisn = card.dataset.nisn ?? '';

            // Ambil ID Tahun Ajaran aktif jika ada di URL
            const params = new URLSearchParams(window.location.search);
            const idTa = params.get('id_tahun_ajaran') || '';

            const inisial = nama.trim().split(/\s+/).slice(0, 2).map(w => w[0]?.toUpperCase() ?? '').join('');
            document.getElementById('modalAvatar').textContent = inisial || '--';
            document.getElementById('modalNama').textContent   = nama;
            document.getElementById('modalKelas').textContent  = 'Kelas ' + kelas;

            const badge = document.getElementById('modalBadgePoin');
            badge.textContent = 'Total Poin: ' + poin;
            badge.className   = 'px-2 py-0.5 rounded text-[10px] font-bold border shadow-sm ' +
                (poin >= 75 ? 'bg-red-100 text-red-600 border-red-200' :
                 poin >= 40 ? 'bg-orange-100 text-orange-600 border-orange-200' :
                 poin >= 15 ? 'bg-yellow-100 text-yellow-700 border-yellow-200' :
                              'bg-green-100 text-green-700 border-green-200');

            document.getElementById('modalLoading').classList.remove('hidden');
            document.getElementById('modalContent').classList.add('hidden');
            document.getElementById('modalDetailSiswa').classList.remove('hidden');
            document.getElementById('modalDetailSiswa').classList.add('flex');

            try {
                // Fetch diubah dengan menambahkan param nisn dan id_tahun_ajaran
                const res  = await fetch(`/bk/pemantauan/${id}/detail?nisn=${nisn}&id_tahun_ajaran=${idTa}`, {
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
                    '<p class="text-red-400 text-[11px] py-6 text-center px-6">Gagal memuat data detail.</p>';
            }
        }

        function renderOrangTua(siswa) {
            const wrap = document.getElementById('modalOrangTua');
            if (!siswa) {
                wrap.innerHTML = '<p class="text-slate-300 italic text-[10px]">Data tidak tersedia.</p>';
                return;
            }

            const baris = (label, value) => value
                ? `<div class="flex gap-2">
                       <span class="w-16 text-[9px] font-bold text-slate-400 uppercase tracking-wide shrink-0 pt-0.5">${label}</span>
                       <span class="text-slate-700 text-[11px]">${value}</span>
                   </div>`
                : `<div class="flex gap-2">
                       <span class="w-16 text-[9px] font-bold text-slate-400 uppercase tracking-wide shrink-0 pt-0.5">${label}</span>
                       <span class="text-slate-300 text-[11px]">-</span>
                   </div>`;

            const ayah = [siswa.nama_ayah, siswa.pekerjaan_ayah ? `(${siswa.pekerjaan_ayah})` : ''].filter(Boolean).join(' ') || null;
            const ibu  = [siswa.nama_ibu,  siswa.pekerjaan_ibu  ? `(${siswa.pekerjaan_ibu})`  : ''].filter(Boolean).join(' ') || null;
            const wali = [siswa.nama_wali, siswa.pekerjaan_wali ? `(${siswa.pekerjaan_wali})` : ''].filter(Boolean).join(' ') || null;

            let html = baris('Ayah', ayah) + baris('Ibu', ibu) + baris('Wali', wali);

            if (siswa.alamat) {
                html += `<div class="pt-2 mt-2 border-t border-slate-100">
                    <p class="text-[9px] font-bold text-slate-400 uppercase tracking-wide mb-1">Alamat</p>
                    <p class="text-slate-700 text-[11px] leading-relaxed">${siswa.alamat}</p>
                </div>`;
            }

            if (siswa.no_telepon) {
                html += `<div class="pt-2 mt-2 border-t border-slate-100">
                    <p class="text-[9px] font-bold text-slate-400 uppercase tracking-wide mb-1.5">Kontak Aktif</p>
                    <span class="bg-green-50 text-green-700 border border-green-200 shadow-sm px-2.5 py-1 rounded-md text-[11px] font-mono font-bold inline-flex items-center gap-1.5">
                        <i class="fa-solid fa-phone text-[9px]"></i> ${siswa.no_telepon}
                    </span>
                </div>`;
            }

            wrap.innerHTML = html;
        }

        // FIX Timezone: Menghindari tanggal mundur 1 hari di JavaScript
        function formatTanggalAman(str) {
            if (!str) return '-';
            const datePart = str.split('T')[0].split(' ')[0];
            const [y, m, d] = datePart.split('-');
            const dateObj = new Date(y, m - 1, d);
            return dateObj.toLocaleDateString('id-ID', {
                day: 'numeric', month: 'short', year: 'numeric'
            });
        }

        function renderKronologi(pelanggaran) {
            const wrap   = document.getElementById('modalKronologiWrap');
            const latest = pelanggaran.find(p => p.keterangan && p.keterangan.trim() !== '');

            if (!latest) {
                wrap.classList.add('hidden');
                return;
            }

            wrap.classList.remove('hidden');
            document.getElementById('modalKronologiTanggal').textContent = formatTanggalAman(latest.tanggal);
            document.getElementById('modalKronologiTeks').textContent    = `"${latest.keterangan}"`;
        }

        function renderRiwayat(pelanggaran) {
            const tbody = document.getElementById('modalRiwayatBody');
            if (!pelanggaran.length) {
                tbody.innerHTML = '<tr><td colspan="3" class="p-4 text-center text-slate-300 italic text-[10px]">Belum ada riwayat pelanggaran.</td></tr>';
                return;
            }

            tbody.innerHTML = pelanggaran.map(p => {
                const tgl = formatTanggalAman(p.tanggal);
                return `<tr>
                    <td class="p-2.5 font-mono text-slate-500 pl-4">${tgl}</td>
                    <td class="p-2.5 font-bold text-slate-700">${p.pelanggaran ?? '-'}
                        ${p.keterangan ? `<p class="font-normal text-slate-400 text-[9px] mt-0.5 italic line-clamp-1">${p.keterangan}</p>` : ''}
                    </td>
                    <td class="p-2.5 text-right font-bold text-red-600 pr-4">+${p.poin ?? 0}</td>
                </tr>`;
            }).join('');
        }
    </script>
    <?php $__env->stopPush(); ?>

 <?php echo $__env->renderComponent(); ?>
<?php endif; ?>
<?php if (isset($__attributesOriginal1517f3a8d67063730434eec5aab2d6f3)): ?>
<?php $attributes = $__attributesOriginal1517f3a8d67063730434eec5aab2d6f3; ?>
<?php unset($__attributesOriginal1517f3a8d67063730434eec5aab2d6f3); ?>
<?php endif; ?>
<?php if (isset($__componentOriginal1517f3a8d67063730434eec5aab2d6f3)): ?>
<?php $component = $__componentOriginal1517f3a8d67063730434eec5aab2d6f3; ?>
<?php unset($__componentOriginal1517f3a8d67063730434eec5aab2d6f3); ?>
<?php endif; ?>
<?php /**PATH C:\laragon\www\New foldersssss\ekesiswaan\resources\views/guru_bk/pemantauan.blade.php ENDPATH**/ ?>