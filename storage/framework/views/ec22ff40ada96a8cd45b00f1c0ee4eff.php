<?php if (isset($component)) { $__componentOriginal9ac128a9029c0e4701924bd2d73d7f54 = $component; } ?>
<?php if (isset($attributes)) { $__attributesOriginal9ac128a9029c0e4701924bd2d73d7f54 = $attributes; } ?>
<?php $component = App\View\Components\AppLayout::resolve([] + (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag ? $attributes->all() : [])); ?>
<?php $component->withName('app-layout'); ?>
<?php if ($component->shouldRender()): ?>
<?php $__env->startComponent($component->resolveView(), $component->data()); ?>
<?php if (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag): ?>
<?php $attributes = $attributes->except(\App\View\Components\AppLayout::ignoredParameterNames()); ?>
<?php endif; ?>
<?php $component->withAttributes([]); ?>
<?php \Livewire\Features\SupportCompiledWireKeys\SupportCompiledWireKeys::processComponentKey($component); ?>

     <?php $__env->slot('header', null, []); ?> 
     <?php $__env->endSlot(); ?>
    <div class="py-[15px] lg:py-[14px]">
        <div class="max-w-[1700px] mx-auto">
            <div
                class="relative overflow-hidden rounded-xl bg-gradient-to-br from-[#1E3A8A] to-[#4866BD] pt-3 pb-4 px-4 lg:pl-5 lg:py-4 text-white shadow-md mb-3">
                <div class="absolute right-0 top-0 opacity-10">
                    <i data-lucide="layout-dashboard" class="w-40 h-40"></i>
                </div>
                <div class="relative flex flex-col lg:flex-row lg:justify-between gap-2">
                    <div>
                        <h1 class="text-2xl font-bold text-white leading-tight">
                            Dashboard Auditi
                        </h1>
                        <p class="text-blue-100 max-w-xl text-sm lg:text-base leading-relaxed">
                            Pantau progres tindak lanjut temuan audit yang menjadi tanggung jawab Anda.
                        </p>
                    </div>
                    <div class="flex items-center">
                        <div
                            class="rounded-2xl bg-white/10 backdrop-blur-sm px-5 py-2 min-w-full lg:min-w-[300px] transition-all duration-300 shadow-lg hover:-translate-y-1 hover:shadow-xl">
                            <p class="text-xs text-blue-100 font-medium">
                                Progress Penyelesaian
                            </p>
                            <div class="mt-1 flex items-end gap-2">
                                <h3 class="text-3xl font-bold text-[#6EFF53]">
                                    <?php echo e($persentaseClosed); ?>%
                                </h3>
                                <span class="text-xs text-blue-100 mb-1">
                                    selesai
                                </span>
                            </div>
                            <div class="mt-2 h-2 bg-white/20 rounded-lg overflow-hidden">
                                <div class="h-full bg-white rounded-lg" style="width: <?php echo e($persentaseClosed); ?>%">
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <div class="grid grid-cols-2 lg:grid-cols-4 gap-3 mb-3">
                <div
                    class="group relative overflow-hidden rounded-2xl border border-slate-200 bg-white p-4 shadow-sm transition-all duration-300 hover:-translate-y-1 hover:shadow-xl">
                    <div class="absolute -top-5 -right-5 w-24 h-24 bg-[#1E3A8A]/30 rounded-full blur-3xl opacity-70">
                    </div>
                    <div class="relative">
                        <div class="flex items-start justify-between">
                            <div>
                                <p class="text-xs font-semibold uppercase text-slate-400">
                                    Total Audit
                                </p>
                                <h3 class="mt-2 text-3xl font-bold text-slate-800">
                                    <?php echo e($totalAudit); ?>

                                </h3>
                            </div>
                            <div
                                class="flex items-center justify-center w-12 h-12 rounded-2xl bg-[#1E3A8A]/5 text-[#1E3A8A]">
                                <i data-lucide="clipboard-check" class="w-6 h-6"></i>
                            </div>
                        </div>
                    </div>
                </div>
                <div
                    class="group relative overflow-hidden rounded-2xl border border-slate-200 bg-white p-4 shadow-sm transition-all duration-300 hover:-translate-y-1 hover:shadow-xl">
                    <div class="absolute -top-5 -right-5 w-24 h-24 bg-[#EA7A17]/40 rounded-full blur-3xl opacity-60">
                    </div>
                    <div class="relative">
                        <div class="flex items-start justify-between">
                            <div>
                                <p class="text-xs font-semibold uppercase text-slate-400">
                                    Total Temuan
                                </p>
                                <h3 class="mt-2 text-3xl font-bold text-slate-800">
                                    <?php echo e($total); ?>

                                </h3>
                            </div>
                            <div
                                class="flex items-center justify-center w-12 h-12 rounded-2xl bg-[#E77817]/5 text-[#E77817]">
                                <i data-lucide="file-warning" class="w-6 h-6"></i>
                            </div>
                        </div>
                    </div>
                </div>
                <div
                    class="group relative overflow-hidden rounded-2xl border border-slate-200 bg-white p-4 shadow-sm transition-all duration-300 hover:-translate-y-1 hover:shadow-xl">
                    <div class="absolute -top-5 -right-5 w-24 h-24 bg-red-200 rounded-full blur-3xl opacity-70">
                    </div>
                    <div class="relative">
                        <div class="flex items-start justify-between">
                            <div>
                                <p class="text-xs font-semibold uppercase text-slate-400">
                                    Temuan Open
                                </p>
                                <h3 class="mt-2 text-3xl font-bold text-slate-800">
                                    <?php echo e($open); ?>

                                </h3>
                            </div>
                            <div class="flex items-center justify-center w-12 h-12 rounded-2xl bg-red-50 text-red-500">
                                <i data-lucide="alert-circle" class="w-6 h-6"></i>
                            </div>
                        </div>
                    </div>
                </div>
                <div
                    class="group relative overflow-hidden rounded-2xl border border-slate-200 bg-white p-4 shadow-sm transition-all duration-300 hover:-translate-y-1 hover:shadow-xl">
                    <div class="absolute -top-5 -right-5 w-24 h-24 bg-green-100 rounded-full blur-3xl opacity-70">
                    </div>
                    <div class="relative">
                        <div class="flex items-start justify-between">
                            <div>
                                <p class="text-xs font-semibold uppercase text-slate-400">
                                    Temuan Closed
                                </p>
                                <h3 class="mt-2 text-3xl font-bold text-slate-800">
                                    <?php echo e($closed); ?>

                                </h3>
                            </div>
                            <div
                                class="flex items-center justify-center w-12 h-12 rounded-2xl bg-green-50 text-green-500">
                                <i data-lucide="check-circle" class="w-6 h-6"></i>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <div class="grid grid-cols-1 lg:grid-cols-3 gap-3">
                <div class="lg:col-span-2 rounded-2xl border border-slate-200 bg-white py-3.5 px-3.5 shadow-sm">
                    
                    <div class="flex items-start justify-between mb-3">
                        <div>
                            <h3 class="text-base font-bold text-slate-800">
                                Temuan Terbaru
                            </h3>
                            <p class="text-sm text-slate-500">
                                Temuan yang menjadi tanggung jawab Anda
                            </p>
                        </div>
                        <div
                            class="shrink-0 flex items-center justify-center w-8 h-8 rounded-lg bg-[#EA7A17]/10 text-[#EA7A17]">
                            <i data-lucide="activity" class="w-4 h-4"></i>
                        </div>
                    </div>
                    
                    <div class="space-y-2">
                        <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php \Livewire\Features\SupportCompiledWireKeys\SupportCompiledWireKeys::openLoop(); ?><?php endif; ?><?php $__empty_1 = true; $__currentLoopData = $temuanTerbaru; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $temuan): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); $__empty_1 = false; ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><?php \Livewire\Features\SupportCompiledWireKeys\SupportCompiledWireKeys::startLoopIteration(); ?><?php endif; ?>
                            <a href="<?php echo e(route('temuan.index', ['audit_id' => $temuan->audit_id, 'temuan' => $temuan->id])); ?>#temuan-<?php echo e($temuan->id); ?>"
                                class="group flex items-start justify-between rounded-2xl border border-slate-100 bg-slate-50/50 p-3 transition-all duration-300 hover:-translate-y-1 hover:border-slate-200 hover:bg-white hover:shadow-lg">
                                
                                <div class="flex items-start gap-3">
                                    
                                    <div
                                        class="shrink-0 flex items-center justify-center w-10 h-10 rounded-xl <?php echo e($temuan->status == 'OPEN' ? 'bg-red-50 text-red-500' : 'bg-green-50 text-green-500'); ?>">
                                        <i data-lucide="<?php echo e($temuan->status == 'OPEN' ? 'alert-circle' : 'check-circle-2'); ?>"
                                            class="w-5 h-5">
                                        </i>
                                    </div>
                                    
                                    <div class="min-w-0 flex-1">
                                        <div class="flex flex-wrap items-center gap-3">
                                            <p class="text-sm font-bold text-slate-800">
                                                <?php echo e($temuan->kode_indikator); ?>

                                            </p>
                                            <span
                                                class="inline-flex items-center rounded-full px-2 py-1 text-[11px] font-semibold <?php echo e($temuan->status == 'OPEN' ? 'bg-red-100 text-red-600' : 'bg-green-100 text-green-600'); ?>">
                                                <?php echo e($temuan->status); ?>

                                            </span>
                                        </div>
                                        <p class="mt-1 text-sm leading-relaxed text-slate-500">
                                            <?php echo e(Str::limit($temuan->temuan, 70)); ?>

                                        </p>
                                        <div class="mt-1 flex flex-wrap items-center gap-3 text-xs text-slate-400">
                                            <div class="flex items-center gap-1">
                                                <i data-lucide="building-2" class="w-3 h-3"></i>
                                                <?php echo e($temuan->audit->unit->nama ?? '-'); ?>

                                            </div>
                                            <div class="flex items-center gap-1">
                                                <i data-lucide="clock-3" class="w-3 h-3"></i>
                                                <?php echo e($temuan->created_at->diffForHumans()); ?>

                                            </div>
                                        </div>
                                    </div>
                                </div>
                                
                                <div
                                    class="opacity-0 transition-all duration-300 group-hover:translate-x-1 group-hover:opacity-100">
                                    <i data-lucide="arrow-up-right" class="w-4 h-4 text-slate-300"></i>
                                </div>
                            </a>
                        <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><?php \Livewire\Features\SupportCompiledWireKeys\SupportCompiledWireKeys::endLoop(); ?><?php endif; ?><?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); if ($__empty_1): ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><?php \Livewire\Features\SupportCompiledWireKeys\SupportCompiledWireKeys::closeLoop(); ?><?php endif; ?>
                            <div class="rounded-2xl border border-dashed border-slate-200 p-10 text-center">
                                <div class="flex flex-col items-center justify-center">
                                    <div
                                        class="flex items-center justify-center w-14 h-14 rounded-2xl bg-slate-100 text-slate-400">
                                        <i data-lucide="inbox" class="w-6 h-6"></i>
                                    </div>
                                    <p class="mt-4 text-sm font-medium text-slate-500">
                                        Belum ada temuan terbaru
                                    </p>
                                    <p class="mt-1 text-xs text-slate-400">
                                        Temuan yang menjadi tanggung jawab Anda akan muncul di sini.
                                    </p>
                                </div>
                            </div>
                        <?php endif; ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php endif; ?>
                    </div>
                </div>
                <div class="grid grid-rows-2">
                    <div class="rounded-2xl border border-slate-200 bg-white py-3.5 shadow-sm mb-3">
                        <div class="flex items-start justify-between mb-3 px-3.5">
                            <div>
                                <h3 class="text-base font-bold text-slate-800">
                                    Progress per Audit
                                </h3>
                                <p class="text-sm text-slate-500">
                                    Status penyelesaian tindak lanjut pada setiap audit
                                </p>
                            </div>
                            <div
                                class="shrink-0 flex items-center justify-center w-8 h-8 rounded-lg bg-[#1E3A8A]/10 text-[#1E3A8A]">
                                <i data-lucide="bar-chart-3" class="w-4 h-4"></i>
                            </div>
                        </div>
                        <div class="space-y-4 max-h-[400px] lg:max-h-[189px] overflow-y-auto pl-1 pr-1 custom-scroll">
                            <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php \Livewire\Features\SupportCompiledWireKeys\SupportCompiledWireKeys::openLoop(); ?><?php endif; ?><?php $__currentLoopData = $progressAudit; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $item): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><?php \Livewire\Features\SupportCompiledWireKeys\SupportCompiledWireKeys::startLoopIteration(); ?><?php endif; ?>
                                <div class="px-3">
                                    <div class="flex justify-between mb-1">
                                        <span class="text-sm font-medium text-slate-700">
                                            <?php echo e($item['nama_audit']); ?>

                                        </span>
                                        <span class="text-sm font-semibold text-slate-500">
                                            <?php echo e($item['progress']); ?>%
                                        </span>
                                    </div>
                                    <div class="h-1 overflow-hidden rounded-full bg-slate-100">
                                        <div class="h-full rounded-full bg-[#1E3A8A]"
                                            style="width: <?php echo e($item['progress']); ?>%">
                                        </div>
                                    </div>
                                    <p class="mt-1 text-xs text-slate-400">
                                        <?php echo e($item['closed']); ?> / <?php echo e($item['total']); ?>

                                        temuan selesai
                                    </p>
                                </div>
                            <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><?php \Livewire\Features\SupportCompiledWireKeys\SupportCompiledWireKeys::endLoop(); ?><?php endif; ?><?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php \Livewire\Features\SupportCompiledWireKeys\SupportCompiledWireKeys::closeLoop(); ?><?php endif; ?>
                        </div>
                    </div>
                    <div class="rounded-2xl border border-slate-200 bg-white py-3 shadow-sm">
                        <div class="flex items-start justify-between mb-3 px-3">
                            <div>
                                <h3 class="text-base font-bold text-slate-800">
                                    Audit Perlu Perhatian
                                </h3>
                                <p class="text-sm text-slate-500">
                                    Audit dengan progress penyelesaian terendah
                                </p>
                            </div>
                            <div
                                class="shrink-0 flex items-center justify-center w-10 h-10 rounded-xl bg-red-50 text-red-500">
                                <i data-lucide="alert-triangle" class="w-5 h-5"></i>
                            </div>
                        </div>
                        <div class="space-y-2 max-h-[400px] lg:max-h-[189px] overflow-y-auto pl-3 pr-2 custom-scroll">
                            <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php \Livewire\Features\SupportCompiledWireKeys\SupportCompiledWireKeys::openLoop(); ?><?php endif; ?><?php $__empty_1 = true; $__currentLoopData = $auditPerhatian; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $audit): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); $__empty_1 = false; ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><?php \Livewire\Features\SupportCompiledWireKeys\SupportCompiledWireKeys::startLoopIteration(); ?><?php endif; ?>
                                <?php
                                    $progressColor =
                                        $audit['progress'] >= 80 ? 'bg-green-500' :
                                        ($audit['progress'] >= 50 ? 'bg-amber-500' :
                                            'bg-red-500');
                                ?>
                                <a href="<?php echo e(route('temuan.index', ['audit_id' => $audit['audit_id']])); ?>"
                                    class="group block rounded-xl border border-slate-100 bg-slate-50 py-1.5 px-3 transition-all duration-300 hover:-translate-y-1 hover:border-slate-200 hover:bg-white hover:shadow-lg">
                                    <div class="flex items-center justify-between">
                                        <h4 class="text-sm font-medium text-slate-700">
                                            <?php echo e($audit['nama_audit']); ?>

                                        </h4>
                                        <div class="flex items-center">
                                            <span
                                                class="rounded-full px-2 py-1 text-xs font-semibold <?php echo e($audit['progress'] == 0 ? 'bg-red-50 text-red-600' : 'bg-slate-100 text-slate-600'); ?>">
                                                <?php echo e($audit['progress']); ?>%
                                            </span>
                                            <i data-lucide="arrow-up-right"
                                                class="w-4 h-4 text-slate-300 opacity-0 transition-all duration-300 group-hover:translate-x-1 group-hover:-translate-y-1 group-hover:opacity-100">
                                            </i>
                                        </div>
                                    </div>
                                    <div class="mt-1 h-1 rounded-full bg-slate-200 overflow-hidden">
                                        <div class="h-full rounded-full <?php echo e($progressColor); ?>"
                                            style="width: <?php echo e($audit['progress']); ?>%">
                                        </div>
                                    </div>
                                    <p class="mt-2 text-xs text-slate-500">
                                        <?php echo e($audit['closed']); ?>/<?php echo e($audit['total']); ?>

                                        temuan selesai
                                    </p>
                                </a>
                            <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><?php \Livewire\Features\SupportCompiledWireKeys\SupportCompiledWireKeys::endLoop(); ?><?php endif; ?><?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); if ($__empty_1): ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><?php \Livewire\Features\SupportCompiledWireKeys\SupportCompiledWireKeys::closeLoop(); ?><?php endif; ?>
                                <div class="text-center py-6 text-slate-400 text-sm">
                                    Tidak ada data audit.
                                </div>
                            <?php endif; ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php endif; ?>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
 <?php echo $__env->renderComponent(); ?>
<?php endif; ?>
<?php if (isset($__attributesOriginal9ac128a9029c0e4701924bd2d73d7f54)): ?>
<?php $attributes = $__attributesOriginal9ac128a9029c0e4701924bd2d73d7f54; ?>
<?php unset($__attributesOriginal9ac128a9029c0e4701924bd2d73d7f54); ?>
<?php endif; ?>
<?php if (isset($__componentOriginal9ac128a9029c0e4701924bd2d73d7f54)): ?>
<?php $component = $__componentOriginal9ac128a9029c0e4701924bd2d73d7f54; ?>
<?php unset($__componentOriginal9ac128a9029c0e4701924bd2d73d7f54); ?>
<?php endif; ?><?php /**PATH C:\laragon\www\simami\resources\views/auditi/dashboard.blade.php ENDPATH**/ ?>