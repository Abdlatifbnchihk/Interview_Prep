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
                <a href="<?php echo e(route('concepts.show', $concept)); ?>" class="inline-flex items-center gap-2 text-white/50 hover:text-white transition text-sm mb-4">
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7"/>
                    </svg>
                    Back to <?php echo e($concept->title); ?>

                </a>
            </div>
        </div>

        <div class="pb-8">
            <?php if(session('success')): ?>
                <div class="mb-5 p-4 bg-emerald-500/20 border border-emerald-500/50 rounded-xl text-emerald-400">
                    <?php echo e(session('success')); ?>

                </div>
            <?php endif; ?>

            <?php if(session('error')): ?>
                <div class="mb-5 p-4 bg-red-500/20 border border-red-500/50 rounded-xl text-red-400">
                    <?php echo e(session('error')); ?>

                </div>
            <?php endif; ?>

            <div class="bg-gradient-to-br from-indigo-500/10 to-purple-500/10 backdrop-blur-sm border border-indigo-500/20 rounded-2xl p-5 mb-5">
                <form method="POST" action="<?php echo e(route('generations.store', $concept)); ?>">
                    <?php echo csrf_field(); ?>
                    <div class="flex items-center justify-between">
                        <div class="flex items-center gap-3">
                            <div class="w-12 h-12 bg-indigo-500/20 rounded-xl flex items-center justify-center">
                                <svg class="w-6 h-6 text-indigo-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 10V3L4 14h7v7l9-11h-7z"/>
                                </svg>
                            </div>
                            <div>
                                <h3 class="text-base font-semibold text-white">Generate Questions</h3>
                                <p class="text-white/40 text-xs">5 AI-powered interview questions</p>
                            </div>
                        </div>
                        <button type="submit" class="flex items-center gap-2 px-5 py-2.5 bg-indigo-600 hover:bg-indigo-700 text-white rounded-xl font-medium transition text-sm shadow-lg shadow-indigo-500/20">
                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 10V3L4 14h7v7l9-11h-7z"/>
                            </svg>
                            Generate
                        </button>
                    </div>
                </form>
            </div>

            <h2 class="text-base font-semibold text-white/70 mb-4">Generation History</h2>

            <?php if($concept->generations->isEmpty()): ?>
                <div class="bg-white/5 backdrop-blur-sm border border-white/10 rounded-2xl p-12 text-center">
                    <div class="w-16 h-16 mx-auto mb-4 bg-indigo-500/20 rounded-2xl flex items-center justify-center">
                        <svg class="w-8 h-8 text-indigo-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9.663 17h4.673M12 3v1m6.364 1.636l-.707.707m16.364 12.364l.707.707M21 12h-1M4 12H3m3.343-5.657l-.707-.707m2.828 9.9a5 5 0 117.072 0l-.548.547A3.374 3.374 0 0014 18.469V19a2 2 0 11-4 0v-.531c0-.895-.356-1.754-.988-2.386l-.548-.547z"/>
                        </svg>
                    </div>
                    <h3 class="text-lg font-semibold text-white mb-2">No questions generated yet</h3>
                    <p class="text-white/40 text-sm">Click "Generate" to create AI-powered questions</p>
                </div>
            <?php else: ?>
                <div class="space-y-3">
                    <?php $__currentLoopData = $concept->generations->sortByDesc('created_at'); $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $generation): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                        <div class="bg-white/5 backdrop-blur-sm border border-white/10 rounded-xl p-5">
                            <div class="flex items-center justify-between mb-4">
                                <div class="flex items-center gap-2 text-white/40 text-xs">
                                    <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"/>
                                    </svg>
                                    <?php echo e($generation->created_at->format('M d, Y H:i')); ?>

                                </div>
                                <form action="<?php echo e(route('generations.destroy', $generation)); ?>" method="POST" onsubmit="return confirm('Delete this generation?');">
                                    <?php echo csrf_field(); ?>
                                    <?php echo method_field('DELETE'); ?>
                                    <button type="submit" class="px-3 py-1.5 bg-red-500/10 hover:bg-red-500/20 text-red-400 rounded-lg transition text-xs">
                                        Delete
                                    </button>
                                </form>
                            </div>

                            <ul class="space-y-2.5">
                                <?php $__currentLoopData = $generation->questions; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $index => $question): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                    <li class="flex items-start gap-3">
                                        <span class="flex-shrink-0 w-5 h-5 bg-indigo-500/20 text-indigo-400 rounded-lg flex items-center justify-center text-xs font-semibold">
                                            <?php echo e($index + 1); ?>

                                        </span>
                                        <span class="text-white/70 text-sm leading-relaxed"><?php echo e($question->question); ?></span>
                                    </li>
                                <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                            </ul>
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
<?php endif; ?><?php /**PATH C:\Users\pc\Desktop\learn php\htdocs\Interview_Prep\interviewprep\resources\views/generations/index.blade.php ENDPATH**/ ?>