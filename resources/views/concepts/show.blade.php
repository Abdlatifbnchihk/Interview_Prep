<x-app-layout>
    <x-slot:header>
        <a href="{{ route('concepts.index') }}" class="flex items-center gap-2 text-white/70 hover:text-white transition">
            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7"/>
            </svg>
            Back to Concepts
        </a>
    </x-slot:header>

    <div class="py-8">
        <div class="max-w-4xl mx-auto">
            <div class="bg-white/5 backdrop-blur-sm border border-white/10 rounded-2xl p-8 mb-8">
                <div class="flex items-start justify-between mb-6">
                    <div>
                        <h1 class="text-2xl font-bold text-white mb-3">{{ $concept->title }}</h1>
                        <div class="flex items-center gap-3">
                            <span class="px-3 py-1 text-sm rounded-full bg-{{ $concept->difficulty->color() }}-500/20 text-{{ $concept->difficulty->color() }}-400">
                                {{ $concept->difficulty->label() }}
                            </span>
                            <span class="px-3 py-1 text-sm rounded-full bg-{{ $concept->status->color() }}-500/20 text-{{ $concept->status->color() }}-400">
                                {{ $concept->status->label() }}
                            </span>
                            <a href="{{ route('domains.show', $concept->domain) }}" class="text-white/50 hover:text-white transition text-sm flex items-center gap-1">
                                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 11H5m14 0a2 2 0 012 2v6a2 2 0 01-2 2H5a2 2 0 01-2-2v-6a2 2 0 012-2m14 0V9a2 2 0 00-2-2M5 11V9a2 2 0 012-2m0 0V5a2 2 0 012-2h6a2 2 0 012 2v2M7 7h10"/>
                                </svg>
                                {{ $concept->domain->name }}
                            </a>
                        </div>
                    </div>
                    <div class="flex gap-2">
                        <a href="{{ route('concepts.edit', $concept) }}" class="px-4 py-2 bg-white/10 hover:bg-white/20 text-white/70 hover:text-white rounded-lg transition text-sm">
                            Edit
                        </a>
                        <form action="{{ route('concepts.destroy', $concept) }}" method="POST" onsubmit="return confirm('Are you sure?');">
                            @csrf
                            @method('DELETE')
                            <button type="submit" class="px-4 py-2 bg-red-500/20 hover:bg-red-500/30 text-red-400 rounded-lg transition text-sm">
                                Delete
                            </button>
                        </form>
                    </div>
                </div>

                <div>
                    <h3 class="text-lg font-semibold text-white mb-3">Explanation</h3>
                    <p class="text-white/70 whitespace-pre-wrap leading-relaxed">{{ $concept->explanation }}</p>
                </div>
            </div>

            <div class="bg-gradient-to-br from-indigo-500/10 to-purple-500/10 backdrop-blur-sm border border-indigo-500/20 rounded-2xl p-8">
                <div class="flex items-center justify-between mb-4">
                    <div class="flex items-center gap-3">
                        <div class="w-10 h-10 bg-indigo-500/20 rounded-xl flex items-center justify-center">
                            <svg class="w-5 h-5 text-indigo-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9.663 17h4.673M12 3v1m6.364 1.636l-.707.707m16.364 12.364l.707.707M21 12h-1M4 12H3m3.343-5.657l-.707-.707m2.828 9.9a5 5 0 117.072 0l-.548.547A3.374 3.374 0 0014 18.469V19a2 2 0 11-4 0v-.531c0-.895-.356-1.754-.988-2.386l-.548-.547z"/>
                            </svg>
                        </div>
                        <div>
                            <h3 class="text-xl font-semibold text-white">Question Generations</h3>
                            <p class="text-white/50 text-sm">AI-powered interview questions</p>
                        </div>
                    </div>
                    <a href="{{ route('generations.index', $concept) }}" class="flex items-center gap-2 px-6 py-3 bg-indigo-600 hover:bg-indigo-700 text-white rounded-xl font-medium transition">
                        View Generations
                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"/>
                        </svg>
                    </a>
                </div>
            </div>
        </div>
    </div>
</x-app-layout>