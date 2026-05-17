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
                    <h1 class="text-2xl font-bold text-white">Trash</h1>
                    <p class="text-white/50 text-sm mt-1">Soft-deleted concepts — restore or permanently delete</p>
                </div>
                <a href="<?php echo e(route('concepts.index')); ?>" class="flex items-center gap-2 px-4 py-2 bg-white/5 hover:bg-white/10 border border-white/10 text-white/60 hover:text-white rounded-xl font-medium transition text-sm">
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7"/>
                    </svg>
                    Back to Concepts
                </a>
            </div>
        </div>

        <div class="pb-8">
            <?php if(session('success')): ?>
                <div class="mb-5 p-4 bg-emerald-500/20 border border-emerald-500/50 rounded-xl text-emerald-400">
                    <?php echo e(session('success')); ?>

                </div>
            <?php endif; ?>

            <?php if($concepts->isEmpty()): ?>
                <div class="bg-white/5 backdrop-blur-sm border border-white/10 rounded-2xl p-16 text-center">
                    <div class="w-20 h-20 mx-auto mb-5 bg-white/5 rounded-2xl flex items-center justify-center">
                        <svg class="w-10 h-10 text-white/30" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"/>
                        </svg>
                    </div>
                    <h3 class="text-xl font-semibold text-white mb-2">Trash is empty</h3>
                    <p class="text-white/50">Deleted concepts will appear here. You can restore or permanently delete them.</p>
                </div>
            <?php else: ?>
                <div class="space-y-2">
                    <?php $__currentLoopData = $concepts; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $concept): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                        <div class="flex items-center gap-4 bg-white/5 backdrop-blur-sm border border-white/10 rounded-xl px-5 py-4 opacity-70 hover:opacity-100 transition">
                            <div class="w-2.5 h-2.5 rounded-full bg-red-500/50 flex-shrink-0"></div>
                            <div class="flex-1 min-w-0">
                                <div class="flex items-center gap-3 mb-1 flex-wrap">
                                    <h3 class="text-sm font-medium text-white truncate"><?php echo e($concept->title); ?></h3>
                                    <?php if($concept->domain): ?>
                                        <span class="px-2 py-0.5 text-xs rounded-full bg-white/5 text-white/40 flex-shrink-0 flex items-center gap-1">
                                            <span class="w-1.5 h-1.5 rounded-full" style="background-color: <?php echo e($concept->domain->color); ?>;"></span>
                                            <?php echo e($concept->domain->name); ?>

                                        </span>
                                    <?php else: ?>
                                        <span class="px-2 py-0.5 text-xs bg-red-500/10 text-red-400 rounded-full flex-shrink-0">Domain deleted</span>
                                    <?php endif; ?>
                                    <span class="px-2 py-0.5 text-xs bg-red-500/10 text-red-400 rounded-full flex-shrink-0">Trashed</span>
                                </div>
                                <div class="flex items-center gap-3 text-xs text-white/30">
                                    <span class="flex items-center gap-1.5">
                                        <svg class="w-3 h-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"/>
                                        </svg>
                                        Deleted <?php echo e($concept->deleted_at->diffForHumans()); ?>

                                    </span>
                                    <span class="px-2 py-0.5 text-xs rounded-full bg-<?php echo e($concept->difficulty->color()); ?>-500/20 text-<?php echo e($concept->difficulty->color()); ?>-400">
                                        <?php echo e($concept->difficulty->label()); ?>

                                    </span>
                                </div>
                            </div>
                            <div class="flex items-center gap-2 flex-shrink-0">
                                <form action="<?php echo e(route('concepts.restore', $concept->id)); ?>" method="POST">
                                    <?php echo csrf_field(); ?>
                                    <?php echo method_field('PATCH'); ?>
                                    <button type="submit" class="flex items-center gap-2 px-4 py-2 bg-emerald-500/10 hover:bg-emerald-500/20 border border-emerald-500/30 text-emerald-400 hover:text-emerald-300 rounded-xl font-medium transition text-sm" title="Restore">
                                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 4v5h.582m15.356 2A8.001 8.001 0 004.582 9m0 0H9m11 11v-5h-.581m0 0a8.003 8.003 0 01-15.357-2m15.357 2H15"/>
                                        </svg>
                                        Restore
                                    </button>
                                </form>
                                <form action="<?php echo e(route('concepts.forceDelete', $concept->id)); ?>" method="POST" onsubmit="return confirm('Permanently delete this concept and ALL its questions? This cannot be undone.');">
                                    <?php echo csrf_field(); ?>
                                    <?php echo method_field('DELETE'); ?>
                                    <button type="submit" class="p-2 bg-red-500/10 hover:bg-red-500/20 border border-red-500/30 text-red-400 hover:text-red-300 rounded-xl transition" title="Delete forever">
                                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"/>
                                        </svg>
                                    </button>
                                </form>
                            </div>
                        </div>
                    <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                </div>

                <div class="mt-6">
                    <div class="bg-white/5 backdrop-blur-sm border border-white/10 rounded-xl p-4">
                        <?php echo e($concepts->withQueryString()->links()); ?>

                    </div>
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
<?php endif; ?><?php /**PATH C:\Users\pc\Desktop\learn php\htdocs\Interview_Prep\interviewprep\resources\views/concepts/trash.blade.php ENDPATH**/ ?>