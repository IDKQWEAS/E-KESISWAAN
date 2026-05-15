
<?php if (isset($component)) { $__componentOriginal1517f3a8d67063730434eec5aab2d6f3 = $component; } ?>
<?php if (isset($attributes)) { $__attributesOriginal1517f3a8d67063730434eec5aab2d6f3 = $attributes; } ?>
<?php $component = Illuminate\View\AnonymousComponent::resolve(['view' => 'components.layout-app','data' => ['title' => 'Database Prestasi','role' => $role]] + (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag ? $attributes->all() : [])); ?>
<?php $component->withName('layout-app'); ?>
<?php if ($component->shouldRender()): ?>
<?php $__env->startComponent($component->resolveView(), $component->data()); ?>
<?php if (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag): ?>
<?php $attributes = $attributes->except(\Illuminate\View\AnonymousComponent::ignoredParameterNames()); ?>
<?php endif; ?>
<?php $component->withAttributes(['title' => 'Database Prestasi','role' => \Illuminate\View\Compilers\BladeCompiler::sanitizeComponentAttribute($role)]); ?>
    
    <div class="flex-1 overflow-y-auto p-6 custom-scroll bg-[#f8fafc]">
        
        <div class="mx-auto space-y-6">

            
            <div class="flex flex-col md:flex-row justify-between items-end gap-3">
                <div>
                    <h1 class="text-xl font-bold text-slate-800 mb-1">Pusat Laporan</h1>
                    <p class="text-slate-500 text-xs">
                        Unduh rekapitulasi data berdasarkan kelas dan tahun ajaran.
                    </p>
                </div>
                
                
                <div class="flex flex-wrap gap-2.5">
                    
                    <div class="relative group">
                        
                        <label class="block text-[8px] font-black text-slate-400 uppercase mb-1 ml-1">Tahun Ajaran</label>
                        <select id="filterTA" class="appearance-none bg-white border border-slate-200 rounded-xl py-2 pl-4 pr-9 text-xs font-bold text-slate-700 outline-none focus:ring-2 focus:ring-blue-500/20 cursor-pointer min-w-[170px] transition-all shadow-sm">
                            <?php $__currentLoopData = $taList; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $ta): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                <option value="<?php echo e($ta['id']); ?>" <?php echo e($ta['status'] == 'aktif' ? 'selected' : ''); ?>>
                                    <?php echo e($ta['tahun_ajaran']); ?> - <?php echo e(ucfirst($ta['semester'])); ?>

                                </option>
                            <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                        </select>
                        <i class="fa-solid fa-chevron-down absolute right-3.5 top-7 text-[10px] text-slate-400 pointer-events-none"></i>
                    </div>

                    
                    <div class="relative group">
                        <label class="block text-[8px] font-black text-slate-400 uppercase mb-1 ml-1">Filter Kelas</label>
                        <select id="filterKelas" class="appearance-none bg-white border border-slate-200 rounded-xl py-2 pl-4 pr-9 text-xs font-bold text-slate-700 outline-none focus:ring-2 focus:ring-blue-500/20 cursor-pointer min-w-[130px] transition-all shadow-sm">
                            <option value="">Semua Kelas</option>
                            <?php $__currentLoopData = ['7A','7B','7C','7D','7E','7F','7G','8A','8B','8C','8D','8E','8F','8G','9A','9B','9C','9D','9E','9F','9G']; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $k): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                <option value="<?php echo e($k); ?>">Kelas <?php echo e($k); ?></option>
                            <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                        </select>
                        <i class="fa-solid fa-chevron-down absolute right-3.5 top-7 text-[10px] text-slate-400 pointer-events-none"></i>
                    </div>
                </div>
            </div>

            
            <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
                
                <?php $reports = [
                    ['type' => 'absensi', 'title' => 'Laporan Absensi', 'icon' => 'fa-file-pdf', 'color' => 'red'],
                    ['type' => 'pelanggaran', 'title' => 'Laporan Pelanggaran', 'icon' => 'fa-file-invoice', 'color' => 'green'],
                    ['type' => 'prestasi', 'title' => 'Laporan Prestasi', 'icon' => 'fa-trophy', 'color' => 'yellow']
                ]; ?>

                <?php $__currentLoopData = $reports; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $rpt): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                
                <div class="bg-white p-8 rounded-[1.5rem] shadow-sm border border-slate-200 text-center hover:shadow-xl hover:-translate-y-1 transition-all duration-300 group relative overflow-hidden">
                    <div class="absolute top-0 left-0 w-full h-1 bg-<?php echo e($rpt['color']); ?>-500 transform scale-x-0 group-hover:scale-x-100 transition-transform duration-500 origin-center"></div>
                    
                    
                    <div class="w-20 h-20 bg-<?php echo e($rpt['color']); ?>-50 rounded-full flex items-center justify-center mx-auto mb-5 group-hover:bg-<?php echo e($rpt['color']); ?>-100 transition-colors duration-300">
                        <i class="fa-solid <?php echo e($rpt['icon']); ?> text-3xl text-<?php echo e($rpt['color']); ?>-500"></i>
                    </div>
                    
                    
                    <h3 class="font-bold text-slate-800 text-lg mb-6 tracking-tight"><?php echo e($rpt['title']); ?></h3>
                    
                    
                    <div class="flex gap-2.5 justify-center">
                        <button onclick="triggerDownload('<?php echo e($rpt['type']); ?>', 'pdf')" class="px-5 py-2 border border-red-100 text-red-600 rounded-full text-[9px] font-black uppercase tracking-widest hover:bg-red-50 transition block">
                            PDF
                        </button>
                        <button onclick="triggerDownload('<?php echo e($rpt['type']); ?>', 'excel')" class="px-5 py-2 border border-green-100 text-green-600 rounded-full text-[9px] font-black uppercase tracking-widest hover:bg-green-50 transition block">
                            EXCEL
                        </button>
                    </div>
                </div>
                <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
            </div>
        </div> 
    </div>

    <?php $__env->startPush('scripts'); ?>
    <script>
        function triggerDownload(type, format) {
            const kelas = document.getElementById('filterKelas').value;
            const idTA = document.getElementById('filterTA').value;

            // Susun URL dengan query string
            const baseUrl = "<?php echo e(url('/bk/laporan/download')); ?>";
            const fullUrl = `${baseUrl}/${type}/${format}?kelas=${kelas}&id_tahun_ajaran=${idTA}`;

            // Tampilkan Loading Swal untuk UX
            Swal.fire({
                title: 'Menyiapkan File...',
                text: 'Laporan sedang diunduh',
                timer: 2000,
                showConfirmButton: false,
                didOpen: () => { Swal.showLoading() }
            });

            window.location.href = fullUrl;
        }

        const errorMessage = "<?php echo e(session('error')); ?>";
        if (errorMessage) {
            Swal.fire({
                title: 'Gagal Download',
                text: errorMessage,
                icon: 'error',
                confirmButtonColor: '#2563eb'
            });
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
<?php endif; ?><?php /**PATH C:\laragon\www\New foldersssss\ekesiswaan\resources\views/guru_bk/laporan.blade.php ENDPATH**/ ?>