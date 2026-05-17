<!DOCTYPE html>
<html lang="<?php echo e(str_replace('_', '-', app()->getLocale())); ?>">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title><?php echo e(config('app.name', 'InterviewPrep')); ?></title>
    <?php echo app('Illuminate\Foundation\Vite')(['resources/css/app.css', 'resources/js/app.js']); ?>
</head>
<body class="bg-gradient-to-br from-slate-900 via-slate-800 to-slate-900 min-h-screen text-white">
    <div class="min-h-screen flex flex-col">
        <nav class="flex items-center justify-between p-6">
            <a href="<?php echo e(route('home')); ?>" class="text-xl font-bold bg-gradient-to-r from-indigo-400 to-purple-400 bg-clip-text text-transparent">
                InterviewPrep
            </a>
            <div class="flex items-center gap-4">
                <?php if(auth()->guard()->check()): ?>
                    <a href="<?php echo e(route('domains.index')); ?>" class="px-5 py-2 text-white/70 hover:text-white transition">
                        Domains
                    </a>
                    <a href="<?php echo e(route('concepts.index')); ?>" class="px-5 py-2 text-white/70 hover:text-white transition">
                        Concepts
                    </a>
                    <form method="POST" action="<?php echo e(route('logout')); ?>" class="inline">
                        <?php echo csrf_field(); ?>
                        <button type="submit" class="px-5 py-2 bg-white/10 hover:bg-white/20 text-white rounded-lg transition">
                            Log out
                        </button>
                    </form>
                <?php else: ?>
                    <a href="<?php echo e(route('login')); ?>" class="px-5 py-2 text-white/70 hover:text-white transition">Log in</a>
                    <a href="<?php echo e(route('register')); ?>" class="px-5 py-2 bg-indigo-600 hover:bg-indigo-700 rounded-lg font-medium transition">
                        Register
                    </a>
                <?php endif; ?>
            </div>
        </nav>

        <main class="flex-1 pb-12">
            <?php if(auth()->guard()->check()): ?>
                <div class="max-w-7xl mx-auto">
                    <div class="mb-8">
                        <h1 class="text-3xl font-bold text-white mb-1">Welcome back, <?php echo e(auth()->user()->name); ?>!</h1>
                        <p class="text-white/50 text-sm">Here's an overview of your interview preparation</p>
                    </div>

                    <?php if(session('success')): ?>
                        <div class="mb-5 p-4 bg-emerald-500/20 border border-emerald-500/50 rounded-xl text-emerald-400">
                            <?php echo e(session('success')); ?>

                        </div>
                    <?php endif; ?>

                    <?php
                        $domains = auth()->user()->domains()->withCount('concepts')->get();
                        $totalConcepts = $domains->sum('concepts_count');
                        $totalMastered = $domains->sum(fn($d) => $d->masteredCount());
                    ?>

                    <div class="grid grid-cols-1 md:grid-cols-3 gap-4 mb-8">
                        <div class="bg-white/5 backdrop-blur-sm border border-white/10 rounded-2xl p-5">
                            <div class="text-2xl font-bold text-white mb-1"><?php echo e($domains->count()); ?></div>
                            <div class="text-white/40 text-sm">Total Domains</div>
                        </div>
                        <div class="bg-white/5 backdrop-blur-sm border border-white/10 rounded-2xl p-5">
                            <div class="text-2xl font-bold text-white mb-1"><?php echo e($totalConcepts); ?></div>
                            <div class="text-white/40 text-sm">Total Concepts</div>
                        </div>
                        <div class="bg-white/5 backdrop-blur-sm border border-white/10 rounded-2xl p-5">
                            <div class="text-2xl font-bold text-emerald-400 mb-1"><?php echo e($totalMastered); ?></div>
                            <div class="text-white/40 text-sm">Mastered Concepts</div>
                        </div>
                    </div>

                    <div class="flex items-center justify-between mb-5">
                        <h2 class="text-base font-semibold text-white/70">Your Domains</h2>
                        <a href="<?php echo e(route('domains.create')); ?>" class="flex items-center gap-2 px-4 py-2 bg-indigo-600 hover:bg-indigo-700 text-white rounded-xl font-medium transition text-sm shadow-lg shadow-indigo-500/20">
                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"/>
                            </svg>
                            New Domain
                        </a>
                    </div>

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
                        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-4">
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
                                                <form action="<?php echo e(route('domains.destroy', $domain)); ?>" method="POST" onsubmit="return confirm('Are you sure?');">
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
            <?php else: ?>
                <div class="text-center mb-16 mt-8">
                    <h1 class="text-5xl md:text-6xl font-bold mb-4 bg-gradient-to-r from-indigo-400 to-purple-400 bg-clip-text text-transparent">
                        InterviewPrep
                    </h1>
                    <p class="text-xl text-white/70 max-w-2xl mx-auto">
                        Master your technical interviews with AI-powered question generation
                    </p>
                </div>

                <div class="max-w-6xl mx-auto">
                    <h2 class="text-2xl font-bold mb-8 text-center text-white/90">Featured Domains</h2>

                    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
                        <div class="bg-white/5 backdrop-blur-sm border border-white/10 rounded-2xl p-6 hover:bg-white/10 transition cursor-pointer group">
                            <div class="flex items-center gap-3 mb-4">
                                <div class="w-10 h-10 rounded-xl bg-indigo-500/20 flex items-center justify-center">
                                    <svg class="w-5 h-5 text-indigo-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 20l4-16m4 4l4 4-4 4M6 16l-4-4 4-4"/>
                                    </svg>
                                </div>
                                <span class="w-4 h-4 rounded-full bg-indigo-500"></span>
                            </div>
                            <h3 class="text-xl font-semibold mb-2 group-hover:text-indigo-400 transition">Laravel</h3>
                            <p class="text-white/50 text-sm">Master Laravel framework concepts and best practices</p>
                        </div>

                        <div class="bg-white/5 backdrop-blur-sm border border-white/10 rounded-2xl p-6 hover:bg-white/10 transition cursor-pointer group">
                            <div class="flex items-center gap-3 mb-4">
                                <div class="w-10 h-10 rounded-xl bg-emerald-500/20 flex items-center justify-center">
                                    <svg class="w-5 h-5 text-emerald-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4.318 6.318a4.5 4.5 0 000 9.75a4.5 4.5 0 009.75 0a4.5 4.5 0 000-9.75a4.5 4.5 0 00-9.75 0"/>
                                    </svg>
                                </div>
                                <span class="w-4 h-4 rounded-full bg-emerald-500"></span>
                            </div>
                            <h3 class="text-xl font-semibold mb-2 group-hover:text-emerald-400 transition">PHP OOP</h3>
                            <p class="text-white/50 text-sm">Object-oriented programming in PHP</p>
                        </div>

                        <div class="bg-white/5 backdrop-blur-sm border border-white/10 rounded-2xl p-6 hover:bg-white/10 transition cursor-pointer group">
                            <div class="flex items-center gap-3 mb-4">
                                <div class="w-10 h-10 rounded-xl bg-amber-500/20 flex items-center justify-center">
                                    <svg class="w-5 h-5 text-amber-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 9l3 3-3 3m5 0h3M5 20h14a2 2 0 002-2V6a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"/>
                                    </svg>
                                </div>
                                <span class="w-4 h-4 rounded-full bg-amber-500"></span>
                            </div>
                            <h3 class="text-xl font-semibold mb-2 group-hover:text-amber-400 transition">REST API</h3>
                            <p class="text-white/50 text-sm">Build and consume REST APIs</p>
                        </div>

                        <div class="bg-white/5 backdrop-blur-sm border border-white/10 rounded-2xl p-6 hover:bg-white/10 transition cursor-pointer group">
                            <div class="flex items-center gap-3 mb-4">
                                <div class="w-10 h-10 rounded-xl bg-blue-500/20 flex items-center justify-center">
                                    <svg class="w-5 h-5 text-blue-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 7v10c0 2.21 3.582 4 8 4s8-1.79 8-4V7M4 7c0 2.21 3.582 4 8 4s8-1.79 8-4M4 7c0-2.21 3.582-4 8-4s8 1.79 8 4"/>
                                    </svg>
                                </div>
                                <span class="w-4 h-4 rounded-full bg-blue-500"></span>
                            </div>
                            <h3 class="text-xl font-semibold mb-2 group-hover:text-blue-400 transition">MySQL</h3>
                            <p class="text-white/50 text-sm">Database design and optimization</p>
                        </div>

                        <div class="bg-white/5 backdrop-blur-sm border border-white/10 rounded-2xl p-6 hover:bg-white/10 transition cursor-pointer group">
                            <div class="flex items-center gap-3 mb-4">
                                <div class="w-10 h-10 rounded-xl bg-rose-500/20 flex items-center justify-center">
                                    <svg class="w-5 h-5 text-rose-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9.663 17h4.673M12 3v1m6.364 1.636l-.707.707m16.364 12.364l.707.707M21 12h-1M4 12H3m3.343-5.657l-.707-.707m2.828 9.9a5 5 0 117.072 0l-.548.547A3.374 3.374 0 0014 18.469V19a2 2 0 11-4 0v-.531c0-.895-.356-1.754-.988-2.386l-.548-.547z"/>
                                    </svg>
                                </div>
                                <span class="w-4 h-4 rounded-full bg-rose-500"></span>
                            </div>
                            <h3 class="text-xl font-semibold mb-2 group-hover:text-rose-400 transition">Algorithms</h3>
                            <p class="text-white/50 text-sm">Data structures and problem solving</p>
                        </div>

                        <div class="bg-white/5 backdrop-blur-sm border border-white/10 rounded-2xl p-6 hover:bg-white/10 transition cursor-pointer group">
                            <div class="flex items-center gap-3 mb-4">
                                <div class="w-10 h-10 rounded-xl bg-cyan-500/20 flex items-center justify-center">
                                    <svg class="w-5 h-5 text-cyan-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 12a9 9 0 01-9 9m9-9a9 9 0 00-9-9m9 9H3m9 9a9 9 0 01-9-9m9 9c1.657 0 3-4.03 3-9s-1.343-9-3-9m0 18c-1.657 0 3-4.03 3-9s-1.343-9-3-9m-9 9a9 9 0 019-9"/>
                                    </svg>
                                </div>
                                <span class="w-4 h-4 rounded-full bg-cyan-500"></span>
                            </div>
                            <h3 class="text-xl font-semibold mb-2 group-hover:text-cyan-400 transition">System Design</h3>
                            <p class="text-white/50 text-sm">Scalable architecture principles</p>
                        </div>
                    </div>

                    <div class="text-center mt-12">
                        <p class="text-white/50 mb-4">Ready to start your journey?</p>
                        <a href="<?php echo e(route('register')); ?>" class="inline-block px-8 py-3 bg-gradient-to-r from-indigo-600 to-purple-600 hover:from-indigo-700 hover:to-purple-700 rounded-xl font-semibold transition transform hover:scale-105">
                            Get Started Free
                        </a>
                    </div>
                </div>
            <?php endif; ?>
        </main>

        <footer class="text-center py-6 text-white/30">
            <p>Built with Laravel & Groq AI</p>
        </footer>
    </div>
</body>
</html><?php /**PATH C:\Users\pc\Desktop\learn php\htdocs\Interview_Prep\interviewprep\resources\views/welcome.blade.php ENDPATH**/ ?>