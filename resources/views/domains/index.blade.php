<x-app-layout>
    <x-slot:header>
        <div class="flex items-center justify-between">
            <h1 class="text-2xl font-bold text-white">My Domains</h1>
            <a href="{{ route('domains.create') }}" class="flex items-center gap-2 px-4 py-2 bg-indigo-600 hover:bg-indigo-700 text-white rounded-lg font-medium transition">
                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"/>
                </svg>
                Create Domain
            </a>
        </div>
    </x-slot:header>

    <div class="py-8">
        @if (session('success'))
            <div class="mb-6 p-4 bg-emerald-500/20 border border-emerald-500/50 rounded-xl text-emerald-400">
                {{ session('success') }}
            </div>
        @endif

        @if ($domains->isEmpty())
            <div class="bg-white/5 backdrop-blur-sm border border-white/10 rounded-2xl p-12 text-center">
                <div class="w-16 h-16 mx-auto mb-4 bg-indigo-500/20 rounded-full flex items-center justify-center">
                    <svg class="w-8 h-8 text-indigo-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 11H5m14 0a2 2 0 012 2v6a2 2 0 01-2 2H5a2 2 0 01-2-2v-6a2 2 0 012-2m14 0V9a2 2 0 00-2-2M5 11V9a2 2 0 012-2m0 0V5a2 2 0 012-2h6a2 2 0 012 2v2M7 7h10"/>
                    </svg>
                </div>
                <h3 class="text-xl font-semibold text-white mb-2">No domains yet</h3>
                <p class="text-white/50 mb-6">Create your first domain to start organizing your concepts</p>
                <a href="{{ route('domains.create') }}" class="inline-flex items-center gap-2 px-6 py-3 bg-indigo-600 hover:bg-indigo-700 text-white rounded-lg font-medium transition">
                    Create Your First Domain
                </a>
            </div>
        @else
            <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
                @foreach ($domains as $domain)
                    <div class="group bg-white/5 backdrop-blur-sm border border-white/10 rounded-2xl overflow-hidden hover:bg-white/10 transition">
                        <div class="h-2" style="background-color: {{ $domain->color }};"></div>
                        <div class="p-6">
                            <div class="flex items-start justify-between mb-4">
                                <div class="flex items-center gap-3">
                                    <div class="w-3 h-3 rounded-full" style="background-color: {{ $domain->color }};"></div>
                                    <h3 class="text-lg font-semibold text-white">{{ $domain->name }}</h3>
                                </div>
                                <div class="flex gap-2 opacity-0 group-hover:opacity-100 transition">
                                    <a href="{{ route('domains.edit', $domain) }}" class="p-2 text-white/50 hover:text-indigo-400 transition">
                                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15.232 5.232l3.536 3.536m-2.036-5.036a2.5 2.5 0 113.536 3.536L6.5 21.036H3v-3.572L16.732 3.732z"/>
                                        </svg>
                                    </a>
                                    <form action="{{ route('domains.destroy', $domain) }}" method="POST" onsubmit="return confirm('Are you sure?');">
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit" class="p-2 text-white/50 hover:text-red-400 transition">
                                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"/>
                                            </svg>
                                        </button>
                                    </form>
                                </div>
                            </div>

                            <div class="flex items-center gap-4 text-sm text-white/50 mb-4">
                                <span class="flex items-center gap-1">
                                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"/>
                                    </svg>
                                    {{ $domain->concepts_count }} concepts
                                </span>
                            </div>

                            <div class="space-y-2">
                                <div class="flex justify-between text-xs">
                                    <span class="text-emerald-400">{{ $domain->masteredCount() }} Mastered</span>
                                    <span class="text-amber-400">{{ $domain->inProgressCount() }} In Progress</span>
                                    <span class="text-blue-400">{{ $domain->toReviewCount() }} To Review</span>
                                </div>
                                @php
                                    $total = $domain->masteredCount() + $domain->inProgressCount() + $domain->toReviewCount();
                                    $masteredPct = $total > 0 ? ($domain->masteredCount() / $total) * 100 : 0;
                                    $inProgressPct = $total > 0 ? ($domain->inProgressCount() / $total) * 100 : 0;
                                @endphp
                                <div class="h-2 bg-white/10 rounded-full overflow-hidden flex">
                                    <div class="bg-emerald-500" style="width: {{ $masteredPct }}%;"></div>
                                    <div class="bg-amber-500" style="width: {{ $inProgressPct }}%;"></div>
                                </div>
                            </div>

                            <a href="{{ route('domains.show', $domain) }}" class="mt-4 flex items-center justify-center gap-2 w-full py-2 bg-white/5 hover:bg-white/10 text-white/70 hover:text-white rounded-lg transition text-sm">
                                View Domain
                                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"/>
                                </svg>
                            </a>
                        </div>
                    </div>
                @endforeach
            </div>
        @endif
    </div>
</x-app-layout>