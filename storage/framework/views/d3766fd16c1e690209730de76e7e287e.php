<?php if (isset($component)) { $__componentOriginal1517f3a8d67063730434eec5aab2d6f3 = $component; } ?>
<?php if (isset($attributes)) { $__attributesOriginal1517f3a8d67063730434eec5aab2d6f3 = $attributes; } ?>
<?php $component = Illuminate\View\AnonymousComponent::resolve(['view' => 'components.layout-app','data' => ['title' => 'Data Kehadiran','role' => 'admin']] + (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag ? $attributes->all() : [])); ?>
<?php $component->withName('layout-app'); ?>
<?php if ($component->shouldRender()): ?>
<?php $__env->startComponent($component->resolveView(), $component->data()); ?>
<?php if (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag): ?>
<?php $attributes = $attributes->except(\Illuminate\View\AnonymousComponent::ignoredParameterNames()); ?>
<?php endif; ?>
<?php $component->withAttributes(['title' => 'Data Kehadiran','role' => 'admin']); ?>
    
    <div class="flex-1 overflow-y-auto p-6 bg-slate-50 custom-scroll">
        
        <div class="bg-white rounded-2xl border border-slate-200 shadow-sm p-6">

            
            <div class="flex flex-col xl:flex-row justify-between items-start xl:items-center gap-5 mb-6">
                <div>
                    
                    <h1 class="text-xl font-bold text-slate-800 mb-1">Rekap Data Kehadiran</h1>
                    
                    <p class="text-slate-500 text-xs font-medium">Tahun Ajaran: <?php echo e($taAktif['tahun_ajaran'] ?? '-'); ?> (<?php echo e($taAktif['semester'] ?? '-'); ?>)</p>
                </div>

                <form action="<?php echo e(route('admin.kehadiran')); ?>" method="GET" class="flex flex-wrap items-center gap-2.5">
                 

                    
                    <input type="date" name="tanggal" value="<?php echo e(request('tanggal', now()->format('Y-m-d'))); ?>" onchange="this.form.submit()" class="bg-white border border-slate-200 rounded-lg py-2 px-3 text-xs font-bold text-slate-600 outline-none shadow-sm">

                    
                    <select name="kelas" onchange="this.form.submit()" class="bg-white border border-slate-200 rounded-lg py-2 px-3 text-xs font-bold text-slate-600 outline-none shadow-sm cursor-pointer">
                       <option value="">Semua Kelas</option>

                        <?php $__currentLoopData = [7,8,9]; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $tingkat): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                            <?php $__currentLoopData = range('A', 'G'); $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $huruf): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                <?php $kls = $tingkat . $huruf; ?>

                                <option value="<?php echo e($kls); ?>" <?php echo e(request('kelas') == $kls ? 'selected' : ''); ?>>
                                    Kelas <?php echo e($kls); ?>

                                </option>
                            <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                        <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                    </select>

                    
                    <select name="status" onchange="this.form.submit()" class="bg-white border border-slate-200 rounded-lg py-2 px-3 text-xs font-bold text-slate-600 outline-none shadow-sm cursor-pointer">
                        <option value="">Semua Status</option>
                        <option value="tepat_waktu" <?php echo e(request('status') == 'tepat_waktu' ? 'selected' : ''); ?>>Tepat Waktu</option>
                        <option value="terlambat" <?php echo e(request('status') == 'terlambat' ? 'selected' : ''); ?>>Terlambat</option>
                    </select>
                </form>
            </div>

            
            <div class="overflow-x-auto">
                <table class="w-full text-left">
                    <thead>
                        
                        <tr class="text-slate-400 text-[9px] font-black uppercase tracking-widest border-b border-slate-100">
                            <th class="pb-3 pl-3">No</th>
                            <th class="pb-3">Nama Siswa</th>
                            <th class="pb-3">Kelas</th>
                            <th class="pb-3">Jam Absen</th>
                            <th class="pb-3">Status</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-slate-50">
                        <?php $__empty_1 = true; $__currentLoopData = $dataKehadiran; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $index => $item): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); $__empty_1 = false; ?>
                        <tr class="hover:bg-slate-50 transition">
                            
                            <td class="py-3 pl-3 text-xs font-bold text-slate-400"><?php echo e($index + 1); ?></td>
                            
                            <td class="py-3 text-xs font-bold text-slate-700 uppercase">
                                <?php echo e($item['nama'] ?? '-'); ?>

                            </td>
                            
                            <td class="py-3">
                                
                                <span class="px-1.5 py-0.5 bg-slate-100 text-slate-600 rounded text-[8px] font-black uppercase tracking-wider">
                                    <?php echo e($item['kelas'] ?? '-'); ?>

                                </span>
                            </td>

                            <td class="py-3 text-xs font-bold text-slate-500 font-mono">
                                <?php echo e(isset($item['created_at']) ? \Carbon\Carbon::parse($item['created_at'])->timezone('Asia/Jakarta')->format('H:i') : '-'); ?>

                            </td>
                            <td class="py-3">
                                
                                <span class="px-2.5 py-1 rounded-full text-[9px] font-black uppercase tracking-wide <?php echo e(($item['status'] ?? '') == 'tepat waktu' ? 'bg-green-50 text-green-600 border border-green-100' : 'bg-red-50 text-red-600 border border-red-100'); ?>">
                                    <?php echo e($item['status'] ?? 'Terlambat'); ?>

                                </span>
                            </td>
                        </tr>
                        <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); if ($__empty_1): ?>
                        <tr>
                            <td colspan="5" class="py-16 text-center text-slate-400 italic text-[10px] uppercase font-bold tracking-widest opacity-50">
                                Data kehadiran tidak ditemukan.
                            </td>
                        </tr>
                        <?php endif; ?>
                    </tbody>
                </table>
            </div>
        </div>
    </div>
 <?php echo $__env->renderComponent(); ?>
<?php endif; ?>
<?php if (isset($__attributesOriginal1517f3a8d67063730434eec5aab2d6f3)): ?>
<?php $attributes = $__attributesOriginal1517f3a8d67063730434eec5aab2d6f3; ?>
<?php unset($__attributesOriginal1517f3a8d67063730434eec5aab2d6f3); ?>
<?php endif; ?>
<?php if (isset($__componentOriginal1517f3a8d67063730434eec5aab2d6f3)): ?>
<?php $component = $__componentOriginal1517f3a8d67063730434eec5aab2d6f3; ?>
<?php unset($__componentOriginal1517f3a8d67063730434eec5aab2d6f3); ?>
<?php endif; ?><?php /**PATH C:\laragon\www\New foldersssss\ekesiswaan\resources\views/admin/data_kehadiran.blade.php ENDPATH**/ ?>