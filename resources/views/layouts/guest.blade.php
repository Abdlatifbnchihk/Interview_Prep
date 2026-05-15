<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>{{ config('app.name', 'InterviewPrep') }}</title>
    @vite(['resources/css/app.css', 'resources/js/app.js'])
</head>
<body class="bg-gradient-to-br from-slate-900 via-slate-800 to-slate-900 min-h-screen text-white">
    <div class="min-h-screen flex flex-col items-center justify-center py-12 px-4">
        <div class="w-full max-w-md">
            <div class="text-center mb-8">
                <a href="{{ route('home') }}" class="inline-block text-2xl font-bold bg-gradient-to-r from-indigo-400 to-purple-400 bg-clip-text text-transparent">
                    InterviewPrep
                </a>
            </div>

            <div class="bg-white/5 backdrop-blur-sm border border-white/10 rounded-2xl p-8">
                {{ $slot }}
            </div>
        </div>
    </div>
</body>
</html>