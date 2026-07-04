<?php $attributes ??= new \Illuminate\View\ComponentAttributeBag;

$__newAttributes = [];
$__propNames = \Illuminate\View\ComponentAttributeBag::extractPropNames((['active']));

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

foreach (array_filter((['active']), 'is_string', ARRAY_FILTER_USE_KEY) as $__key => $__value) {
    $$__key = $$__key ?? $__value;
}

$__defined_vars = get_defined_vars();

foreach ($attributes->all() as $__key => $__value) {
    if (array_key_exists($__key, $__defined_vars)) unset($$__key);
}

unset($__defined_vars, $__key, $__value); ?>

<?php
    $classes = ($active ?? false)
        ? 'flex items-center w-full px-4 py-2.5 rounded-lg bg-[#152F79] text-white font-semibold'
        : 'flex items-center w-full px-4 py-2.5 rounded-lg text-slate-600 hover:bg-[#88A4F4]/20 hover:text-black transition duration-200';
?>

<a <?php echo e($attributes->merge(['class' => $classes])); ?>>
    <?php echo e($slot); ?>

</a><?php /**PATH C:\laragon\www\simami\resources\views/components/nav-link.blade.php ENDPATH**/ ?>