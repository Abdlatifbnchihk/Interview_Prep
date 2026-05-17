<x-app-layout>
    <div class="pt-20">
        <div class="pt-4 pb-0">
            <div class="flex items-center justify-between mb-6">
                <div>
                    <h1 class="text-2xl font-bold text-white">My Concepts</h1>
                    <p class="text-white/50 text-sm mt-1">All your concepts across domains</p>
                </div>
                <div class="flex items-center gap-3">
                    @if (isset($trashCount) && $trashCount > 0)
                        <a href="{{ route('concepts.trash') }}" class="flex items-center gap-2 px-4 py-2 bg-white/5 hover:bg-white/10 border border-white/10 text-white/60 hover:text-white rounded-xl font-medium transition text-sm" title="View trash">
                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"/>
                            </svg>
                            Trash
                            <span class="px-1.5 py-0.5 bg-red-500/20 text-red-400 text-xs rounded-md font-semibold">{{ $trashCount }}</span>
                        </a>
                    @endif
                    <a href="{{ route('concepts.create') }}" class="flex items-center gap-2 px-4 py-2.5 bg-indigo-600 hover:bg-indigo-700 text-white rounded-xl font-medium transition text-sm shadow-lg shadow-indigo-500/20">
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"/>
                        </svg>
                        New Concept
                    </a>
                </div>
            </div>
        </div>

        <div class="pb-8">
            @if (session('success'))
                <div class="mb-5 p-4 bg-emerald-500/20 border border-emerald-500/50 rounded-xl text-emerald-400">
                    {{ session('success') }}
                </div>
            @endif

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
                            @php
                                $activeFilters = 0;
                                if (request('domain_id')) $activeFilters++;
                                if (request('status')) $activeFilters++;
                                if (request('difficulty')) $activeFilters++;
                            @endphp
                            @if ($activeFilters > 0)
                                <span class="text-indigo-400 font-medium">{{ $activeFilters }}</span> filter{{ $activeFilters > 1 ? 's' : '' }} applied
                            @else
                                Narrow down by domain, status, or difficulty
                            @endif
                        </p>
                    </div>
                </div>

                <form method="GET" action="{{ route('concepts.index') }}" class="grid grid-cols-1 sm:grid-cols-4 gap-3 items-end">
                    <div>
                        <label class="block text-xs text-white/40 mb-1.5 font-medium">Domain</label>
                        <select name="domain_id" class="w-full bg-white/5 border border-white/10 rounded-xl text-white text-sm px-4 py-2.5 focus:outline-none focus:border-indigo-500 focus:ring-1 focus:ring-indigo-500/50 transition">
                            <option value="">All domains</option>
                            @foreach ($domains as $domain)
                                <option value="{{ $domain->id }}" {{ request('domain_id') == $domain->id ? 'selected' : '' }}>
                                    {{ $domain->name }}
                                </option>
                            @endforeach
                        </select>
                    </div>
                    <div>
                        <label class="block text-xs text-white/40 mb-1.5 font-medium">Status</label>
                        <select name="status" class="w-full bg-white/5 border border-white/10 rounded-xl text-white text-sm px-4 py-2.5 focus:outline-none focus:border-indigo-500 focus:ring-1 focus:ring-indigo-500/50 transition">
                            <option value="">All statuses</option>
                            @foreach (\App\Enums\ConceptStatus::cases() as $status)
                                <option value="{{ $status->value }}" {{ request('status') == $status->value ? 'selected' : '' }}>
                                    {{ $status->label() }}
                                </option>
                            @endforeach
                        </select>
                    </div>
                    <div>
                        <label class="block text-xs text-white/40 mb-1.5 font-medium">Difficulty</label>
                        <select name="difficulty" class="w-full bg-white/5 border border-white/10 rounded-xl text-white text-sm px-4 py-2.5 focus:outline-none focus:border-indigo-500 focus:ring-1 focus:ring-indigo-500/50 transition">
                            <option value="">All levels</option>
                            @foreach (\App\Enums\ConceptDifficulty::cases() as $diff)
                                <option value="{{ $diff->value }}" {{ request('difficulty') == $diff->value ? 'selected' : '' }}>
                                    {{ $diff->label() }}
                                </option>
                            @endforeach
                        </select>
                    </div>
                    <div class="flex items-center gap-2">
                        <button type="submit" class="flex-1 flex items-center justify-center gap-2 px-4 py-2.5 bg-indigo-600 hover:bg-indigo-700 text-white rounded-xl font-medium transition text-sm shadow-lg shadow-indigo-500/20">
                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"/>
                            </svg>
                            Apply
                        </button>
                        @if ($activeFilters > 0)
                            <a href="{{ route('concepts.index') }}" class="px-4 py-2.5 bg-white/5 hover:bg-white/10 border border-white/10 text-white/60 hover:text-white rounded-xl font-medium transition text-sm text-center" title="Clear all filters">
                                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/>
                                </svg>
                            </a>
                        @endif
                    </div>
                </form>
            </div>

            @if ($concepts->isEmpty())
                <div class="bg-white/5 backdrop-blur-sm border border-white/10 rounded-2xl p-16 text-center">
                    <div class="w-20 h-20 mx-auto mb-5 bg-indigo-500/20 rounded-2xl flex items-center justify-center">
                        <svg class="w-10 h-10 text-indigo-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"/>
                        </svg>
                    </div>
                    <h3 class="text-xl font-semibold text-white mb-2">
                        @if (request()->hasAny(['domain_id', 'status', 'difficulty']))
                            No concepts match your filters
                        @else
                            No concepts yet
                        @endif
                    </h3>
                    <p class="text-white/50 mb-6">
                        @if (request()->hasAny(['domain_id', 'status', 'difficulty']))
                            Try adjusting your filters or clear them to see all concepts.
                        @else
                            Create your first concept to start preparing for interviews.
                        @endif
                    </p>
                    <a href="{{ route('concepts.create') }}" class="inline-flex items-center gap-2 px-6 py-3 bg-indigo-600 hover:bg-indigo-700 text-white rounded-xl font-medium transition">
                        Create Your First Concept
                    </a>
                </div>
            @else
                <div class="flex items-center justify-between mb-3">
                    <p class="text-sm text-white/40">
                        Showing <span class="text-white/70 font-medium">{{ $concepts->count() }}</span>
                        of <span class="text-white/70 font-medium">{{ $concepts->total() }}</span> concept{{ $concepts->total() != 1 ? 's' : '' }}
                    </p>
                </div>

                <div class="space-y-2">
                    @foreach ($concepts as $concept)
                        <div class="group flex items-center gap-4 bg-white/5 backdrop-blur-sm border border-white/10 rounded-xl px-5 py-4 hover:bg-white/10 hover:border-white/20 transition cursor-pointer"
                            onclick="window.location='{{ route('concepts.show', $concept) }}'">
                            <div class="w-2.5 h-2.5 rounded-full bg-{{ $concept->status->color() }}-500 flex-shrink-0 group-hover:scale-110 transition"></div>
                            <div class="flex-1 min-w-0 cursor-pointer" onclick="event.stopPropagation(); window.location='{{ route('concepts.show', $concept) }}'">
                                <div class="flex items-center gap-3 mb-1.5 flex-wrap">
                                    <h3 class="text-sm font-medium text-white group-hover:text-indigo-400 transition">{{ $concept->title }}</h3>
                                    <span class="px-2 py-0.5 text-xs rounded-full bg-{{ $concept->difficulty->color() }}-500/20 text-{{ $concept->difficulty->color() }}-400 font-medium flex-shrink-0">
                                        {{ $concept->difficulty->label() }}
                                    </span>
                                    <span class="px-2 py-0.5 text-xs rounded-full bg-{{ $concept->status->color() }}-500/20 text-{{ $concept->status->color() }}-400 font-medium flex-shrink-0">
                                        {{ $concept->status->label() }}
                                    </span>
                                </div>
                                <div class="flex items-center gap-1.5 text-white/30 text-xs">
                                    <svg class="w-3 h-3 flex-shrink-0" style="color: {{ $concept->domain->color }};" fill="currentColor" viewBox="0 0 24 24">
                                        <circle cx="6" cy="12" r="4"/>
                                    </svg>
                                    {{ $concept->domain->name }}
                                </div>
                            </div>
                            <div class="flex items-center gap-1.5 flex-shrink-0 opacity-0 group-hover:opacity-100 transition" onclick="event.stopPropagation();">
                                <a href="{{ route('concepts.edit', $concept) }}" class="p-2 text-white/40 hover:text-indigo-400 hover:bg-white/5 rounded-lg transition" title="Edit">
                                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15.232 5.232l3.536 3.536m-2.036-5.036a2.5 2.5 0 113.536 3.536L6.5 21.036H3v-3.572L16.732 3.732z"/>
                                    </svg>
                                </a>
                                <form action="{{ route('concepts.destroy', $concept) }}" method="POST" onsubmit="return confirm('Move this concept to trash?');">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit" class="p-2 text-white/40 hover:text-red-400 hover:bg-white/5 rounded-lg transition" title="Delete">
                                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"/>
                                        </svg>
                                    </button>
                                </form>
                            </div>
                            <svg class="w-4 h-4 text-white/30 group-hover:text-white/50 group-hover:translate-x-0.5 transition flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24" onclick="event.stopPropagation(); window.location='{{ route('concepts.show', $concept) }}'">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"/>
                            </svg>
                        </div>
                    @endforeach
                </div>

                <div class="mt-6">
                    <div class="bg-white/5 backdrop-blur-sm border border-white/10 rounded-xl p-4">
                        {{ $concepts->withQueryString()->links() }}
                    </div>
                </div>
            @endif
        </div>
    </div>
</x-app-layout>