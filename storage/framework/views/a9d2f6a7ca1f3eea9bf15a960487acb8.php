<?php if (isset($component)) { $__componentOriginal1517f3a8d67063730434eec5aab2d6f3 = $component; } ?>
<?php if (isset($attributes)) { $__attributesOriginal1517f3a8d67063730434eec5aab2d6f3 = $attributes; } ?>
<?php $component = Illuminate\View\AnonymousComponent::resolve(['view' => 'components.layout-app','data' => ['title' => 'Home Visit','role' => $role]] + (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag ? $attributes->all() : [])); ?>
<?php $component->withName('layout-app'); ?>
<?php if ($component->shouldRender()): ?>
<?php $__env->startComponent($component->resolveView(), $component->data()); ?>
<?php if (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag): ?>
<?php $attributes = $attributes->except(\Illuminate\View\AnonymousComponent::ignoredParameterNames()); ?>
<?php endif; ?>
<?php $component->withAttributes(['title' => 'Home Visit','role' => \Illuminate\View\Compilers\BladeCompiler::sanitizeComponentAttribute($role)]); ?>

    <div class="w-full space-y-4 pb-6 fade-in">

        
        <div class="bg-white p-4 lg:p-5 rounded-2xl shadow-sm border border-slate-200 flex flex-col md:flex-row justify-between items-start md:items-center gap-4">
            <div>
                <h3 class="font-bold text-base text-slate-800">Manajemen Home Visit</h3>
                <p class="text-[11px] text-[#ef4444] font-medium mt-0.5">Prioritas: Siswa dengan pelanggaran berat.</p>
            </div>

            <div class="flex items-center gap-2 w-full md:w-auto">
                
                <form id="filterFormVisit" method="GET" action="<?php echo e(route('bk.visit')); ?>" class="relative group flex-1 md:flex-none">
                    <select name="kelas" class="auto-submit w-full appearance-none bg-white border border-slate-200 hover:border-slate-300 rounded-lg py-1.5 pl-3 pr-8 text-[11px] font-bold text-slate-600 outline-none focus:ring-2 focus:ring-blue-500/20 cursor-pointer transition-all h-[32px] min-w-[120px]">
                        <option value="">Semua Kelas</option>
                        <?php $__currentLoopData = ['7A','7B','7C','7D','7E','7F','7G', '8A','8B','8C','8D','8E','8F','8G', '9A','9B','9C','9D','9E','9F','9G']; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $k): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                            <option value="<?php echo e($k); ?>" <?php echo e(request('kelas') == $k ? 'selected' : ''); ?>>Kelas <?php echo e($k); ?></option>
                        <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                    </select>
                    <i class="fa-solid fa-chevron-down absolute right-3 top-1/2 -translate-y-1/2 text-[9px] text-slate-400 pointer-events-none"></i>
                </form>

                <button onclick="toggleModal('modalAddVisit')" class="bg-[#2563eb] hover:bg-blue-700 text-white px-4 py-1.5 rounded-lg text-[11px] font-bold shadow-md shadow-blue-200 transition flex items-center justify-center gap-1.5 flex-shrink-0 h-[32px]">
                    <i class="fa-solid fa-plus"></i> Tambah Visit
                </button>
            </div>
        </div>

        
        <div id="list-container" class="grid grid-cols-1 lg:grid-cols-2 gap-6 transition-opacity duration-300">

            
            <div class="flex flex-col gap-3">
                <div class="p-3 bg-[#f0fdf4] border border-[#bbf7d0] rounded-xl flex justify-between items-center shadow-sm">
                    <h4 class="font-bold text-[#166534] text-[13px]">Sudah Terlaksana</h4>
                    <span class="bg-white text-[#16a34a] border border-[#86efac] px-2.5 py-1 rounded-md text-[10px] font-bold shadow-sm"><?php echo e(count($visitSelesai)); ?> Selesai</span>
                </div>

                <?php $__empty_1 = true; $__currentLoopData = $visitSelesai; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $visit): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); $__empty_1 = false; ?>
                    <?php
                        $tglDisplay = \Carbon\Carbon::parse($visit['tanggal'])->timezone('Asia/Jakarta')->format('d M Y');
                        $tglEdit    = \Carbon\Carbon::parse($visit['tanggal'])->timezone('Asia/Jakarta')->format('Y-m-d');
                    ?>
                    <div onclick="openDetailModal(<?php echo e($visit['id']); ?>)" class="bg-white rounded-xl border border-slate-200 p-4 shadow-sm flex justify-between items-center cursor-pointer hover:border-slate-300 hover:shadow-md transition group">
                        <div class="flex gap-3 items-center">
                            <div class="w-10 h-10 rounded-full bg-slate-100 flex items-center justify-center text-[11px] font-bold text-slate-500 uppercase flex-shrink-0">
                                <?php echo e(substr($visit['nama_siswa'], 0, 2)); ?>

                            </div>
                            <div>
                                <p class="text-[12px] font-bold text-slate-800 group-hover:text-[#2563eb] transition">
                                    <?php echo e($visit['nama_siswa']); ?>

                                    <span class="ml-1 text-[9px] font-normal text-slate-400">(<?php echo e($visit['kelas']); ?>)</span>
                                </p>
                                <p class="text-[10px] text-slate-400 mt-0.5 flex items-center gap-1.5"><i class="fa-regular fa-calendar"></i> <?php echo e($tglDisplay); ?></p>
                            </div>
                        </div>
                        <div class="flex gap-1.5 text-sm flex-shrink-0">
                            <button onclick="event.stopPropagation(); openEditModal(<?php echo e(json_encode($visit)); ?>, '<?php echo e($tglEdit); ?>')" class="w-7 h-7 rounded-lg bg-slate-50 border border-transparent text-slate-400 hover:text-[#2563eb] hover:border-slate-200 transition flex items-center justify-center text-[11px]" title="Edit">
                                <i class="fa-regular fa-pen-to-square"></i>
                            </button>
                            <button onclick="event.stopPropagation(); confirmDelete(<?php echo e($visit['id']); ?>)" class="w-7 h-7 rounded-lg bg-slate-50 border border-transparent text-slate-400 hover:text-[#ef4444] hover:border-slate-200 transition flex items-center justify-center text-[11px]" title="Hapus">
                                <i class="fa-regular fa-trash-can"></i>
                            </button>
                            <form id="delete-form-<?php echo e($visit['id']); ?>" action="<?php echo e(route('bk.visit.destroy', $visit['id'])); ?>" method="POST" class="hidden">
                                <?php echo csrf_field(); ?> <?php echo method_field('DELETE'); ?>
                            </form>
                        </div>
                    </div>
                <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); if ($__empty_1): ?>
                    <div class="text-center py-6 text-[11px] text-slate-400 border-2 border-dashed border-slate-200 rounded-xl">Belum ada data kunjungan terlaksana.</div>
                <?php endif; ?>
            </div>

            
            <div class="flex flex-col gap-3">
                <div class="p-3 bg-[#fff7ed] border border-[#ffedd5] rounded-xl flex justify-between items-center shadow-sm">
                    <h4 class="font-bold text-[#9a3412] text-[13px]">Rencana Kunjungan</h4>
                    <span class="bg-white text-[#ea580c] border border-[#fdba74] px-2.5 py-1 rounded-md text-[10px] font-bold shadow-sm"><?php echo e(count($visitPending)); ?> Pending</span>
                </div>

                <?php $__empty_1 = true; $__currentLoopData = $visitPending; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $visit): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); $__empty_1 = false; ?>
                    <?php
                        $tglDisplay = \Carbon\Carbon::parse($visit['tanggal'])->timezone('Asia/Jakarta')->format('d M Y');
                        $tglEdit    = \Carbon\Carbon::parse($visit['tanggal'])->timezone('Asia/Jakarta')->format('Y-m-d');
                    ?>
                    <div onclick="openDetailModal(<?php echo e($visit['id']); ?>)" class="bg-white rounded-xl border border-slate-200 p-4 shadow-sm flex justify-between items-center cursor-pointer hover:border-slate-300 hover:shadow-md transition group">
                        <div class="flex gap-3 items-center">
                            <div class="w-10 h-10 rounded-full bg-[#fee2e2] flex items-center justify-center text-[11px] font-bold text-[#ef4444] uppercase flex-shrink-0">
                                <?php echo e(substr($visit['nama_siswa'], 0, 2)); ?>

                            </div>
                            <div>
                                <p class="text-[12px] font-bold text-slate-800 group-hover:text-[#2563eb] transition">
                                    <?php echo e($visit['nama_siswa']); ?>

                                    <span class="ml-1 text-[9px] font-normal text-slate-400">(<?php echo e($visit['kelas']); ?>)</span>
                                </p>
                                <p class="text-[10px] text-[#ea580c] font-medium mt-0.5 flex items-center gap-1.5">
                                    <i class="fa-regular fa-calendar"></i> <?php echo e($tglDisplay); ?>

                                </p>
                            </div>
                        </div>
                        <div class="flex gap-1.5 text-sm flex-shrink-0">
                            <button onclick="event.stopPropagation(); openEditModal(<?php echo e(json_encode($visit)); ?>, '<?php echo e($tglEdit); ?>')" class="w-7 h-7 rounded-lg bg-slate-50 border border-transparent text-slate-400 hover:text-[#2563eb] hover:border-slate-200 transition flex items-center justify-center text-[11px]" title="Edit">
                                <i class="fa-regular fa-pen-to-square"></i>
                            </button>
                            <button onclick="event.stopPropagation(); confirmDelete(<?php echo e($visit['id']); ?>)" class="w-7 h-7 rounded-lg bg-slate-50 border border-transparent text-slate-400 hover:text-[#ef4444] hover:border-slate-200 transition flex items-center justify-center text-[11px]" title="Hapus">
                                <i class="fa-regular fa-trash-can"></i>
                            </button>
                            <form id="delete-form-<?php echo e($visit['id']); ?>" action="<?php echo e(route('bk.visit.destroy', $visit['id'])); ?>" method="POST" class="hidden">
                                <?php echo csrf_field(); ?> <?php echo method_field('DELETE'); ?>
                            </form>
                        </div>
                    </div>
                <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); if ($__empty_1): ?>
                    <div class="text-center py-6 text-[11px] text-slate-400 border-2 border-dashed border-slate-200 rounded-xl">Belum ada rencana kunjungan.</div>
                <?php endif; ?>
            </div>

        </div>
    </div>

    
    <div id="modalAddVisit" class="fixed inset-0 bg-navy-900/50 hidden items-center justify-center z-50 backdrop-blur-sm transition-opacity duration-300">
        <div class="bg-white rounded-2xl w-full max-w-md p-5 shadow-2xl transform scale-100 transition-all mx-4">
            <div class="flex justify-between items-center mb-4 pb-3 border-b border-slate-100">
                <h3 class="font-bold text-sm text-slate-800">Tambah Kunjungan Rumah</h3>
                <button onclick="toggleModal('modalAddVisit')" class="w-6 h-6 rounded bg-slate-50 text-slate-400 hover:text-red-500 hover:bg-slate-100 transition flex items-center justify-center text-[10px]">
                    <i class="fa-solid fa-xmark"></i>
                </button>
            </div>

            <form action="<?php echo e(route('bk.visit.store')); ?>" method="POST">
                <?php echo csrf_field(); ?>
                <div class="space-y-4 mb-5">
                    <div class="grid grid-cols-2 gap-4">
                        <div>
                            <label class="text-[10px] font-bold text-slate-500 uppercase tracking-wide mb-1.5 block">Kelas</label>
                            <select id="add_kelas" onchange="fetchSiswaForAdd(this.value)" class="w-full border border-slate-200 py-2 px-3 rounded-lg text-[11px] focus:ring-2 focus:ring-[#2563eb]/50 outline-none transition bg-white" required>
                                <option value="">Pilih Kelas</option>
                                <?php $__currentLoopData = ['7A','7B','7C','7D','7E','7F','7G', '8A','8B','8C','8D','8E','8F','8G', '9A','9B','9C','9D','9E','9F','9G']; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $k): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                    <option value="<?php echo e($k); ?>"><?php echo e($k); ?></option>
                                <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                            </select>
                        </div>
                        <div>
                            <label class="text-[10px] font-bold text-slate-500 uppercase tracking-wide mb-1.5 block">Siswa</label>
                            <select id="add_id_siswa" name="id_siswa" class="w-full border border-slate-200 py-2 px-3 rounded-lg text-[11px] focus:ring-2 focus:ring-[#2563eb]/50 outline-none transition bg-white" required>
                                <option value="">Pilih Siswa</option>
                            </select>
                        </div>
                    </div>
                    <div>
                        <label class="text-[10px] font-bold text-slate-500 uppercase tracking-wide mb-1.5 block">Tanggal Kunjungan</label>
                        <input type="date" name="tanggal" class="w-full border border-slate-200 py-2 px-3 rounded-lg text-[11px] focus:ring-2 focus:ring-[#2563eb]/50 outline-none transition text-slate-600" required/>
                    </div>
                    <div>
                        <label class="text-[10px] font-bold text-slate-500 uppercase tracking-wide mb-1.5 block">Status Awal</label>
                        <select name="status" class="w-full border border-slate-200 py-2 px-3 rounded-lg text-[11px] focus:ring-2 focus:ring-[#2563eb]/50 outline-none transition bg-white" required>
                            <option value="Rencana Kunjungan">Rencana Kunjungan</option>
                            <option value="Sudah Terlaksana">Sudah Terlaksana</option>
                        </select>
                    </div>
                </div>

                <div class="flex gap-2">
                    <button type="button" onclick="toggleModal('modalAddVisit')" class="flex-1 border border-slate-200 py-2 rounded-lg font-bold text-[11px] text-slate-600 hover:bg-slate-50 transition">Batal</button>
                    <button type="submit" class="flex-1 bg-[#2563eb] text-white py-2 rounded-lg font-bold text-[11px] shadow-sm hover:bg-blue-700 transition">Simpan Jadwal</button>
                </div>
            </form>
        </div>
    </div>

    
    <div id="modalEditVisit" class="fixed inset-0 bg-navy-900/50 hidden items-center justify-center z-50 backdrop-blur-sm transition-opacity duration-300">
        <div class="bg-white rounded-2xl w-full max-w-md p-5 shadow-2xl transform scale-100 transition-all mx-4">
            <div class="flex justify-between items-center mb-4 pb-3 border-b border-slate-100">
                <h3 class="font-bold text-sm text-slate-800">Edit Data Kunjungan</h3>
                <button onclick="toggleModal('modalEditVisit')" class="w-6 h-6 rounded bg-slate-50 text-slate-400 hover:text-red-500 hover:bg-slate-100 transition flex items-center justify-center text-[10px]">
                    <i class="fa-solid fa-xmark"></i>
                </button>
            </div>

            <form id="formEditVisit" method="POST">
                <?php echo csrf_field(); ?> <?php echo method_field('PATCH'); ?>
                <div class="space-y-4 mb-5">
                    <div class="grid grid-cols-2 gap-4">
                        <div>
                            <label class="text-[10px] font-bold text-slate-500 uppercase tracking-wide mb-1.5 block">Kelas</label>
                            <input type="text" id="edit_kelas" class="w-full border border-slate-200 py-2 px-3 rounded-lg text-[11px] bg-slate-50 outline-none font-bold text-slate-500" disabled>
                        </div>
                        <div>
                            <label class="text-[10px] font-bold text-slate-500 uppercase tracking-wide mb-1.5 block">Siswa</label>
                            <input type="text" id="edit_nama_siswa" class="w-full border border-slate-200 py-2 px-3 rounded-lg text-[11px] bg-slate-50 outline-none font-bold text-slate-500" disabled>
                        </div>
                    </div>
                    <div>
                        <label class="text-[10px] font-bold text-slate-500 uppercase tracking-wide mb-1.5 block">Tanggal Kunjungan</label>
                        <input type="date" id="edit_tanggal" name="tanggal" class="w-full border border-slate-200 py-2 px-3 rounded-lg text-[11px] focus:ring-2 focus:ring-[#2563eb]/50 outline-none transition text-slate-700" required/>
                    </div>
                    <div>
                        <label class="text-[10px] font-bold text-slate-500 uppercase tracking-wide mb-1.5 block">Status</label>
                        <select id="edit_status" name="status" class="w-full border border-slate-200 py-2 px-3 rounded-lg text-[11px] focus:ring-2 focus:ring-[#2563eb]/50 outline-none transition bg-white" required>
                            <option value="Rencana Kunjungan">Rencana Kunjungan</option>
                            <option value="Sudah Terlaksana">Sudah Terlaksana</option>
                        </select>
                    </div>
                </div>

                <div class="flex gap-2">
                    <button type="button" onclick="toggleModal('modalEditVisit')" class="flex-1 border border-slate-200 py-2 rounded-lg font-bold text-[11px] text-slate-600 hover:bg-slate-50 transition">Batal</button>
                    <button type="submit" class="flex-1 bg-[#2563eb] text-white py-2 rounded-lg font-bold text-[11px] shadow-sm hover:bg-blue-700 transition">Update Data</button>
                </div>
            </form>
        </div>
    </div>

    
    <div id="modalDetailVisit" class="fixed inset-0 bg-navy-900/50 hidden items-center justify-center z-50 backdrop-blur-sm transition-opacity">
        <div class="bg-white rounded-2xl w-full max-w-lg shadow-2xl overflow-hidden transform scale-100 transition-all mx-4">
            <div class="p-5 border-b border-slate-100 flex justify-between items-center bg-slate-50">
                <h3 class="font-bold text-slate-800 text-sm">Detail Kunjungan Rumah</h3>
                <button onclick="toggleModal('modalDetailVisit')" class="w-6 h-6 rounded bg-white border border-slate-200 text-slate-400 hover:text-red-500 hover:bg-slate-100 transition flex items-center justify-center text-[10px]">
                    <i class="fa-solid fa-xmark"></i>
                </button>
            </div>

            <div class="p-5" id="detailContent">
                <div class="text-center py-8 text-slate-500"><i class="fa-solid fa-spinner fa-spin text-2xl text-primary mb-2"></i><br><span class="text-[11px]">Memuat data...</span></div>
            </div>
        </div>
    </div>

    <?php $__env->startPush('scripts'); ?>
    <script>
        <?php if(session('success')): ?>
            Swal.fire({ title: 'Berhasil!', text: '<?php echo e(session("success")); ?>', icon: 'success', timer: 1500, showConfirmButton: false });
        <?php endif; ?>
        <?php if(session('error')): ?>
            Swal.fire({ title: 'Gagal!', text: '<?php echo e(session("error")); ?>', icon: 'error' });
        <?php endif; ?>

        // ── SCRIPT AJAX EVENT DELEGATION (ANTI BERKEDIP RESET) ─────────────────
        document.addEventListener('change', e => {
            if (e.target.matches('.auto-submit')) {
                const form = e.target.closest('form');
                if (form) executeAjaxFilter(form);
            }
        });

        function executeAjaxFilter(form) {
            const url = new URL(form.action);
            const params = new URLSearchParams(window.location.search);
            const formData = new FormData(form);

            for (const [key, value] of formData.entries()) {
                if (value) params.set(key, value);
                else params.delete(key);
            }

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

        // ── MODAL & LOGIC BAWAAN ────────────────────────────────────────
        function toggleModal(id) {
            const el = document.getElementById(id);
            if (el.classList.contains('hidden')) {
                el.classList.remove('hidden'); el.classList.add('flex');
            } else {
                el.classList.add('hidden'); el.classList.remove('flex');
            }
        }

        function confirmDelete(id) {
            Swal.fire({
                title: "Hapus Jadwal?",
                text: "Data kunjungan ini akan dihapus permanen.",
                icon: "warning",
                showCancelButton: true,
                confirmButtonColor: "#ef4444",
                cancelButtonColor: "#64748b",
                confirmButtonText: "Ya, Hapus",
                cancelButtonText: "Batal"
            }).then((result) => {
                if (result.isConfirmed) {
                    document.getElementById('delete-form-' + id).submit();
                }
            });
        }

        async function fetchSiswaForAdd(kelas) {
            const selectSiswa = document.getElementById('add_id_siswa');
            selectSiswa.innerHTML = '<option value="">Memuat...</option>';

            if(!kelas) {
                selectSiswa.innerHTML = '<option value="">Pilih Siswa</option>';
                return;
            }

            try {
                const res = await fetch(`<?php echo e(route('bk.visit.getSiswa')); ?>?kelas=${kelas}`);
                const data = await res.json();

                selectSiswa.innerHTML = '<option value="">Pilih Siswa</option>';
                data.forEach(siswa => {
                    selectSiswa.innerHTML += `<option value="${siswa.id}">${siswa.nama}</option>`;
                });
            } catch (e) {
                selectSiswa.innerHTML = '<option value="">Gagal memuat siswa</option>';
            }
        }

        function openEditModal(visit, tglEdit) {
            document.getElementById('edit_kelas').value = visit.kelas;
            document.getElementById('edit_nama_siswa').value = visit.nama_siswa;
            document.getElementById('edit_tanggal').value = tglEdit;
            document.getElementById('edit_status').value = visit.status;

            const formAction = `<?php echo e(url('bk/home-visit')); ?>/${visit.id}`;
            document.getElementById('formEditVisit').action = formAction;

            toggleModal('modalEditVisit');
        }

        async function openDetailModal(id) {
            toggleModal('modalDetailVisit');
            const content = document.getElementById('detailContent');
            content.innerHTML = '<div class="text-center py-8 text-slate-500"><i class="fa-solid fa-spinner fa-spin text-2xl text-primary mb-2"></i><br><span class="text-[11px]">Memuat data...</span></div>';

            try {
                const res = await fetch(`<?php echo e(url('bk/home-visit')); ?>/${id}/detail`);
                const data = await res.json();

                const dateObj = new Date(data.tanggal);
                const dateStr = dateObj.toLocaleDateString('id-ID', { day: 'numeric', month: 'long', year: 'numeric' });

                const waLink = data.no_telepon
                    ? `https://wa.me/62${data.no_telepon.replace(/^0+/, '')}`
                    : '#';

                content.innerHTML = `
                    <div class="flex items-center gap-4 mb-5 bg-gradient-to-r from-blue-50 to-indigo-50 p-4 rounded-xl border border-blue-100">
                        <div class="w-12 h-12 bg-white text-[#2563eb] rounded-full flex items-center justify-center font-bold text-lg shadow-sm border border-white uppercase flex-shrink-0">
                            ${data.nama_siswa.substring(0,2)}
                        </div>
                        <div>
                            <h4 class="font-bold text-slate-800 text-sm">${data.nama_siswa}</h4>
                            <div class="flex flex-wrap items-center gap-2 mt-1">
                                <span class="text-[9px] font-bold text-slate-600 bg-white px-2 py-0.5 rounded border border-slate-200 shadow-sm">Kelas ${data.kelas}</span>
                                <span class="text-[9px] font-bold ${data.status === 'Sudah Terlaksana' ? 'text-green-700 bg-green-100 border-green-200' : 'text-orange-700 bg-orange-100 border-orange-200'} px-2 py-0.5 rounded flex items-center gap-1 border">
                                    <i class="fa-solid ${data.status === 'Sudah Terlaksana' ? 'fa-circle-check' : 'fa-clock'}"></i> ${data.status}
                                </span>
                            </div>
                        </div>
                    </div>

                    <div class="grid grid-cols-1 md:grid-cols-2 gap-4 mb-6">
                        <div class="space-y-1">
                            <p class="text-[9px] font-bold text-slate-400 uppercase tracking-wider flex items-center gap-1">
                                <i class="fa-regular fa-calendar text-slate-400"></i> Tanggal Kunjungan
                            </p>
                            <p class="font-bold text-slate-800 text-[11px]">${dateStr}</p>
                        </div>
                        <div class="space-y-1">
                            <p class="text-[9px] font-bold text-slate-400 uppercase tracking-wider flex items-center gap-1">
                                <i class="fa-solid fa-user-group text-slate-400"></i> Orang Tua / Wali
                            </p>
                            <p class="font-bold text-slate-800 text-[11px]">${data.nama_wali}</p>
                        </div>
                        <div class="md:col-span-2 space-y-1.5">
                            <p class="text-[9px] font-bold text-slate-400 uppercase tracking-wider flex items-center gap-1">
                                <i class="fa-solid fa-location-dot text-slate-400"></i> Alamat Lengkap
                            </p>
                            <p class="font-medium text-slate-700 text-[11px] leading-relaxed bg-slate-50 p-3 rounded-lg border border-slate-200 shadow-inner">
                                ${data.alamat}
                            </p>
                        </div>
                    </div>

                    <div class="pt-2 border-t border-slate-100">
                        <a href="${waLink}" target="_blank" class="flex items-center justify-center gap-2 w-full bg-[#16a34a] hover:bg-green-700 text-white font-bold py-2.5 rounded-lg text-[11px] shadow-sm transition-all group ${!data.no_telepon ? 'opacity-50 pointer-events-none' : ''}">
                            <i class="fa-brands fa-whatsapp text-sm group-hover:scale-110 transition-transform"></i>
                            <span>Hubungi via WhatsApp</span>
                        </a>
                    </div>
                `;
            } catch (e) {
                content.innerHTML = '<div class="text-center py-8 text-red-500 text-[11px]">Gagal memuat detail data. Coba lagi.</div>';
            }
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
<?php /**PATH C:\laragon\www\New foldersssss\ekesiswaan\resources\views/guru_bk/home_visit.blade.php ENDPATH**/ ?>