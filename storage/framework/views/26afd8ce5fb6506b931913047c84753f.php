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
        <div class="pt-4 pb-0">
            <div class="flex items-center justify-between mb-6">
                <div>
                    <h1 class="text-2xl font-bold text-white">My Concepts</h1>
                    <p class="text-white/50 text-sm mt-1">All your concepts across domains</p>
                </div>
                <div class="flex items-center gap-3">
                    <?php if(isset($trashCount) && $trashCount > 0): ?>
                        <a href="<?php echo e(route('concepts.trash')); ?>" class="flex items-center gap-2 px-4 py-2 bg-white/5 hover:bg-white/10 border border-white/10 text-white/60 hover:text-white rounded-xl font-medium transition text-sm" title="View trash">
                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"/>
                            </svg>
                            Trash
                            <span class="px-1.5 py-0.5 bg-red-500/20 text-red-400 text-xs rounded-md font-semibold"><?php echo e($trashCount); ?></span>
                        </a>
                    <?php endif; ?>
                    <a href="<?php echo e(route('concepts.create')); ?>" class="flex items-center gap-2 px-4 py-2.5 bg-indigo-600 hover:bg-indigo-700 text-white rounded-xl font-medium transition text-sm shadow-lg shadow-indigo-500/20">
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"/>
                        </svg>
                        New Concept
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

            <div class="bg-gradient-to-br from-white/5 to-white/3 backdrop-blur-sm border border-white/10 rounded-2xl p-5 mb-5">
                <div class="flex items-center gap-3 mb-4">
                    <div class="w-9 h-9 bg-indigo-500/20 rounded-xl flex items-center justify-center">
                        <svg class="w-4 h-4 text-indigo-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 4a1 1 0 011-1h16a1 1 0 011 1v2.036a2 2 0 01-.469 1.227l-6.5 6.5a2 2 0 01-.636.318l-.636-.318L4.469 8.264A2 2 0 014 7.036V4z"/>
                        </svg>
                    </div>
                    <div>
                        <h3 class="text-sm font-semibold text-white">Filter Concepts</h3>
                        <p class="text-white/40 text-xs">
                            <?php
                                $activeFilters = 0;
                                if (request('domain_id')) $activeFilters++;
                                if (request('status')) $activeFilters++;
                                if (request('difficulty')) $activeFilters++;
                            ?>
                            <?php if($activeFilters > 0): ?>
                                <span class="text-indigo-400 font-medium"><?php echo e($activeFilters); ?></span> filter<?php echo e($activeFilters > 1 ? 's' : ''); ?> applied
                            <?php else: ?>
                                Narrow down by domain, status, or difficulty
                            <?php endif; ?>
                        </p>
                    </div>
                </div>

                <form method="GET" action="<?php echo e(route('concepts.index')); ?>" class="grid grid-cols-1 sm:grid-cols-4 gap-3 items-end">
                    <div>
                        <label class="block text-xs text-white/40 mb-1.5 font-medium">Domain</label>
                        <select name="domain_id" class="w-full bg-white/5 border border-white/10 rounded-xl text-white text-sm px-4 py-2.5 focus:outline-none focus:border-indigo-500 focus:ring-1 focus:ring-indigo-500/50 transition">
                            <option value="">All domains</option>
                            <?php $__currentLoopData = $domains; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $domain): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                <option value="<?php echo e($domain->id); ?>" <?php echo e(request('domain_id') == $domain->id ? 'selected' : ''); ?>>
                                    <?php echo e($domain->name); ?>

                                </option>
                            <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                        </select>
                    </div>
                    <div>
                        <label class="block text-xs text-white/40 mb-1.5 font-medium">Status</label>
                        <select name="status" class="w-full bg-white/5 border border-white/10 rounded-xl text-white text-sm px-4 py-2.5 focus:outline-none focus:border-indigo-500 focus:ring-1 focus:ring-indigo-500/50 transition">
                            <option value="">All statuses</option>
                            <?php $__currentLoopData = \App\Enums\ConceptStatus::cases(); $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $status): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                <option value="<?php echo e($status->value); ?>" <?php echo e(request('status') == $status->value ? 'selected' : ''); ?>>
                                    <?php echo e($status->label()); ?>

                                </option>
                            <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                        </select>
                    </div>
                    <div>
                        <label class="block text-xs text-white/40 mb-1.5 font-medium">Difficulty</label>
                        <select name="difficulty" class="w-full bg-white/5 border border-white/10 rounded-xl text-white text-sm px-4 py-2.5 focus:outline-none focus:border-indigo-500 focus:ring-1 focus:ring-indigo-500/50 transition">
                            <option value="">All levels</option>
                            <?php $__currentLoopData = \App\Enums\ConceptDifficulty::cases(); $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $diff): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                <option value="<?php echo e($diff->value); ?>" <?php echo e(request('difficulty') == $diff->value ? 'selected' : ''); ?>>
                                    <?php echo e($diff->label()); ?>

                                </option>
                            <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                        </select>
                    </div>
                    <div class="flex items-center gap-2">
                        <button type="submit" class="flex-1 flex items-center justify-center gap-2 px-4 py-2.5 bg-indigo-600 hover:bg-indigo-700 text-white rounded-xl font-medium transition text-sm shadow-lg shadow-indigo-500/20">
                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"/>
                            </svg>
                            Apply
                        </button>
                        <?php if($activeFilters > 0): ?>
                            <a href="<?php echo e(route('concepts.index')); ?>" class="px-4 py-2.5 bg-white/5 hover:bg-white/10 border border-white/10 text-white/60 hover:text-white rounded-xl font-medium transition text-sm text-center" title="Clear all filters">
                                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/>
                                </svg>
                            </a>
                        <?php endif; ?>
                    </div>
                </form>
            </div>

            <?php if($concepts->isEmpty()): ?>
                <div class="bg-white/5 backdrop-blur-sm border border-white/10 rounded-2xl p-16 text-center">
                    <div class="w-20 h-20 mx-auto mb-5 bg-indigo-500/20 rounded-2xl flex items-center justify-center">
                        <svg class="w-10 h-10 text-indigo-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"/>
                        </svg>
                    </div>
                    <h3 class="text-xl font-semibold text-white mb-2">
                        <?php if(request()->hasAny(['domain_id', 'status', 'difficulty'])): ?>
                            No concepts match your filters
                        <?php else: ?>
                            No concepts yet
                        <?php endif; ?>
                    </h3>
                    <p class="text-white/50 mb-6">
                        <?php if(request()->hasAny(['domain_id', 'status', 'difficulty'])): ?>
                            Try adjusting your filters or clear them to see all concepts.
                        <?php else: ?>
                            Create your first concept to start preparing for interviews.
                        <?php endif; ?>
                    </p>
                    <a href="<?php echo e(route('concepts.create')); ?>" class="inline-flex items-center gap-2 px-6 py-3 bg-indigo-600 hover:bg-indigo-700 text-white rounded-xl font-medium transition">
                        Create Your First Concept
                    </a>
                </div>
            <?php else: ?>
                <div class="flex items-center justify-between mb-3">
                    <p class="text-sm text-white/40">
                        Showing <span class="text-white/70 font-medium"><?php echo e($concepts->count()); ?></span>
                        of <span class="text-white/70 font-medium"><?php echo e($concepts->total()); ?></span> concept<?php echo e($concepts->total() != 1 ? 's' : ''); ?>

                    </p>
                </div>

                <div class="space-y-2">
                    <?php $__currentLoopData = $concepts; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $concept): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                        <div class="group flex items-center gap-4 bg-white/5 backdrop-blur-sm border border-white/10 rounded-xl px-5 py-4 hover:bg-white/10 hover:border-white/20 transition cursor-pointer"
                            onclick="window.location='<?php echo e(route('concepts.show', $concept)); ?>'">
                            <div class="w-2.5 h-2.5 rounded-full bg-<?php echo e($concept->status->color()); ?>-500 flex-shrink-0 group-hover:scale-110 transition"></div>
                            <div class="flex-1 min-w-0 cursor-pointer" onclick="event.stopPropagation(); window.location='<?php echo e(route('concepts.show', $concept)); ?>'">
                                <div class="flex items-center gap-3 mb-1.5 flex-wrap">
                                    <h3 class="text-sm font-medium text-white group-hover:text-indigo-400 transition"><?php echo e($concept->title); ?></h3>
                                    <span class="px-2 py-0.5 text-xs rounded-full bg-<?php echo e($concept->difficulty->color()); ?>-500/20 text-<?php echo e($concept->difficulty->color()); ?>-400 font-medium flex-shrink-0">
                                        <?php echo e($concept->difficulty->label()); ?>

                                    </span>
                                    <span class="px-2 py-0.5 text-xs rounded-full bg-<?php echo e($concept->status->color()); ?>-500/20 text-<?php echo e($concept->status->color()); ?>-400 font-medium flex-shrink-0">
                                        <?php echo e($concept->status->label()); ?>

                                    </span>
                                </div>
                                <div class="flex items-center gap-1.5 text-white/30 text-xs">
                                    <svg class="w-3 h-3 flex-shrink-0" style="color: <?php echo e($concept->domain->color); ?>;" fill="currentColor" viewBox="0 0 24 24">
                                        <circle cx="6" cy="12" r="4"/>
                                    </svg>
                                    <?php echo e($concept->domain->name); ?>

                                </div>
                            </div>
                            <div class="flex items-center gap-1.5 flex-shrink-0 opacity-0 group-hover:opacity-100 transition" onclick="event.stopPropagation();">
                                <a href="<?php echo e(route('concepts.edit', $concept)); ?>" class="p-2 text-white/40 hover:text-indigo-400 hover:bg-white/5 rounded-lg transition" title="Edit">
                                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15.232 5.232l3.536 3.536m-2.036-5.036a2.5 2.5 0 113.536 3.536L6.5 21.036H3v-3.572L16.732 3.732z"/>
                                    </svg>
                                </a>
                                <form action="<?php echo e(route('concepts.destroy', $concept)); ?>" method="POST" onsubmit="return confirm('Move this concept to trash?');">
                                    <?php echo csrf_field(); ?>
                                    <?php echo method_field('DELETE'); ?>
                                    <button type="submit" class="p-2 text-white/40 hover:text-red-400 hover:bg-white/5 rounded-lg transition" title="Delete">
                                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"/>
                                        </svg>
                                    </button>
                                </form>
                            </div>
                            <svg class="w-4 h-4 text-white/30 group-hover:text-white/50 group-hover:translate-x-0.5 transition flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24" onclick="event.stopPropagation(); window.location='<?php echo e(route('concepts.show', $concept)); ?>'">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"/>
                            </svg>
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
<?php endif; ?><?php /**PATH C:\Users\pc\Desktop\learn php\htdocs\Interview_Prep\interviewprep\resources\views/concepts/index.blade.php ENDPATH**/ ?>