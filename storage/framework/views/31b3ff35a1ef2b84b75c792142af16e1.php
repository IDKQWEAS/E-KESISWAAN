<?php if (isset($component)) { $__componentOriginal1517f3a8d67063730434eec5aab2d6f3 = $component; } ?>
<?php if (isset($attributes)) { $__attributesOriginal1517f3a8d67063730434eec5aab2d6f3 = $attributes; } ?>
<?php $component = Illuminate\View\AnonymousComponent::resolve(['view' => 'components.layout-app','data' => ['title' => 'Database Siswa','role' => 'admin']] + (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag ? $attributes->all() : [])); ?>
<?php $component->withName('layout-app'); ?>
<?php if ($component->shouldRender()): ?>
<?php $__env->startComponent($component->resolveView(), $component->data()); ?>
<?php if (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag): ?>
<?php $attributes = $attributes->except(\Illuminate\View\AnonymousComponent::ignoredParameterNames()); ?>
<?php endif; ?>
<?php $component->withAttributes(['title' => 'Database Siswa','role' => 'admin']); ?>

    <div class="flex-1 overflow-y-auto p-6 custom-scroll bg-[#f8fafc]">

        
        <div class="bg-white rounded-[16px] border border-slate-200 shadow-sm p-5 mb-5">

            <form action="<?php echo e(route('admin.siswa')); ?>"
                  method="GET"
                  id="filterForm"
                  class="flex flex-col xl:flex-row justify-between items-start xl:items-center gap-4">

                <div>

                    <h1 class="text-lg font-bold text-slate-800">
                        Database Siswa
                    </h1>

                    <p class="text-slate-500 text-xs mt-0.5">
                        Total :
                        <?php echo e($dataSiswa['total'] ?? 0); ?>

                        Siswa
                    </p>

                </div>

                <div class="flex flex-wrap items-center gap-3 w-full xl:w-auto">

                    
                    <div class="relative flex-1 xl:w-[240px]">

                        <i class="fa-solid fa-magnifying-glass absolute left-3 top-2.5 text-slate-400 text-xs"></i>

                        <input type="text"
                               id="liveSearchInput"
                               placeholder="Ketik Nama / NISN..."
                               class="w-full bg-slate-50 border border-transparent hover:border-slate-200 rounded-lg py-2 pl-9 pr-3 text-xs font-bold text-slate-700 placeholder:text-slate-400 outline-none focus:ring-2 focus:ring-blue-500/20 transition-all shadow-sm focus:shadow-md">

                    </div>

                    
                    <div class="relative group">

                        <select name="kelas"
                                onchange="document.getElementById('filterForm').submit()"
                                class="appearance-none bg-slate-50 border border-transparent hover:border-slate-200 rounded-lg py-2 pl-3 pr-8 text-xs font-bold text-slate-700 outline-none focus:ring-2 focus:ring-blue-500/20 cursor-pointer min-w-[120px] transition-all">

                            <option value="">Semua Kelas</option>

                            <?php $__currentLoopData = [7,8,9]; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $tingkat): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>

                                <?php $__currentLoopData = range('A','G'); $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $huruf): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>

                                    <?php
                                        $kls = $tingkat . $huruf;
                                    ?>

                                    <option value="<?php echo e($kls); ?>"
                                        <?php echo e(request('kelas') == $kls ? 'selected' : ''); ?>>

                                        Kelas <?php echo e($kls); ?>


                                    </option>

                                <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>

                            <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>

                        </select>

                        <i class="fa-solid fa-chevron-down absolute right-3 top-3 text-[10px] text-slate-400 pointer-events-none"></i>

                    </div>

                </div>

            </form>

        </div>

        
        
<div id="tableContainer">

    <div class="bg-white rounded-[16px] border border-slate-200 shadow-sm overflow-hidden">

        
        <div class="overflow-x-auto">

            <table class="w-full border-collapse table-fixed">

                <thead class="border-b border-slate-100 bg-white">

                    <tr>

                        
                        <th class="w-[60%] py-4 pl-6 pr-4 text-[10px] font-bold text-slate-400 uppercase tracking-widest text-left">
                            IDENTITAS SISWA
                        </th>

                        
                        <th class="w-[20%] py-4 px-4 text-[10px] font-bold text-slate-400 uppercase tracking-widest text-center">
                            KELAS
                        </th>

                        
                        <th class="w-[20%] py-4 px-4 text-[10px] font-bold text-slate-400 uppercase tracking-widest text-center">
                            GENDER
                        </th>

                    </tr>

                </thead>

                <tbody class="divide-y divide-slate-50">

                    <?php $__empty_1 = true; $__currentLoopData = $dataSiswa['data'] ?? []; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $siswa): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); $__empty_1 = false; ?>

                        <tr class="hover:bg-slate-50 transition">

                            
                            <td class="w-[60%] py-3 pl-6 pr-4">

                                <div class="flex items-center gap-3 overflow-hidden">

                                    <?php

                                        $genderColor =
                                            ($siswa['jenis_kelamin'] ?? '') == 'P'
                                            ? 'bg-pink-100 text-pink-600'
                                            : 'bg-[#dbeafe] text-[#2563eb]';

                                        $nama = $siswa['nama'] ?? 'X';

                                        $words = explode(' ', $nama);

                                        $initials =
                                            substr($words[0],0,1) .
                                            (isset($words[1]) ? substr($words[1],0,1) : '');

                                    ?>

                                    <div class="w-9 h-9 min-w-[36px] rounded-full <?php echo e($genderColor); ?> flex items-center justify-center font-bold text-[10px] uppercase">

                                        <?php echo e($initials); ?>


                                    </div>

                                    <div class="overflow-hidden">

                                        <h4 class="text-sm font-bold text-slate-800 uppercase truncate">

                                            <?php echo e($siswa['nama'] ?? '-'); ?>


                                        </h4>

                                        <p class="text-[10px] text-slate-400 truncate">

                                            NISN :
                                            <?php echo e($siswa['nisn'] ?? '-'); ?>


                                        </p>

                                    </div>

                                </div>

                            </td>

                            
                            <td class="w-[20%] py-3 px-4 text-center">

                                <span class="bg-slate-100 text-slate-600 border border-slate-200 px-2.5 py-1 rounded-md text-xs font-bold inline-block min-w-[50px]">

                                    <?php echo e($siswa['kelas'] ?? '-'); ?>


                                </span>

                            </td>

                            
                            <td class="w-[20%] py-3 px-4 text-center">

                                <?php if(($siswa['jenis_kelamin'] ?? '') == 'L'): ?>

                                    <span class="bg-[#eff6ff] text-[#2563eb] border border-blue-100 px-3 py-1 rounded-md text-[10px] font-bold inline-flex items-center justify-center gap-1 min-w-[100px]">

                                        <i class="fa-solid fa-mars text-[9px]"></i>

                                        Laki-laki

                                    </span>

                                <?php else: ?>

                                    <span class="bg-pink-50 text-pink-600 border border-pink-100 px-3 py-1 rounded-md text-[10px] font-bold inline-flex items-center justify-center gap-1 min-w-[100px]">

                                        <i class="fa-solid fa-venus text-[9px]"></i>

                                        Perempuan

                                    </span>

                                <?php endif; ?>

                            </td>

                        </tr>

                    <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); if ($__empty_1): ?>

                        <tr>

                            <td colspan="3"
                                class="py-10 text-center text-slate-400 text-xs italic">

                                Tidak ada data siswa

                            </td>

                        </tr>

                    <?php endif; ?>

                </tbody>

            </table>

        </div>

        
        <div class="flex flex-col md:flex-row justify-between items-center gap-3 p-4 border-t border-slate-100 bg-white">

            <p class="text-slate-500 text-[10px] font-bold uppercase">

                Halaman

                <?php echo e($dataSiswa['current_page'] ?? 1); ?>


                dari

                <?php echo e($dataSiswa['last_page'] ?? 1); ?>


            </p>

            <div class="flex items-center gap-2">

                
                <?php if(($dataSiswa['current_page'] ?? 1) > 1): ?>

                    <a href="<?php echo e(request()->fullUrlWithQuery([
                        'page' => ($dataSiswa['current_page'] ?? 1) - 1
                    ])); ?>"
                    class="pagination-link px-3 py-1.5 border border-slate-200 rounded-xl text-slate-600 text-xs font-bold hover:bg-slate-50 transition">

                        Prev

                    </a>

                <?php else: ?>

                    <span class="px-3 py-1.5 border border-slate-200 rounded-xl text-slate-300 text-xs font-bold">

                        Prev

                    </span>

                <?php endif; ?>

                
                <div class="w-9 h-9 bg-[#2563eb] text-white rounded-xl flex items-center justify-center text-xs font-bold shadow-md">

                    <?php echo e($dataSiswa['current_page'] ?? 1); ?>


                </div>

                
                <?php if(($dataSiswa['current_page'] ?? 1) < ($dataSiswa['last_page'] ?? 1)): ?>

                    <a href="<?php echo e(request()->fullUrlWithQuery([
                        'page' => ($dataSiswa['current_page'] ?? 1) + 1
                    ])); ?>"
                    class="pagination-link px-3 py-1.5 border border-slate-200 rounded-xl text-slate-600 text-xs font-bold hover:bg-slate-50 transition">

                        Next

                    </a>

                <?php else: ?>

                    <span class="px-3 py-1.5 border border-slate-200 rounded-xl text-slate-300 text-xs font-bold">

                        Next

                    </span>

                <?php endif; ?>

            </div>

        </div>

    </div>

</div>

    </div>

    
    <script>

    function initSearch() {

        const searchInput = document.getElementById('liveSearchInput');

        if (!searchInput) return;

        searchInput.addEventListener('keyup', function() {

            const keyword = this.value.toLowerCase();

            const rows = document.querySelectorAll('tbody tr');

            rows.forEach(row => {

                const text = row.innerText.toLowerCase();

                if (text.includes(keyword)) {

                    row.style.display = '';

                } else {

                    row.style.display = 'none';

                }

            });

        });

    }

    async function loadPagination(url) {

        try {

            const response = await fetch(url, {
                headers: {
                    'X-Requested-With': 'XMLHttpRequest'
                }
            });

            const data = await response.json();

            const parser = new DOMParser();

            const doc = parser.parseFromString(data.table, 'text/html');

            const newTable =
                doc.querySelector('#tableContainer').innerHTML;

            document.querySelector('#tableContainer').innerHTML = newTable;

            window.history.pushState({}, '', url);

            initSearch();

        } catch(err) {

            console.error(err);

        }

    }

    document.addEventListener('click', function(e) {

        const link = e.target.closest('.pagination-link');

        if (!link) return;

        e.preventDefault();

        loadPagination(link.href);

    });

    document.addEventListener('DOMContentLoaded', function() {

        initSearch();

    });

</script>

 <?php echo $__env->renderComponent(); ?>
<?php endif; ?>
<?php if (isset($__attributesOriginal1517f3a8d67063730434eec5aab2d6f3)): ?>
<?php $attributes = $__attributesOriginal1517f3a8d67063730434eec5aab2d6f3; ?>
<?php unset($__attributesOriginal1517f3a8d67063730434eec5aab2d6f3); ?>
<?php endif; ?>
<?php if (isset($__componentOriginal1517f3a8d67063730434eec5aab2d6f3)): ?>
<?php $component = $__componentOriginal1517f3a8d67063730434eec5aab2d6f3; ?>
<?php unset($__componentOriginal1517f3a8d67063730434eec5aab2d6f3); ?>
<?php endif; ?><?php /**PATH C:\laragon\www\New foldersssss\ekesiswaan\resources\views/admin/data_siswa.blade.php ENDPATH**/ ?>