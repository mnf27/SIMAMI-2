<!DOCTYPE html>
<html lang="<?php echo e(str_replace('_', '-', app()->getLocale())); ?>" class="overscroll-none">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="<?php echo e(csrf_token()); ?>">
    <title><?php echo e(config('app.name', 'Laravel')); ?></title>

    <!-- Fonts -->
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link rel="preconnect" href="https://fonts.bunny.net">
    <link href="https://fonts.bunny.net/css?family=figtree:400,500,600&display=swap" rel="stylesheet" />
    <link href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@400;500;600;700;800&display=swap"
        rel="stylesheet">

    <!-- Scripts -->
    <?php echo app('Illuminate\Foundation\Vite')(['resources/css/app.css', 'resources/js/app.js']); ?>
</head>

<body
    class="font-sans antialiased bg-[#88A4F4]/50 lg:bg-gradient-to-br lg:from-[#1E3A8A] lg:to-[#080F24] overscroll-none overflow-y-hidden overflow-x-hidden">
    <div class="min-h-screen grid lg:grid-cols-2">
        
        <div class="hidden lg:flex relative text-white">
            <div class="absolute -bottom-24 -left-24 h-80 w-80 rounded-full bg-white/5 blur-3xl">
            </div>
            
            <div class="absolute top-20 right-20 h-56 w-56 rounded-full bg-white/5 blur-2xl">
            </div>
            
            <div class="absolute bottom-20 right-1/3 h-40 w-40 rounded-full bg-white/5 blur-2xl">
            </div>
            <div class="absolute inset-0 opacity-[0.04]"
                style="background-image: linear-gradient(white 1px, transparent 1px), linear-gradient(90deg, white 1px, transparent 1px); background-size: 40px 40px;">
            </div>
            <div class="relative flex flex-col justify-start pt-8 px-10">
                <div class="flex items-center gap-4 mb-8">
                    <?php if (isset($component)) { $__componentOriginal26b7abff97924cc1584c125f1dde3cef = $component; } ?>
<?php if (isset($attributes)) { $__attributesOriginal26b7abff97924cc1584c125f1dde3cef = $attributes; } ?>
<?php $component = Illuminate\View\AnonymousComponent::resolve(['view' => 'components.login-logo','data' => ['class' => 'w-15 h-15']] + (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag ? $attributes->all() : [])); ?>
<?php $component->withName('login-logo'); ?>
<?php if ($component->shouldRender()): ?>
<?php $__env->startComponent($component->resolveView(), $component->data()); ?>
<?php if (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag): ?>
<?php $attributes = $attributes->except(\Illuminate\View\AnonymousComponent::ignoredParameterNames()); ?>
<?php endif; ?>
<?php $component->withAttributes(['class' => 'w-15 h-15']); ?>
<?php \Livewire\Features\SupportCompiledWireKeys\SupportCompiledWireKeys::processComponentKey($component); ?>

<?php echo $__env->renderComponent(); ?>
<?php endif; ?>
<?php if (isset($__attributesOriginal26b7abff97924cc1584c125f1dde3cef)): ?>
<?php $attributes = $__attributesOriginal26b7abff97924cc1584c125f1dde3cef; ?>
<?php unset($__attributesOriginal26b7abff97924cc1584c125f1dde3cef); ?>
<?php endif; ?>
<?php if (isset($__componentOriginal26b7abff97924cc1584c125f1dde3cef)): ?>
<?php $component = $__componentOriginal26b7abff97924cc1584c125f1dde3cef; ?>
<?php unset($__componentOriginal26b7abff97924cc1584c125f1dde3cef); ?>
<?php endif; ?>
                </div>
                <div class="mt-10">
                    <h2 class="text-5xl font-bold leading-tight max-w-xl">
                        Digitalisasi Sistem Informasi Audit Mutu Internal
                    </h2>
                    <p class="mt-4 text-blue-100 max-w-lg text-lg leading-relaxed">
                        Kelola proses audit, temuan, tindak lanjut, serta monitoring
                        mutu Program Studi dan Laboratorium secara terintegrasi.
                    </p>
                </div>
                
                <div class="grid grid-cols-3 gap-4 mt-16 max-w-xl">
                    <div class="rounded-2xl bg-white/10 backdrop-blur-sm p-4 shadow-lg">
                        <i data-lucide="clipboard-check" class="w-5 h-5 mb-3 text-white"></i>
                        <h3 class="font-bold text-white">
                            Audit
                        </h3>
                        <p class="text-sm text-blue-100">
                            Manajemen audit mutu internal
                        </p>
                    </div>
                    <div class="rounded-2xl bg-white/10 backdrop-blur-sm p-4 shadow-lg">
                        <i data-lucide="file-warning" class="w-5 h-5 mb-3 text-white"></i>
                        <h3 class="font-bold text-white">
                            Temuan
                        </h3>
                        <p class="text-sm text-blue-100">
                            Monitoring hasil audit
                        </p>
                    </div>
                    <div class="rounded-2xl bg-white/10 backdrop-blur-sm p-4 shadow-lg">
                        <i data-lucide="check-check" class="w-5 h-5 mb-3 text-white"></i>
                        <h3 class="font-bold text-white">
                            Tindak Lanjut
                        </h3>
                        <p class="text-sm text-blue-100">
                            Penyelesaian temuan audit
                        </p>
                    </div>
                </div>
            </div>
        </div>
        
        <div class="flex items-center justify-center py-4 px-4">
            <div class="w-full max-w-xl">
                
                <div class="lg:hidden flex justify-center mb-3">
                    <?php if (isset($component)) { $__componentOriginal20f1b15b7fdf25014cb8be985cd1919a = $component; } ?>
<?php if (isset($attributes)) { $__attributesOriginal20f1b15b7fdf25014cb8be985cd1919a = $attributes; } ?>
<?php $component = Illuminate\View\AnonymousComponent::resolve(['view' => 'components.login-logo-mobile','data' => []] + (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag ? $attributes->all() : [])); ?>
<?php $component->withName('login-logo-mobile'); ?>
<?php if ($component->shouldRender()): ?>
<?php $__env->startComponent($component->resolveView(), $component->data()); ?>
<?php if (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag): ?>
<?php $attributes = $attributes->except(\Illuminate\View\AnonymousComponent::ignoredParameterNames()); ?>
<?php endif; ?>
<?php $component->withAttributes([]); ?>
<?php \Livewire\Features\SupportCompiledWireKeys\SupportCompiledWireKeys::processComponentKey($component); ?>

<?php echo $__env->renderComponent(); ?>
<?php endif; ?>
<?php if (isset($__attributesOriginal20f1b15b7fdf25014cb8be985cd1919a)): ?>
<?php $attributes = $__attributesOriginal20f1b15b7fdf25014cb8be985cd1919a; ?>
<?php unset($__attributesOriginal20f1b15b7fdf25014cb8be985cd1919a); ?>
<?php endif; ?>
<?php if (isset($__componentOriginal20f1b15b7fdf25014cb8be985cd1919a)): ?>
<?php $component = $__componentOriginal20f1b15b7fdf25014cb8be985cd1919a; ?>
<?php unset($__componentOriginal20f1b15b7fdf25014cb8be985cd1919a); ?>
<?php endif; ?>
                </div>
                <div class="rounded-2xl border border-slate-200 bg-white pt-4 pb-5 px-5 shadow-xl">
                    <?php echo e($slot); ?>

                </div>
            </div>
        </div>
    </div>
    <script src="https://unpkg.com/lucide@latest"></script>
    <script>
        lucide.createIcons();
    </script>
    <script>
        window.addEventListener('pageshow', function (event) {
            const navigation = performance.getEntriesByType('navigation')[0];
            
            if (event.persisted || navigation?.type === 'back_forward') {
                setTimeout(() => {
                    location.reload();
                }, 0);
            }
        });
    </script>
</body>

</html><?php /**PATH C:\laragon\www\simami\resources\views/layouts/guest.blade.php ENDPATH**/ ?>