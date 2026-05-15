<x-app-layout>
    <x-slot:header>
        <a href="{{ route('domains.index') }}" class="flex items-center gap-2 text-white/70 hover:text-white transition">
            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7"/>
            </svg>
            Back to Domains
        </a>
    </x-slot:header>

    <div class="py-8">
        <div class="max-w-4xl mx-auto">
            <div class="bg-white/5 backdrop-blur-sm border border-white/10 rounded-2xl overflow-hidden mb-8">
                <div class="h-2" style="background-color: {{ $domain->color }};"></div>
                <div class="p-8">
                    <div class="flex items-center justify-between">
                        <div class="flex items-center gap-4">
                            <div class="w-4 h-4 rounded-full" style="background-color: {{ $domain->color }};"></div>
                            <div>
                                <h1 class="text-2xl font-bold text-white">{{ $domain->name }}</h1>
                                <p class="text-white/50 text-sm">{{ $domain->concepts->count() }} concepts</p>
                            </div>
                        </div>
                        <div class="flex gap-2">
                            <a href="{{ route('domains.edit', $domain) }}" class="px-4 py-2 bg-white/10 hover:bg-white/20 text-white/70 hover:text-white rounded-lg transition text-sm">
                                Edit
                            </a>
                            <form action="{{ route('domains.destroy', $domain) }}" method="POST" onsubmit="return confirm('Are you sure you want to delete this domain? All concepts will be deleted.');">
                                @csrf
                                @method('DELETE')
                                <button type="submit" class="px-4 py-2 bg-red-500/20 hover:bg-red-500/30 text-red-400 rounded-lg transition text-sm">
                                    Delete
                                </button>
                            </form>
                        </div>
                    </div>
                </div>
            </div>

            <div class="flex items-center justify-between mb-6">
                <h2 class="text-xl font-semibold text-white">Concepts</h2>
                <a href="{{ route('concepts.create') }}" class="flex items-center gap-2 px-4 py-2 bg-indigo-600 hover:bg-indigo-700 text-white rounded-lg font-medium transition text-sm">
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"/>
                    </svg>
                    Add Concept
                </a>
            </div>

            @if ($domain->concepts->isEmpty())
                <div class="bg-white/5 backdrop-blur-sm border border-white/10 rounded-2xl p-12 text-center">
                    <p class="text-white/50">No concepts in this domain yet. Add your first concept to get started.</p>
                </div>
            @else
                <div class="space-y-4">
                    @foreach ($domain->concepts as $concept)
                        <a href="{{ route('concepts.show', $concept) }}" class="block bg-white/5 backdrop-blur-sm border border-white/10 rounded-xl p-4 hover:bg-white/10 transition">
                            <div class="flex items-center justify-between">
                                <div class="flex items-center gap-4">
                                    <h3 class="font-semibold text-white">{{ $concept->title }}</h3>
                                    <span class="px-2 py-1 text-xs rounded-full bg-{{ $concept->difficulty->color() }}-500/20 text-{{ $concept->difficulty->color() }}-400">
                                        {{ $concept->difficulty->label() }}
                                    </span>
                                    <span class="px-2 py-1 text-xs rounded-full bg-{{ $concept->status->color() }}-500/20 text-{{ $concept->status->color() }}-400">
                                        {{ $concept->status->label() }}
                                    </span>
                                </div>
                                <svg class="w-5 h-5 text-white/30" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"/>
                                </svg>
                            </div>
                        </a>
                    @endforeach
                </div>
            @endif
        </div>
    </div>
</x-app-layout>