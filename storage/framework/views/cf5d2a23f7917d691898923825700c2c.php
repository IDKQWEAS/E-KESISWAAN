<?php $attributes ??= new \Illuminate\View\ComponentAttributeBag;

$__newAttributes = [];
$__propNames = \Illuminate\View\ComponentAttributeBag::extractPropNames((['title', 'role', 'showAcademic' => true]));

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

foreach (array_filter((['title', 'role', 'showAcademic' => true]), 'is_string', ARRAY_FILTER_USE_KEY) as $__key => $__value) {
    $$__key = $$__key ?? $__value;
}

$__defined_vars = get_defined_vars();

foreach ($attributes->all() as $__key => $__value) {
    if (array_key_exists($__key, $__defined_vars)) unset($$__key);
}

unset($__defined_vars, $__key, $__value); ?>
<!doctype html>
<html lang="id">
<head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    <title><?php echo e($title ?? 'E-KESISWAAN'); ?></title>

    <script src="https://cdn.tailwindcss.com"></script>
    <link href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@400;500;600;700;800&display=swap" rel="stylesheet" />
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css" />
    <script src="https://cdn.jsdelivr.net/npm/apexcharts"></script>
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>

    <script>
        tailwind.config = {
            theme: {
                extend: {
                    fontFamily: { sans: ["Plus Jakarta Sans", "sans-serif"] },
                    colors: {
                        navy: { 900: "#0f172a", 800: "#1e293b", 700: "#334155" },
                        primary: { DEFAULT: "#2563eb", hover: "#1d4ed8", light: "#eff6ff" },
                        surface: "#f8fafc",
                    },
                },
            },
        };
    </script>
    <style>
        .custom-scroll::-webkit-scrollbar { width: 6px; height: 6px; }
        .custom-scroll::-webkit-scrollbar-track { background: transparent; }
        .custom-scroll::-webkit-scrollbar-thumb { background: #cbd5e1; border-radius: 10px; }
        .custom-scroll::-webkit-scrollbar-thumb:hover { background: #94a3b8; }

        .fade-in { animation: fadeIn 0.3s ease-out forwards; opacity: 0; transform: translateY(10px); }
        @keyframes fadeIn { to { opacity: 1; transform: translateY(0); } }

        /* FIX: Mencegah layar bergeser saat SweetAlert muncul */
        body.swal2-shown, body.swal2-shown > [aria-hidden="true"] { padding-right: 0 !important; }

        /* FIX: CSS khusus untuk mengatur toggle sidebar di desktop */
        @media (min-width: 1024px) {
            body.sidebar-closed #sidebar { transform: translateX(-100%) !important; }
            body.sidebar-closed #main-wrapper { padding-left: 0 !important; }
        }
    </style>
</head>

<body class="bg-surface text-slate-600 font-sans overflow-hidden">

    <div id="mobile-backdrop" onclick="toggleSidebar()" class="fixed inset-0 bg-navy-900/60 z-40 hidden lg:hidden backdrop-blur-sm transition-opacity duration-300 opacity-0"></div>

    
    <?php if (isset($component)) { $__componentOriginald31f0a1d6e85408eecaaa9471b609820 = $component; } ?>
<?php if (isset($attributes)) { $__attributesOriginald31f0a1d6e85408eecaaa9471b609820 = $attributes; } ?>
<?php $component = App\View\Components\Sidebar::resolve([] + (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag ? $attributes->all() : [])); ?>
<?php $component->withName('sidebar'); ?>
<?php if ($component->shouldRender()): ?>
<?php $__env->startComponent($component->resolveView(), $component->data()); ?>
<?php if (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag): ?>
<?php $attributes = $attributes->except(\App\View\Components\Sidebar::ignoredParameterNames()); ?>
<?php endif; ?>
<?php $component->withAttributes(['role' => \Illuminate\View\Compilers\BladeCompiler::sanitizeComponentAttribute($role ?? session('user_data.role', 'admin'))]); ?>
<?php echo $__env->renderComponent(); ?>
<?php endif; ?>
<?php if (isset($__attributesOriginald31f0a1d6e85408eecaaa9471b609820)): ?>
<?php $attributes = $__attributesOriginald31f0a1d6e85408eecaaa9471b609820; ?>
<?php unset($__attributesOriginald31f0a1d6e85408eecaaa9471b609820); ?>
<?php endif; ?>
<?php if (isset($__componentOriginald31f0a1d6e85408eecaaa9471b609820)): ?>
<?php $component = $__componentOriginald31f0a1d6e85408eecaaa9471b609820; ?>
<?php unset($__componentOriginald31f0a1d6e85408eecaaa9471b609820); ?>
<?php endif; ?>

    
    <div id="main-wrapper" class="relative flex flex-col h-screen transition-all duration-300 lg:pl-72 w-full">

        <header class="h-16 lg:h-20 flex-shrink-0 px-5 lg:px-8 flex items-center justify-between bg-white/90 backdrop-blur-md z-30 sticky top-0 border-b border-slate-200/80 shadow-sm">
            <div class="flex items-center gap-4 min-w-0">
                <button onclick="toggleSidebar()" class="p-2 rounded-xl hover:bg-slate-100 text-slate-600 focus:outline-none transition flex-shrink-0">
                    <i class="fa-solid fa-bars text-lg lg:text-xl"></i>
                </button>
                <h2 class="text-lg lg:text-xl font-bold text-slate-800 tracking-tight truncate"><?php echo e($title ?? 'Dashboard'); ?></h2>
            </div>

            <?php if($showAcademic): ?>
                <div class="flex items-center gap-3 flex-shrink-0 pl-4">
                    <span class="text-[10px] lg:text-xs font-bold text-slate-400 uppercase tracking-wider hidden md:block">T.A:</span>

                    <?php if(!empty($listTahunAjaran)): ?>
                        <form method="GET" action="<?php echo e(url()->current()); ?>" id="formTahunAjaran">
                            
                            <?php $__currentLoopData = request()->except(['id_tahun_ajaran', 'page']); $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $key => $val): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                <input type="hidden" name="<?php echo e($key); ?>" value="<?php echo e($val); ?>">
                            <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>

                            <select id="selectTahunAjaran" name="id_tahun_ajaran"
                                class="border border-slate-200 py-1.5 lg:py-2 px-3 rounded-xl text-xs lg:text-sm font-bold text-[#2563eb] bg-white outline-none cursor-pointer focus:ring-2 focus:ring-blue-500/20 transition shadow-sm max-w-[130px] lg:max-w-none">
                                <?php $__currentLoopData = $listTahunAjaran; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $ta): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                    <?php
                                        $isAktif  = ! empty($ta['is_aktif']) || ! empty($ta['aktif']) || ($ta['status'] ?? '') === 'aktif' || ($ta['is_active'] ?? false) === true;
                                        $label    = ($ta['tahun_ajaran'] ?? $ta['nama'] ?? 'Tahun ?') . ' ' . ucfirst($ta['semester'] ?? '') . ($isAktif ? ' (Aktif)' : '');
                                        $selected = request('id_tahun_ajaran') ? (string) request('id_tahun_ajaran') === (string) $ta['id'] : $isAktif;
                                    ?>
                                    <option value="<?php echo e($ta['id']); ?>" <?php echo e($selected ? 'selected' : ''); ?>><?php echo e($label); ?></option>
                                <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                            </select>
                        </form>
                    <?php else: ?>
                        <span class="border border-slate-200 py-1.5 px-4 rounded-xl text-xs font-bold text-[#2563eb] bg-white shadow-sm">
                            <?php echo e($tahunAjaranAktif['tahun_ajaran'] ?? '-'); ?>

                        </span>
                    <?php endif; ?>
                </div>
            <?php endif; ?>
        </header>

        <main class="flex-1 overflow-y-auto p-5 lg:p-8 custom-scroll bg-surface">
            <?php echo e($slot); ?>

        </main>

    </div>

    <script>
        function toggleSidebar() {
            const sidebar = document.getElementById("sidebar");
            const backdrop = document.getElementById("mobile-backdrop");

            // Mode HP
            if (window.innerWidth < 1024) {
                sidebar.classList.toggle("-translate-x-full");

                if (backdrop.classList.contains("hidden")) {
                    backdrop.classList.remove("hidden");
                    setTimeout(() => backdrop.classList.remove("opacity-0"), 10);
                } else {
                    backdrop.classList.add("opacity-0");
                    setTimeout(() => backdrop.classList.add("hidden"), 300);
                }
            }
            // Mode PC / Laptop
            else {
                document.body.classList.toggle("sidebar-closed");
                // Trigger resize agar chart Apex tidak gepeng
                setTimeout(() => window.dispatchEvent(new Event('resize')), 300);
            }
        }

        // Auto-submit dropdown Tahun Ajaran
        const selectTA = document.getElementById('selectTahunAjaran');
        if (selectTA) {
            selectTA.addEventListener('change', () => {
                document.getElementById('formTahunAjaran').submit();
            });
        }
    </script>

    <?php echo $__env->yieldPushContent('scripts'); ?>
</body>
</html>
<?php /**PATH C:\laragon\www\New foldersssss\ekesiswaan\resources\views/components/layout-app.blade.php ENDPATH**/ ?>