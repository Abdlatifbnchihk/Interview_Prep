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
    <div class="pt-20">
        <div class="pt-4 pb-8">
            <div class="flex items-center justify-between mb-6">
                <div>
                    <h1 class="text-2xl font-bold text-white">My Domains</h1>
                    <p class="text-white/50 text-sm mt-1">Organize your interview preparation topics</p>
                </div>
                <div class="flex items-center gap-3">
                    <?php if(isset($trashCount) && $trashCount > 0): ?>
                        <a href="<?php echo e(route('domains.trash')); ?>" class="flex items-center gap-2 px-4 py-2 bg-white/5 hover:bg-white/10 border border-white/10 text-white/60 hover:text-white rounded-xl font-medium transition text-sm" title="View trash">
                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"/>
                            </svg>
                            Trash
                            <span class="px-1.5 py-0.5 bg-red-500/20 text-red-400 text-xs rounded-md font-semibold"><?php echo e($trashCount); ?></span>
                        </a>
                    <?php endif; ?>
                    <a href="<?php echo e(route('domains.create')); ?>" class="flex items-center gap-2 px-4 py-2.5 bg-indigo-600 hover:bg-indigo-700 text-white rounded-xl font-medium transition text-sm shadow-lg shadow-indigo-500/20">
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"/>
                        </svg>
                        New Domain
                    </a>
                </div>
            </div>
        </div>

        <div class="pb-8">
            <?php if(session('success')): ?>
                <div class="mb-5 p-4 bg-emerald-500/20 border border-emerald-500/50 rounded-xl text-emerald-400">
                    <?php echo e(session('success')); ?>

                </div>
            <?php endif; ?>

            <?php if($domains->isEmpty()): ?>
                <div class="bg-white/5 backdrop-blur-sm border border-white/10 rounded-2xl p-16 text-center">
                    <div class="w-20 h-20 mx-auto mb-5 bg-indigo-500/20 rounded-2xl flex items-center justify-center">
                        <svg class="w-10 h-10 text-indigo-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 11H5m14 0a2 2 0 012 2v6a2 2 0 01-2 2H5a2 2 0 01-2-2v-6a2 2 0 012-2m14 0V9a2 2 0 00-2-2M5 11V9a2 2 0 012-2m0 0V5a2 2 0 012-2h6a2 2 0 012 2v2M7 7h10"/>
                        </svg>
                    </div>
                    <h3 class="text-xl font-semibold text-white mb-2">No domains yet</h3>
                    <p class="text-white/50 mb-6">Create your first domain to start organizing your concepts</p>
                    <a href="<?php echo e(route('domains.create')); ?>" class="inline-flex items-center gap-2 px-6 py-3 bg-indigo-600 hover:bg-indigo-700 text-white rounded-xl font-medium transition">
                        Create Your First Domain
                    </a>
                </div>
            <?php else: ?>
                <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-5">
                    <?php $__currentLoopData = $domains; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $domain): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                        <div class="group bg-white/5 backdrop-blur-sm border border-white/10 rounded-2xl overflow-hidden hover:bg-white/10 hover:border-white/20 transition-all">
                            <div class="h-1.5" style="background-color: <?php echo e($domain->color); ?>;"></div>
                            <div class="p-5">
                                <div class="flex items-start justify-between mb-4">
                                    <div class="flex items-center gap-3">
                                        <div class="w-3 h-3 rounded-full shadow-sm" style="background-color: <?php echo e($domain->color); ?>;"></div>
                                        <h3 class="text-base font-semibold text-white"><?php echo e($domain->name); ?></h3>
                                    </div>
                                    <div class="flex gap-1 opacity-0 group-hover:opacity-100 transition">
                                        <a href="<?php echo e(route('domains.edit', $domain)); ?>" class="p-2 text-white/40 hover:text-indigo-400 transition rounded-lg hover:bg-white/5">
                                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15.232 5.232l3.536 3.536m-2.036-5.036a2.5 2.5 0 113.536 3.536L6.5 21.036H3v-3.572L16.732 3.732z"/>
                                            </svg>
                                        </a>
                                        <form action="<?php echo e(route('domains.destroy', $domain)); ?>" method="POST" onsubmit="return confirm('Move this domain to trash?');">
                                            <?php echo csrf_field(); ?>
                                            <?php echo method_field('DELETE'); ?>
                                            <button type="submit" class="p-2 text-white/40 hover:text-red-400 transition rounded-lg hover:bg-white/5">
                                                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"/>
                                                </svg>
                                            </button>
                                        </form>
                                    </div>
                                </div>

                                <div class="flex items-center gap-4 text-xs text-white/40 mb-4">
                                    <span class="flex items-center gap-1.5">
                                        <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"/>
                                        </svg>
                                        <?php echo e($domain->concepts_count); ?> concepts
                                    </span>
                                </div>

                                <div class="space-y-2 mb-4">
                                    <div class="flex justify-between text-xs">
                                        <span class="text-emerald-400"><?php echo e($domain->masteredCount()); ?> Mastered</span>
                                        <span class="text-amber-400"><?php echo e($domain->inProgressCount()); ?> In Progress</span>
                                        <span class="text-blue-400"><?php echo e($domain->toReviewCount()); ?> To Review</span>
                                    </div>
                                    <?php
                                        $total = $domain->masteredCount() + $domain->inProgressCount() + $domain->toReviewCount();
                                        $masteredPct = $total > 0 ? ($domain->masteredCount() / $total) * 100 : 0;
                                        $inProgressPct = $total > 0 ? ($domain->inProgressCount() / $total) * 100 : 0;
                                    ?>
                                    <div class="h-1.5 bg-white/10 rounded-full overflow-hidden flex">
                                        <div class="bg-emerald-500" style="width: <?php echo e($masteredPct); ?>%;"></div>
                                        <div class="bg-amber-500" style="width: <?php echo e($inProgressPct); ?>%;"></div>
                                    </div>
                                </div>

                                <a href="<?php echo e(route('domains.show', $domain)); ?>" class="flex items-center justify-center gap-2 w-full py-2.5 bg-white/5 hover:bg-white/10 text-white/60 hover:text-white rounded-xl transition text-sm font-medium">
                                    View Domain
                                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"/>
                                    </svg>
                                </a>
                            </div>
                        </div>
                    <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                </div>
            <?php endif; ?>
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
<?php endif; ?><?php /**PATH C:\Users\pc\Desktop\learn php\htdocs\Interview_Prep\interviewprep\resources\views/domains/index.blade.php ENDPATH**/ ?>