<!DOCTYPE html>
<html lang="<?php echo e(str_replace('_', '-', app()->getLocale())); ?>">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="<?php echo e(csrf_token()); ?>">
    <title><?php echo e(config('app.name', 'InterviewPrep')); ?></title>
    <link rel="preconnect" href="https://fonts.bunny.net">
    <link href="https://fonts.bunny.net/css?family=figtree:400,500,600&display=swap" rel="stylesheet" />
    <?php echo app('Illuminate\Foundation\Vite')(['resources/css/app.css', 'resources/js/app.js']); ?>
</head>
<body class="bg-gradient-to-br from-slate-900 via-slate-800 to-slate-900 min-h-screen text-white">
    <div class="min-h-screen flex flex-col items-center justify-center py-12 px-4">
        <div class="w-full max-w-md">
            <div class="text-center mb-8">
                <a href="<?php echo e(route('home')); ?>" class="inline-block text-2xl font-bold bg-gradient-to-r from-indigo-400 to-purple-400 bg-clip-text text-transparent">
                    InterviewPrep
                </a>
            </div>

            <div class="bg-white/5 backdrop-blur-sm border border-white/10 rounded-2xl p-8">
                <?php echo e($slot); ?>

            </div>
        </div>
    </div>
</body>
</html><?php /**PATH C:\Users\pc\Desktop\learn php\htdocs\Interview_Prep\interviewprep\resources\views/layouts/guest.blade.php ENDPATH**/ ?>