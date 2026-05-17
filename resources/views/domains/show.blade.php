<x-app-layout>
    <div class="pt-20">
        <div class="pt-4 pb-8">
            <div class="mb-5">
                <a href="{{ route('domains.index') }}" class="inline-flex items-center gap-2 text-white/50 hover:text-white transition text-sm mb-4">
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7"/>
                    </svg>
                    Back to Domains
                </a>

                <div class="flex items-center justify-between">
                    <div class="flex items-center gap-3">
                        <div class="w-4 h-4 rounded-full shadow-lg" style="background-color: {{ $domain->color }};"></div>
                        <div>
                            <h1 class="text-2xl font-bold text-white">{{ $domain->name }}</h1>
                            <p class="text-white/40 text-sm">{{ $domain->concepts->count() }} concepts</p>
                        </div>
                    </div>
                    <div class="flex items-center gap-2">
                        <a href="{{ route('domains.edit', $domain) }}" class="p-2.5 bg-white/5 hover:bg-white/10 border border-white/10 text-white/60 hover:text-white rounded-xl transition">
                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15.232 5.232l3.536 3.536m-2.036-5.036a2.5 2.5 0 113.536 3.536L6.5 21.036H3v-3.572L16.732 3.732z"/>
                            </svg>
                        </a>
                        <form action="{{ route('domains.destroy', $domain) }}" method="POST" onsubmit="return confirm('Are you sure you want to delete this domain? All concepts will be deleted.');">
                            @csrf
                            @method('DELETE')
                            <button type="submit" class="p-2.5 bg-red-500/10 hover:bg-red-500/20 border border-red-500/20 text-red-400 rounded-xl transition">
                                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"/>
                                </svg>
                            </button>
                        </form>
                    </div>
                </div>
            </div>
        </div>

        <div class="pb-8">
            <div class="flex items-center justify-between mb-5">
                <h2 class="text-base font-semibold text-white/70">Concepts</h2>
                <a href="{{ route('concepts.create', ['domain_id' => $domain->id]) }}" class="flex items-center gap-2 px-4 py-2 bg-indigo-600 hover:bg-indigo-700 text-white rounded-xl font-medium transition text-sm shadow-lg shadow-indigo-500/20">
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"/>
                    </svg>
                    Add Concept
                </a>
            </div>

            @if ($domain->concepts->isEmpty())
                <div class="bg-white/5 backdrop-blur-sm border border-white/10 rounded-2xl p-12 text-center">
                    <p class="text-white/40">No concepts in this domain yet.</p>
                </div>
            @else
                <div class="space-y-2">
                    @foreach ($domain->concepts as $concept)
                        <a href="{{ route('concepts.show', $concept) }}" class="flex items-center gap-4 bg-white/5 backdrop-blur-sm border border-white/10 rounded-xl px-4 py-3.5 hover:bg-white/10 hover:border-white/20 transition group">
                            <div class="w-2 h-2 rounded-full bg-{{ $concept->status->color() }}-500 flex-shrink-0"></div>
                            <div class="flex-1 min-w-0">
                                <div class="flex items-center gap-3 mb-1">
                                    <h3 class="text-sm font-medium text-white truncate">{{ $concept->title }}</h3>
                                    <span class="px-2 py-0.5 text-xs rounded-full bg-{{ $concept->difficulty->color() }}-500/20 text-{{ $concept->difficulty->color() }}-400 flex-shrink-0">
                                        {{ $concept->difficulty->label() }}
                                    </span>
                                    <span class="px-2 py-0.5 text-xs rounded-full bg-{{ $concept->status->color() }}-500/20 text-{{ $concept->status->color() }}-400 flex-shrink-0">
                                        {{ $concept->status->label() }}
                                    </span>
                                </div>
                            </div>
                            <svg class="w-4 h-4 text-white/30 group-hover:text-white/50 transition flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"/>
                            </svg>
                        </a>
                    @endforeach
                </div>
            @endif
        </div>
    </div>
</x-app-layout>