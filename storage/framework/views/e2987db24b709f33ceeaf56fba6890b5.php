<?php $attributes ??= new \Illuminate\View\ComponentAttributeBag;

$__newAttributes = [];
$__propNames = \Illuminate\View\ComponentAttributeBag::extractPropNames((['role']));

foreach ($attributes->all() as $__key => $__value) {
    if (in_array($__key, $__propNames)) {
        $$__key = $$__key ?? $__value;
    } else {
        $__newAttributes[$__key] = $__value;
    }
}

$attributes = new \Illuminate\View\ComponentAttributeBag($__newAttributes);

unset($__propNames);
unset($__newAttributes);

foreach (array_filter((['role']), 'is_string', ARRAY_FILTER_USE_KEY) as $__key => $__value) {
    $$__key = $$__key ?? $__value;
}

$__defined_vars = get_defined_vars();

foreach ($attributes->all() as $__key => $__value) {
    if (array_key_exists($__key, $__defined_vars)) unset($$__key);
}

unset($__defined_vars, $__key, $__value); ?>

<aside id="sidebar" class="w-72 bg-navy-900 text-white flex flex-col z-50 fixed inset-y-0 left-0 transform -translate-x-full lg:translate-x-0 transition-transform duration-300 shadow-2xl lg:shadow-none border-r border-navy-800">

    
    <div class="h-20 lg:h-24 flex items-center px-5 lg:px-6 border-b border-navy-800 bg-navy-900 justify-between flex-shrink-0">
        <div class="flex items-center gap-3.5">

            
            <div class="w-12 h-12 lg:w-14 lg:h-14 bg-white/5 rounded-xl flex items-center justify-center shadow-lg p-1.5 flex-shrink-0 border border-white/10">
    <img src="<?php echo e(asset('smp.png')); ?>" 
         alt="Logo SMPN 1 Polanharjo" 
         class="w-full h-full object-contain drop-shadow-lg transform transition hover:scale-105">
</div>

            <div class="flex flex-col justify-center mt-1">
                <h1 class="font-extrabold text-base lg:text-lg tracking-tight leading-none text-white">E-KESISWAAN</h1>
                <p class="text-[9px] lg:text-[10px] text-blue-400 font-bold tracking-widest uppercase mt-1">SMPN 1 POLANHARJO</p>
            </div>
        </div>

        
        <button onclick="toggleSidebar()" class="lg:hidden text-slate-400 hover:text-white p-2 focus:outline-none">
            <i class="fa-solid fa-xmark text-2xl"></i>
        </button>
    </div>

    <div class="px-5 pt-6 pb-3 flex-shrink-0">
        <a href="<?php echo e($role == 'kepala_sekolah' ? route('kepsek.profile') : ($role == 'guru_bk' ? route('bk.profile') : '#')); ?>"
           class="p-3 bg-navy-800/60 rounded-2xl border border-navy-700/50 flex items-center gap-3 cursor-pointer hover:bg-navy-800 transition group block">
            <div class="relative flex-shrink-0">
                <div class="w-10 h-10 rounded-full <?php echo e($role == 'kepala_sekolah' ? 'bg-indigo-600' : ($role == 'guru_bk' ? 'bg-blue-600' : 'bg-orange-500')); ?> flex items-center justify-center font-bold text-white shadow-lg border-2 border-navy-900 text-sm">
                    <?php echo e(strtoupper(substr($role, 0, 2))); ?>

                </div>
                <div class="absolute bottom-0 right-0 w-2.5 h-2.5 bg-green-500 border-2 border-navy-900 rounded-full"></div>
            </div>
            <div class="overflow-hidden text-ellipsis flex-1">
                <p class="text-[13px] font-bold text-white group-hover:text-indigo-200 transition truncate leading-tight">
                    <?php if($role == 'kepala_sekolah'): ?> Kepala Sekolah
                    <?php elseif($role == 'guru_bk'): ?> Guru BK
                    <?php elseif($role == 'absensi'): ?> Petugas Absen
                    <?php else: ?> Admin Operator
                    <?php endif; ?>
                </p>
                <p class="text-[10px] text-slate-400 uppercase tracking-wide mt-0.5">Edit Profil</p>
            </div>
        </a>
    </div>

    <nav class="flex-1 overflow-y-auto px-4 pt-2 pb-6 custom-scroll">

        <?php if($role == 'kepala_sekolah'): ?>
            <div class="mb-6">
                <p class="px-3 text-[10px] font-bold text-slate-500 uppercase tracking-widest mb-2.5">Executive Menu</p>
                <div class="flex flex-col gap-1.5">
                    <?php if (isset($component)) { $__componentOriginal5c5186fe0c5c5f30b7e4c343793be4df = $component; } ?>
<?php if (isset($attributes)) { $__attributesOriginal5c5186fe0c5c5f30b7e4c343793be4df = $attributes; } ?>
<?php $component = App\View\Components\NavLink::resolve([] + (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag ? $attributes->all() : [])); ?>
<?php $component->withName('nav-link'); ?>
<?php if ($component->shouldRender()): ?>
<?php $__env->startComponent($component->resolveView(), $component->data()); ?>
<?php if (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag): ?>
<?php $attributes = $attributes->except(\App\View\Components\NavLink::ignoredParameterNames()); ?>
<?php endif; ?>
<?php $component->withAttributes(['href' => ''.e(route('kepsek.dashboard')).'','icon' => 'fa-chart-line','active' => \Illuminate\View\Compilers\BladeCompiler::sanitizeComponentAttribute(request()->routeIs('kepsek.dashboard'))]); ?>Dashboard Statistik <?php echo $__env->renderComponent(); ?>
<?php endif; ?>
<?php if (isset($__attributesOriginal5c5186fe0c5c5f30b7e4c343793be4df)): ?>
<?php $attributes = $__attributesOriginal5c5186fe0c5c5f30b7e4c343793be4df; ?>
<?php unset($__attributesOriginal5c5186fe0c5c5f30b7e4c343793be4df); ?>
<?php endif; ?>
<?php if (isset($__componentOriginal5c5186fe0c5c5f30b7e4c343793be4df)): ?>
<?php $component = $__componentOriginal5c5186fe0c5c5f30b7e4c343793be4df; ?>
<?php unset($__componentOriginal5c5186fe0c5c5f30b7e4c343793be4df); ?>
<?php endif; ?>
                    <?php if (isset($component)) { $__componentOriginal5c5186fe0c5c5f30b7e4c343793be4df = $component; } ?>
<?php if (isset($attributes)) { $__attributesOriginal5c5186fe0c5c5f30b7e4c343793be4df = $attributes; } ?>
<?php $component = App\View\Components\NavLink::resolve([] + (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag ? $attributes->all() : [])); ?>
<?php $component->withName('nav-link'); ?>
<?php if ($component->shouldRender()): ?>
<?php $__env->startComponent($component->resolveView(), $component->data()); ?>
<?php if (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag): ?>
<?php $attributes = $attributes->except(\App\View\Components\NavLink::ignoredParameterNames()); ?>
<?php endif; ?>
<?php $component->withAttributes(['href' => ''.e(route('kepsek.disiplin')).'','icon' => 'fa-map-location-dot','active' => \Illuminate\View\Compilers\BladeCompiler::sanitizeComponentAttribute(request()->routeIs('kepsek.disiplin'))]); ?>Peta Kedisiplinan <?php echo $__env->renderComponent(); ?>
<?php endif; ?>
<?php if (isset($__attributesOriginal5c5186fe0c5c5f30b7e4c343793be4df)): ?>
<?php $attributes = $__attributesOriginal5c5186fe0c5c5f30b7e4c343793be4df; ?>
<?php unset($__attributesOriginal5c5186fe0c5c5f30b7e4c343793be4df); ?>
<?php endif; ?>
<?php if (isset($__componentOriginal5c5186fe0c5c5f30b7e4c343793be4df)): ?>
<?php $component = $__componentOriginal5c5186fe0c5c5f30b7e4c343793be4df; ?>
<?php unset($__componentOriginal5c5186fe0c5c5f30b7e4c343793be4df); ?>
<?php endif; ?>
                    <?php if (isset($component)) { $__componentOriginal5c5186fe0c5c5f30b7e4c343793be4df = $component; } ?>
<?php if (isset($attributes)) { $__attributesOriginal5c5186fe0c5c5f30b7e4c343793be4df = $attributes; } ?>
<?php $component = App\View\Components\NavLink::resolve([] + (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag ? $attributes->all() : [])); ?>
<?php $component->withName('nav-link'); ?>
<?php if ($component->shouldRender()): ?>
<?php $__env->startComponent($component->resolveView(), $component->data()); ?>
<?php if (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag): ?>
<?php $attributes = $attributes->except(\App\View\Components\NavLink::ignoredParameterNames()); ?>
<?php endif; ?>
<?php $component->withAttributes(['href' => ''.e(route('kepsek.prestasi')).'','icon' => 'fa-trophy','active' => \Illuminate\View\Compilers\BladeCompiler::sanitizeComponentAttribute(request()->routeIs('kepsek.prestasi'))]); ?>Monitoring Prestasi <?php echo $__env->renderComponent(); ?>
<?php endif; ?>
<?php if (isset($__attributesOriginal5c5186fe0c5c5f30b7e4c343793be4df)): ?>
<?php $attributes = $__attributesOriginal5c5186fe0c5c5f30b7e4c343793be4df; ?>
<?php unset($__attributesOriginal5c5186fe0c5c5f30b7e4c343793be4df); ?>
<?php endif; ?>
<?php if (isset($__componentOriginal5c5186fe0c5c5f30b7e4c343793be4df)): ?>
<?php $component = $__componentOriginal5c5186fe0c5c5f30b7e4c343793be4df; ?>
<?php unset($__componentOriginal5c5186fe0c5c5f30b7e4c343793be4df); ?>
<?php endif; ?>
                    <?php if (isset($component)) { $__componentOriginal5c5186fe0c5c5f30b7e4c343793be4df = $component; } ?>
<?php if (isset($attributes)) { $__attributesOriginal5c5186fe0c5c5f30b7e4c343793be4df = $attributes; } ?>
<?php $component = App\View\Components\NavLink::resolve([] + (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag ? $attributes->all() : [])); ?>
<?php $component->withName('nav-link'); ?>
<?php if ($component->shouldRender()): ?>
<?php $__env->startComponent($component->resolveView(), $component->data()); ?>
<?php if (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag): ?>
<?php $attributes = $attributes->except(\App\View\Components\NavLink::ignoredParameterNames()); ?>
<?php endif; ?>
<?php $component->withAttributes(['href' => ''.e(route('kepsek.absensi')).'','icon' => 'fa-calendar-check','active' => \Illuminate\View\Compilers\BladeCompiler::sanitizeComponentAttribute(request()->routeIs('kepsek.absensi'))]); ?>Monitoring Absensi <?php echo $__env->renderComponent(); ?>
<?php endif; ?>
<?php if (isset($__attributesOriginal5c5186fe0c5c5f30b7e4c343793be4df)): ?>
<?php $attributes = $__attributesOriginal5c5186fe0c5c5f30b7e4c343793be4df; ?>
<?php unset($__attributesOriginal5c5186fe0c5c5f30b7e4c343793be4df); ?>
<?php endif; ?>
<?php if (isset($__componentOriginal5c5186fe0c5c5f30b7e4c343793be4df)): ?>
<?php $component = $__componentOriginal5c5186fe0c5c5f30b7e4c343793be4df; ?>
<?php unset($__componentOriginal5c5186fe0c5c5f30b7e4c343793be4df); ?>
<?php endif; ?>
                    <?php if (isset($component)) { $__componentOriginal5c5186fe0c5c5f30b7e4c343793be4df = $component; } ?>
<?php if (isset($attributes)) { $__attributesOriginal5c5186fe0c5c5f30b7e4c343793be4df = $attributes; } ?>
<?php $component = App\View\Components\NavLink::resolve([] + (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag ? $attributes->all() : [])); ?>
<?php $component->withName('nav-link'); ?>
<?php if ($component->shouldRender()): ?>
<?php $__env->startComponent($component->resolveView(), $component->data()); ?>
<?php if (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag): ?>
<?php $attributes = $attributes->except(\App\View\Components\NavLink::ignoredParameterNames()); ?>
<?php endif; ?>
<?php $component->withAttributes(['href' => ''.e(route('kepsek.visit')).'','icon' => 'fa-house-user','active' => \Illuminate\View\Compilers\BladeCompiler::sanitizeComponentAttribute(request()->routeIs('kepsek.visit'))]); ?>Monitoring Home Visit <?php echo $__env->renderComponent(); ?>
<?php endif; ?>
<?php if (isset($__attributesOriginal5c5186fe0c5c5f30b7e4c343793be4df)): ?>
<?php $attributes = $__attributesOriginal5c5186fe0c5c5f30b7e4c343793be4df; ?>
<?php unset($__attributesOriginal5c5186fe0c5c5f30b7e4c343793be4df); ?>
<?php endif; ?>
<?php if (isset($__componentOriginal5c5186fe0c5c5f30b7e4c343793be4df)): ?>
<?php $component = $__componentOriginal5c5186fe0c5c5f30b7e4c343793be4df; ?>
<?php unset($__componentOriginal5c5186fe0c5c5f30b7e4c343793be4df); ?>
<?php endif; ?>
                </div>
            </div>

        <?php elseif($role == 'guru_bk'): ?>
            <div class="mb-6">
                <p class="px-3 text-[10px] font-bold text-slate-500 uppercase tracking-widest mb-2.5">Menu Utama BK</p>
                <div class="flex flex-col gap-1.5">
                    <?php if (isset($component)) { $__componentOriginal5c5186fe0c5c5f30b7e4c343793be4df = $component; } ?>
<?php if (isset($attributes)) { $__attributesOriginal5c5186fe0c5c5f30b7e4c343793be4df = $attributes; } ?>
<?php $component = App\View\Components\NavLink::resolve([] + (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag ? $attributes->all() : [])); ?>
<?php $component->withName('nav-link'); ?>
<?php if ($component->shouldRender()): ?>
<?php $__env->startComponent($component->resolveView(), $component->data()); ?>
<?php if (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag): ?>
<?php $attributes = $attributes->except(\App\View\Components\NavLink::ignoredParameterNames()); ?>
<?php endif; ?>
<?php $component->withAttributes(['href' => ''.e(route('bk.dashboard')).'','icon' => 'fa-chart-line','active' => \Illuminate\View\Compilers\BladeCompiler::sanitizeComponentAttribute(request()->routeIs('bk.dashboard'))]); ?>Dashboard & Analisis <?php echo $__env->renderComponent(); ?>
<?php endif; ?>
<?php if (isset($__attributesOriginal5c5186fe0c5c5f30b7e4c343793be4df)): ?>
<?php $attributes = $__attributesOriginal5c5186fe0c5c5f30b7e4c343793be4df; ?>
<?php unset($__attributesOriginal5c5186fe0c5c5f30b7e4c343793be4df); ?>
<?php endif; ?>
<?php if (isset($__componentOriginal5c5186fe0c5c5f30b7e4c343793be4df)): ?>
<?php $component = $__componentOriginal5c5186fe0c5c5f30b7e4c343793be4df; ?>
<?php unset($__componentOriginal5c5186fe0c5c5f30b7e4c343793be4df); ?>
<?php endif; ?>
                    <?php if (isset($component)) { $__componentOriginal5c5186fe0c5c5f30b7e4c343793be4df = $component; } ?>
<?php if (isset($attributes)) { $__attributesOriginal5c5186fe0c5c5f30b7e4c343793be4df = $attributes; } ?>
<?php $component = App\View\Components\NavLink::resolve([] + (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag ? $attributes->all() : [])); ?>
<?php $component->withName('nav-link'); ?>
<?php if ($component->shouldRender()): ?>
<?php $__env->startComponent($component->resolveView(), $component->data()); ?>
<?php if (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag): ?>
<?php $attributes = $attributes->except(\App\View\Components\NavLink::ignoredParameterNames()); ?>
<?php endif; ?>
<?php $component->withAttributes(['href' => ''.e(route('bk.pelanggaran')).'','icon' => 'fa-gavel','active' => \Illuminate\View\Compilers\BladeCompiler::sanitizeComponentAttribute(request()->routeIs('bk.pelanggaran'))]); ?>Input Pelanggaran <?php echo $__env->renderComponent(); ?>
<?php endif; ?>
<?php if (isset($__attributesOriginal5c5186fe0c5c5f30b7e4c343793be4df)): ?>
<?php $attributes = $__attributesOriginal5c5186fe0c5c5f30b7e4c343793be4df; ?>
<?php unset($__attributesOriginal5c5186fe0c5c5f30b7e4c343793be4df); ?>
<?php endif; ?>
<?php if (isset($__componentOriginal5c5186fe0c5c5f30b7e4c343793be4df)): ?>
<?php $component = $__componentOriginal5c5186fe0c5c5f30b7e4c343793be4df; ?>
<?php unset($__componentOriginal5c5186fe0c5c5f30b7e4c343793be4df); ?>
<?php endif; ?>
                    <?php if (isset($component)) { $__componentOriginal5c5186fe0c5c5f30b7e4c343793be4df = $component; } ?>
<?php if (isset($attributes)) { $__attributesOriginal5c5186fe0c5c5f30b7e4c343793be4df = $attributes; } ?>
<?php $component = App\View\Components\NavLink::resolve([] + (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag ? $attributes->all() : [])); ?>
<?php $component->withName('nav-link'); ?>
<?php if ($component->shouldRender()): ?>
<?php $__env->startComponent($component->resolveView(), $component->data()); ?>
<?php if (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag): ?>
<?php $attributes = $attributes->except(\App\View\Components\NavLink::ignoredParameterNames()); ?>
<?php endif; ?>
<?php $component->withAttributes(['href' => ''.e(route('bk.prestasi')).'','icon' => 'fa-trophy','active' => \Illuminate\View\Compilers\BladeCompiler::sanitizeComponentAttribute(request()->routeIs('bk.prestasi'))]); ?>Data Prestasi <?php echo $__env->renderComponent(); ?>
<?php endif; ?>
<?php if (isset($__attributesOriginal5c5186fe0c5c5f30b7e4c343793be4df)): ?>
<?php $attributes = $__attributesOriginal5c5186fe0c5c5f30b7e4c343793be4df; ?>
<?php unset($__attributesOriginal5c5186fe0c5c5f30b7e4c343793be4df); ?>
<?php endif; ?>
<?php if (isset($__componentOriginal5c5186fe0c5c5f30b7e4c343793be4df)): ?>
<?php $component = $__componentOriginal5c5186fe0c5c5f30b7e4c343793be4df; ?>
<?php unset($__componentOriginal5c5186fe0c5c5f30b7e4c343793be4df); ?>
<?php endif; ?>
                    <?php if (isset($component)) { $__componentOriginal5c5186fe0c5c5f30b7e4c343793be4df = $component; } ?>
<?php if (isset($attributes)) { $__attributesOriginal5c5186fe0c5c5f30b7e4c343793be4df = $attributes; } ?>
<?php $component = App\View\Components\NavLink::resolve([] + (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag ? $attributes->all() : [])); ?>
<?php $component->withName('nav-link'); ?>
<?php if ($component->shouldRender()): ?>
<?php $__env->startComponent($component->resolveView(), $component->data()); ?>
<?php if (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag): ?>
<?php $attributes = $attributes->except(\App\View\Components\NavLink::ignoredParameterNames()); ?>
<?php endif; ?>
<?php $component->withAttributes(['href' => ''.e(route('bk.laporan')).'','icon' => 'fa-file-lines','active' => \Illuminate\View\Compilers\BladeCompiler::sanitizeComponentAttribute(request()->routeIs('bk.laporan'))]); ?>Cetak Laporan <?php echo $__env->renderComponent(); ?>
<?php endif; ?>
<?php if (isset($__attributesOriginal5c5186fe0c5c5f30b7e4c343793be4df)): ?>
<?php $attributes = $__attributesOriginal5c5186fe0c5c5f30b7e4c343793be4df; ?>
<?php unset($__attributesOriginal5c5186fe0c5c5f30b7e4c343793be4df); ?>
<?php endif; ?>
<?php if (isset($__componentOriginal5c5186fe0c5c5f30b7e4c343793be4df)): ?>
<?php $component = $__componentOriginal5c5186fe0c5c5f30b7e4c343793be4df; ?>
<?php unset($__componentOriginal5c5186fe0c5c5f30b7e4c343793be4df); ?>
<?php endif; ?>

                    <?php if (isset($component)) { $__componentOriginal5c5186fe0c5c5f30b7e4c343793be4df = $component; } ?>
<?php if (isset($attributes)) { $__attributesOriginal5c5186fe0c5c5f30b7e4c343793be4df = $attributes; } ?>
<?php $component = App\View\Components\NavLink::resolve([] + (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag ? $attributes->all() : [])); ?>
<?php $component->withName('nav-link'); ?>
<?php if ($component->shouldRender()): ?>
<?php $__env->startComponent($component->resolveView(), $component->data()); ?>
<?php if (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag): ?>
<?php $attributes = $attributes->except(\App\View\Components\NavLink::ignoredParameterNames()); ?>
<?php endif; ?>
<?php $component->withAttributes(['href' => ''.e(route('bk.pemantauan')).'','icon' => 'fa-eye','active' => \Illuminate\View\Compilers\BladeCompiler::sanitizeComponentAttribute(request()->routeIs('bk.pemantauan'))]); ?>Pemantauan Siswa <?php echo $__env->renderComponent(); ?>
<?php endif; ?>
<?php if (isset($__attributesOriginal5c5186fe0c5c5f30b7e4c343793be4df)): ?>
<?php $attributes = $__attributesOriginal5c5186fe0c5c5f30b7e4c343793be4df; ?>
<?php unset($__attributesOriginal5c5186fe0c5c5f30b7e4c343793be4df); ?>
<?php endif; ?>
<?php if (isset($__componentOriginal5c5186fe0c5c5f30b7e4c343793be4df)): ?>
<?php $component = $__componentOriginal5c5186fe0c5c5f30b7e4c343793be4df; ?>
<?php unset($__componentOriginal5c5186fe0c5c5f30b7e4c343793be4df); ?>
<?php endif; ?>
                    <?php if (isset($component)) { $__componentOriginal5c5186fe0c5c5f30b7e4c343793be4df = $component; } ?>
<?php if (isset($attributes)) { $__attributesOriginal5c5186fe0c5c5f30b7e4c343793be4df = $attributes; } ?>
<?php $component = App\View\Components\NavLink::resolve([] + (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag ? $attributes->all() : [])); ?>
<?php $component->withName('nav-link'); ?>
<?php if ($component->shouldRender()): ?>
<?php $__env->startComponent($component->resolveView(), $component->data()); ?>
<?php if (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag): ?>
<?php $attributes = $attributes->except(\App\View\Components\NavLink::ignoredParameterNames()); ?>
<?php endif; ?>
<?php $component->withAttributes(['href' => ''.e(route('bk.biodata')).'','icon' => 'fa-address-card','active' => \Illuminate\View\Compilers\BladeCompiler::sanitizeComponentAttribute(request()->routeIs('bk.biodata'))]); ?>Biodata Lengkap <?php echo $__env->renderComponent(); ?>
<?php endif; ?>
<?php if (isset($__attributesOriginal5c5186fe0c5c5f30b7e4c343793be4df)): ?>
<?php $attributes = $__attributesOriginal5c5186fe0c5c5f30b7e4c343793be4df; ?>
<?php unset($__attributesOriginal5c5186fe0c5c5f30b7e4c343793be4df); ?>
<?php endif; ?>
<?php if (isset($__componentOriginal5c5186fe0c5c5f30b7e4c343793be4df)): ?>
<?php $component = $__componentOriginal5c5186fe0c5c5f30b7e4c343793be4df; ?>
<?php unset($__componentOriginal5c5186fe0c5c5f30b7e4c343793be4df); ?>
<?php endif; ?>
                </div>
            </div>

            <div class="mb-6">
                <p class="px-3 text-[10px] font-bold text-slate-500 uppercase tracking-widest mb-2.5">Layanan</p>
                <div class="flex flex-col gap-1.5">
                    <?php if (isset($component)) { $__componentOriginal5c5186fe0c5c5f30b7e4c343793be4df = $component; } ?>
<?php if (isset($attributes)) { $__attributesOriginal5c5186fe0c5c5f30b7e4c343793be4df = $attributes; } ?>
<?php $component = App\View\Components\NavLink::resolve([] + (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag ? $attributes->all() : [])); ?>
<?php $component->withName('nav-link'); ?>
<?php if ($component->shouldRender()): ?>
<?php $__env->startComponent($component->resolveView(), $component->data()); ?>
<?php if (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag): ?>
<?php $attributes = $attributes->except(\App\View\Components\NavLink::ignoredParameterNames()); ?>
<?php endif; ?>
<?php $component->withAttributes(['href' => ''.e(route('bk.visit')).'','icon' => 'fa-house-user','active' => \Illuminate\View\Compilers\BladeCompiler::sanitizeComponentAttribute(request()->routeIs('bk.visit'))]); ?>Data Home Visit <?php echo $__env->renderComponent(); ?>
<?php endif; ?>
<?php if (isset($__attributesOriginal5c5186fe0c5c5f30b7e4c343793be4df)): ?>
<?php $attributes = $__attributesOriginal5c5186fe0c5c5f30b7e4c343793be4df; ?>
<?php unset($__attributesOriginal5c5186fe0c5c5f30b7e4c343793be4df); ?>
<?php endif; ?>
<?php if (isset($__componentOriginal5c5186fe0c5c5f30b7e4c343793be4df)): ?>
<?php $component = $__componentOriginal5c5186fe0c5c5f30b7e4c343793be4df; ?>
<?php unset($__componentOriginal5c5186fe0c5c5f30b7e4c343793be4df); ?>
<?php endif; ?>
                    <?php if (isset($component)) { $__componentOriginal5c5186fe0c5c5f30b7e4c343793be4df = $component; } ?>
<?php if (isset($attributes)) { $__attributesOriginal5c5186fe0c5c5f30b7e4c343793be4df = $attributes; } ?>
<?php $component = App\View\Components\NavLink::resolve([] + (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag ? $attributes->all() : [])); ?>
<?php $component->withName('nav-link'); ?>
<?php if ($component->shouldRender()): ?>
<?php $__env->startComponent($component->resolveView(), $component->data()); ?>
<?php if (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag): ?>
<?php $attributes = $attributes->except(\App\View\Components\NavLink::ignoredParameterNames()); ?>
<?php endif; ?>
<?php $component->withAttributes(['href' => ''.e(route('bk.perizinan')).'','icon' => 'fa-clipboard-check','active' => \Illuminate\View\Compilers\BladeCompiler::sanitizeComponentAttribute(request()->routeIs('bk.perizinan'))]); ?>Approval Perizinan <?php echo $__env->renderComponent(); ?>
<?php endif; ?>
<?php if (isset($__attributesOriginal5c5186fe0c5c5f30b7e4c343793be4df)): ?>
<?php $attributes = $__attributesOriginal5c5186fe0c5c5f30b7e4c343793be4df; ?>
<?php unset($__attributesOriginal5c5186fe0c5c5f30b7e4c343793be4df); ?>
<?php endif; ?>
<?php if (isset($__componentOriginal5c5186fe0c5c5f30b7e4c343793be4df)): ?>
<?php $component = $__componentOriginal5c5186fe0c5c5f30b7e4c343793be4df; ?>
<?php unset($__componentOriginal5c5186fe0c5c5f30b7e4c343793be4df); ?>
<?php endif; ?>
                    <?php if (isset($component)) { $__componentOriginal5c5186fe0c5c5f30b7e4c343793be4df = $component; } ?>
<?php if (isset($attributes)) { $__attributesOriginal5c5186fe0c5c5f30b7e4c343793be4df = $attributes; } ?>
<?php $component = App\View\Components\NavLink::resolve([] + (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag ? $attributes->all() : [])); ?>
<?php $component->withName('nav-link'); ?>
<?php if ($component->shouldRender()): ?>
<?php $__env->startComponent($component->resolveView(), $component->data()); ?>
<?php if (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag): ?>
<?php $attributes = $attributes->except(\App\View\Components\NavLink::ignoredParameterNames()); ?>
<?php endif; ?>
<?php $component->withAttributes(['href' => ''.e(route('bk.rekap_kehadiran')).'','icon' => 'fa-calendar-days','active' => \Illuminate\View\Compilers\BladeCompiler::sanitizeComponentAttribute(request()->routeIs('bk.rekap'))]); ?>Rekap Kehadiran <?php echo $__env->renderComponent(); ?>
<?php endif; ?>
<?php if (isset($__attributesOriginal5c5186fe0c5c5f30b7e4c343793be4df)): ?>
<?php $attributes = $__attributesOriginal5c5186fe0c5c5f30b7e4c343793be4df; ?>
<?php unset($__attributesOriginal5c5186fe0c5c5f30b7e4c343793be4df); ?>
<?php endif; ?>
<?php if (isset($__componentOriginal5c5186fe0c5c5f30b7e4c343793be4df)): ?>
<?php $component = $__componentOriginal5c5186fe0c5c5f30b7e4c343793be4df; ?>
<?php unset($__componentOriginal5c5186fe0c5c5f30b7e4c343793be4df); ?>
<?php endif; ?>
                </div>
            </div>

        <?php elseif($role == 'absensi'): ?>
            <div class="mb-6">
                <p class="px-3 text-[10px] font-bold text-slate-500 uppercase tracking-widest mb-2.5">Menu Petugas</p>
                <div class="flex flex-col gap-1.5">
                    <?php if (isset($component)) { $__componentOriginal5c5186fe0c5c5f30b7e4c343793be4df = $component; } ?>
<?php if (isset($attributes)) { $__attributesOriginal5c5186fe0c5c5f30b7e4c343793be4df = $attributes; } ?>
<?php $component = App\View\Components\NavLink::resolve([] + (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag ? $attributes->all() : [])); ?>
<?php $component->withName('nav-link'); ?>
<?php if ($component->shouldRender()): ?>
<?php $__env->startComponent($component->resolveView(), $component->data()); ?>
<?php if (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag): ?>
<?php $attributes = $attributes->except(\App\View\Components\NavLink::ignoredParameterNames()); ?>
<?php endif; ?>
<?php $component->withAttributes(['href' => ''.e(route('absen.dashboard')).'','icon' => 'fa-chart-line','active' => \Illuminate\View\Compilers\BladeCompiler::sanitizeComponentAttribute(request()->routeIs('absen.dashboard'))]); ?>Dashboard <?php echo $__env->renderComponent(); ?>
<?php endif; ?>
<?php if (isset($__attributesOriginal5c5186fe0c5c5f30b7e4c343793be4df)): ?>
<?php $attributes = $__attributesOriginal5c5186fe0c5c5f30b7e4c343793be4df; ?>
<?php unset($__attributesOriginal5c5186fe0c5c5f30b7e4c343793be4df); ?>
<?php endif; ?>
<?php if (isset($__componentOriginal5c5186fe0c5c5f30b7e4c343793be4df)): ?>
<?php $component = $__componentOriginal5c5186fe0c5c5f30b7e4c343793be4df; ?>
<?php unset($__componentOriginal5c5186fe0c5c5f30b7e4c343793be4df); ?>
<?php endif; ?>
                    <?php if (isset($component)) { $__componentOriginal5c5186fe0c5c5f30b7e4c343793be4df = $component; } ?>
<?php if (isset($attributes)) { $__attributesOriginal5c5186fe0c5c5f30b7e4c343793be4df = $attributes; } ?>
<?php $component = App\View\Components\NavLink::resolve([] + (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag ? $attributes->all() : [])); ?>
<?php $component->withName('nav-link'); ?>
<?php if ($component->shouldRender()): ?>
<?php $__env->startComponent($component->resolveView(), $component->data()); ?>
<?php if (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag): ?>
<?php $attributes = $attributes->except(\App\View\Components\NavLink::ignoredParameterNames()); ?>
<?php endif; ?>
<?php $component->withAttributes(['href' => ''.e(route('absensi.index')).'','icon' => 'fa-calendar-check','active' => \Illuminate\View\Compilers\BladeCompiler::sanitizeComponentAttribute(request()->routeIs('absensi.index'))]); ?>Input Kehadiran <?php echo $__env->renderComponent(); ?>
<?php endif; ?>
<?php if (isset($__attributesOriginal5c5186fe0c5c5f30b7e4c343793be4df)): ?>
<?php $attributes = $__attributesOriginal5c5186fe0c5c5f30b7e4c343793be4df; ?>
<?php unset($__attributesOriginal5c5186fe0c5c5f30b7e4c343793be4df); ?>
<?php endif; ?>
<?php if (isset($__componentOriginal5c5186fe0c5c5f30b7e4c343793be4df)): ?>
<?php $component = $__componentOriginal5c5186fe0c5c5f30b7e4c343793be4df; ?>
<?php unset($__componentOriginal5c5186fe0c5c5f30b7e4c343793be4df); ?>
<?php endif; ?>
                </div>
            </div>

        <?php else: ?>
            <div class="mb-6">
                <p class="px-3 text-[10px] font-bold text-slate-500 uppercase tracking-widest mb-2.5">Menu Utama Admin</p>
                <div class="flex flex-col gap-1.5">
                    <?php if (isset($component)) { $__componentOriginal5c5186fe0c5c5f30b7e4c343793be4df = $component; } ?>
<?php if (isset($attributes)) { $__attributesOriginal5c5186fe0c5c5f30b7e4c343793be4df = $attributes; } ?>
<?php $component = App\View\Components\NavLink::resolve([] + (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag ? $attributes->all() : [])); ?>
<?php $component->withName('nav-link'); ?>
<?php if ($component->shouldRender()): ?>
<?php $__env->startComponent($component->resolveView(), $component->data()); ?>
<?php if (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag): ?>
<?php $attributes = $attributes->except(\App\View\Components\NavLink::ignoredParameterNames()); ?>
<?php endif; ?>
<?php $component->withAttributes(['href' => ''.e(route('admin.dashboard')).'','icon' => 'fa-chart-line','active' => \Illuminate\View\Compilers\BladeCompiler::sanitizeComponentAttribute(request()->routeIs('admin.dashboard'))]); ?>Dashboard <?php echo $__env->renderComponent(); ?>
<?php endif; ?>
<?php if (isset($__attributesOriginal5c5186fe0c5c5f30b7e4c343793be4df)): ?>
<?php $attributes = $__attributesOriginal5c5186fe0c5c5f30b7e4c343793be4df; ?>
<?php unset($__attributesOriginal5c5186fe0c5c5f30b7e4c343793be4df); ?>
<?php endif; ?>
<?php if (isset($__componentOriginal5c5186fe0c5c5f30b7e4c343793be4df)): ?>
<?php $component = $__componentOriginal5c5186fe0c5c5f30b7e4c343793be4df; ?>
<?php unset($__componentOriginal5c5186fe0c5c5f30b7e4c343793be4df); ?>
<?php endif; ?>
                    <?php if (isset($component)) { $__componentOriginal5c5186fe0c5c5f30b7e4c343793be4df = $component; } ?>
<?php if (isset($attributes)) { $__attributesOriginal5c5186fe0c5c5f30b7e4c343793be4df = $attributes; } ?>
<?php $component = App\View\Components\NavLink::resolve([] + (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag ? $attributes->all() : [])); ?>
<?php $component->withName('nav-link'); ?>
<?php if ($component->shouldRender()): ?>
<?php $__env->startComponent($component->resolveView(), $component->data()); ?>
<?php if (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag): ?>
<?php $attributes = $attributes->except(\App\View\Components\NavLink::ignoredParameterNames()); ?>
<?php endif; ?>
<?php $component->withAttributes(['href' => ''.e(route('admin.kehadiran')).'','icon' => 'fa-calendar-check','active' => \Illuminate\View\Compilers\BladeCompiler::sanitizeComponentAttribute(request()->routeIs('admin.kehadiran'))]); ?>Data Kehadiran <?php echo $__env->renderComponent(); ?>
<?php endif; ?>
<?php if (isset($__attributesOriginal5c5186fe0c5c5f30b7e4c343793be4df)): ?>
<?php $attributes = $__attributesOriginal5c5186fe0c5c5f30b7e4c343793be4df; ?>
<?php unset($__attributesOriginal5c5186fe0c5c5f30b7e4c343793be4df); ?>
<?php endif; ?>
<?php if (isset($__componentOriginal5c5186fe0c5c5f30b7e4c343793be4df)): ?>
<?php $component = $__componentOriginal5c5186fe0c5c5f30b7e4c343793be4df; ?>
<?php unset($__componentOriginal5c5186fe0c5c5f30b7e4c343793be4df); ?>
<?php endif; ?>
                    <?php if (isset($component)) { $__componentOriginal5c5186fe0c5c5f30b7e4c343793be4df = $component; } ?>
<?php if (isset($attributes)) { $__attributesOriginal5c5186fe0c5c5f30b7e4c343793be4df = $attributes; } ?>
<?php $component = App\View\Components\NavLink::resolve([] + (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag ? $attributes->all() : [])); ?>
<?php $component->withName('nav-link'); ?>
<?php if ($component->shouldRender()): ?>
<?php $__env->startComponent($component->resolveView(), $component->data()); ?>
<?php if (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag): ?>
<?php $attributes = $attributes->except(\App\View\Components\NavLink::ignoredParameterNames()); ?>
<?php endif; ?>
<?php $component->withAttributes(['href' => ''.e(route('admin.siswa')).'','icon' => 'fa-users','active' => \Illuminate\View\Compilers\BladeCompiler::sanitizeComponentAttribute(request()->routeIs('admin.siswa'))]); ?>Data Siswa <?php echo $__env->renderComponent(); ?>
<?php endif; ?>
<?php if (isset($__attributesOriginal5c5186fe0c5c5f30b7e4c343793be4df)): ?>
<?php $attributes = $__attributesOriginal5c5186fe0c5c5f30b7e4c343793be4df; ?>
<?php unset($__attributesOriginal5c5186fe0c5c5f30b7e4c343793be4df); ?>
<?php endif; ?>
<?php if (isset($__componentOriginal5c5186fe0c5c5f30b7e4c343793be4df)): ?>
<?php $component = $__componentOriginal5c5186fe0c5c5f30b7e4c343793be4df; ?>
<?php unset($__componentOriginal5c5186fe0c5c5f30b7e4c343793be4df); ?>
<?php endif; ?>                    
                    <?php if (isset($component)) { $__componentOriginal5c5186fe0c5c5f30b7e4c343793be4df = $component; } ?>
<?php if (isset($attributes)) { $__attributesOriginal5c5186fe0c5c5f30b7e4c343793be4df = $attributes; } ?>
<?php $component = App\View\Components\NavLink::resolve([] + (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag ? $attributes->all() : [])); ?>
<?php $component->withName('nav-link'); ?>
<?php if ($component->shouldRender()): ?>
<?php $__env->startComponent($component->resolveView(), $component->data()); ?>
<?php if (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag): ?>
<?php $attributes = $attributes->except(\App\View\Components\NavLink::ignoredParameterNames()); ?>
<?php endif; ?>
<?php $component->withAttributes(['href' => ''.e(route('admin.pelanggaran')).'','icon' => 'fa-gavel','active' => \Illuminate\View\Compilers\BladeCompiler::sanitizeComponentAttribute(request()->routeIs('admin.pelanggaran'))]); ?>Data Pelanggaran <?php echo $__env->renderComponent(); ?>
<?php endif; ?>
<?php if (isset($__attributesOriginal5c5186fe0c5c5f30b7e4c343793be4df)): ?>
<?php $attributes = $__attributesOriginal5c5186fe0c5c5f30b7e4c343793be4df; ?>
<?php unset($__attributesOriginal5c5186fe0c5c5f30b7e4c343793be4df); ?>
<?php endif; ?>
<?php if (isset($__componentOriginal5c5186fe0c5c5f30b7e4c343793be4df)): ?>
<?php $component = $__componentOriginal5c5186fe0c5c5f30b7e4c343793be4df; ?>
<?php unset($__componentOriginal5c5186fe0c5c5f30b7e4c343793be4df); ?>
<?php endif; ?>
                </div>
            </div>

            <div class="mb-6">
                <p class="px-3 text-[10px] font-bold text-slate-500 uppercase tracking-widest mb-2.5 border-t border-navy-800 pt-5">System</p>
                <div class="flex flex-col gap-1.5">
                    <?php if (isset($component)) { $__componentOriginal5c5186fe0c5c5f30b7e4c343793be4df = $component; } ?>
<?php if (isset($attributes)) { $__attributesOriginal5c5186fe0c5c5f30b7e4c343793be4df = $attributes; } ?>
<?php $component = App\View\Components\NavLink::resolve([] + (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag ? $attributes->all() : [])); ?>
<?php $component->withName('nav-link'); ?>
<?php if ($component->shouldRender()): ?>
<?php $__env->startComponent($component->resolveView(), $component->data()); ?>
<?php if (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag): ?>
<?php $attributes = $attributes->except(\App\View\Components\NavLink::ignoredParameterNames()); ?>
<?php endif; ?>
<?php $component->withAttributes(['href' => ''.e(route('admin.users')).'','icon' => 'fa-user-gear','active' => \Illuminate\View\Compilers\BladeCompiler::sanitizeComponentAttribute(request()->routeIs('admin.users'))]); ?>Manajemen User <?php echo $__env->renderComponent(); ?>
<?php endif; ?>
<?php if (isset($__attributesOriginal5c5186fe0c5c5f30b7e4c343793be4df)): ?>
<?php $attributes = $__attributesOriginal5c5186fe0c5c5f30b7e4c343793be4df; ?>
<?php unset($__attributesOriginal5c5186fe0c5c5f30b7e4c343793be4df); ?>
<?php endif; ?>
<?php if (isset($__componentOriginal5c5186fe0c5c5f30b7e4c343793be4df)): ?>
<?php $component = $__componentOriginal5c5186fe0c5c5f30b7e4c343793be4df; ?>
<?php unset($__componentOriginal5c5186fe0c5c5f30b7e4c343793be4df); ?>
<?php endif; ?>
                    <?php if (isset($component)) { $__componentOriginal5c5186fe0c5c5f30b7e4c343793be4df = $component; } ?>
<?php if (isset($attributes)) { $__attributesOriginal5c5186fe0c5c5f30b7e4c343793be4df = $attributes; } ?>
<?php $component = App\View\Components\NavLink::resolve([] + (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag ? $attributes->all() : [])); ?>
<?php $component->withName('nav-link'); ?>
<?php if ($component->shouldRender()): ?>
<?php $__env->startComponent($component->resolveView(), $component->data()); ?>
<?php if (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag): ?>
<?php $attributes = $attributes->except(\App\View\Components\NavLink::ignoredParameterNames()); ?>
<?php endif; ?>
<?php $component->withAttributes(['href' => ''.e(route('admin.pengaturan')).'','icon' => 'fa-gear','active' => \Illuminate\View\Compilers\BladeCompiler::sanitizeComponentAttribute(request()->routeIs('admin.pengaturan'))]); ?>Pengaturan <?php echo $__env->renderComponent(); ?>
<?php endif; ?>
<?php if (isset($__attributesOriginal5c5186fe0c5c5f30b7e4c343793be4df)): ?>
<?php $attributes = $__attributesOriginal5c5186fe0c5c5f30b7e4c343793be4df; ?>
<?php unset($__attributesOriginal5c5186fe0c5c5f30b7e4c343793be4df); ?>
<?php endif; ?>
<?php if (isset($__componentOriginal5c5186fe0c5c5f30b7e4c343793be4df)): ?>
<?php $component = $__componentOriginal5c5186fe0c5c5f30b7e4c343793be4df; ?>
<?php unset($__componentOriginal5c5186fe0c5c5f30b7e4c343793be4df); ?>
<?php endif; ?>
                </div>
            </div>
        <?php endif; ?>
    </nav>

    <div class="p-5 border-t border-navy-800 flex-shrink-0 bg-navy-900">
        <form action="<?php echo e(route('logout')); ?>" method="POST">
            <?php echo csrf_field(); ?>
            <button type="submit" class="w-full flex items-center justify-center gap-2 text-slate-400 hover:text-red-400 hover:bg-navy-800 py-3 rounded-xl transition-all text-xs font-bold uppercase tracking-wider group">
                <i class="fa-solid fa-power-off group-hover:animate-pulse"></i> Keluar Sistem
            </button>
        </form>
    </div>
</aside>
<?php /**PATH C:\laragon\www\New foldersssss\ekesiswaan\resources\views/components/sidebar.blade.php ENDPATH**/ ?>