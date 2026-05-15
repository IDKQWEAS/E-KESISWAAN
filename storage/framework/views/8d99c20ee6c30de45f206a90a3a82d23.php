<?php $attributes ??= new \Illuminate\View\ComponentAttributeBag;

$__newAttributes = [];
$__propNames = \Illuminate\View\ComponentAttributeBag::extractPropNames((['icon', 'active' => false]));

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

foreach (array_filter((['icon', 'active' => false]), 'is_string', ARRAY_FILTER_USE_KEY) as $__key => $__value) {
    $$__key = $$__key ?? $__value;
}

$__defined_vars = get_defined_vars();

foreach ($attributes->all() as $__key => $__value) {
    if (array_key_exists($__key, $__defined_vars)) unset($$__key);
}

unset($__defined_vars, $__key, $__value); ?>

<a <?php echo e($attributes); ?> class="<?php echo e($active ? 'bg-primary text-white shadow-md shadow-blue-500/20' : 'text-slate-400 hover:bg-navy-800 hover:text-white'); ?> flex items-center gap-3 px-4 py-2.5 rounded-xl transition-all font-medium text-[13.5px] cursor-pointer w-full">
    <i class="fa-solid <?php echo e($icon); ?> w-5 text-center text-[15px]"></i>
    <span class="flex-1 truncate"><?php echo e($slot); ?></span>
</a>
<?php /**PATH C:\laragon\www\New foldersssss\ekesiswaan\resources\views/components/nav-link.blade.php ENDPATH**/ ?>