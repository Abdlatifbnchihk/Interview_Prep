<x-app-layout>
    <div class="pt-20">
        <div class="pt-4 pb-0">
            <a href="{{ route('concepts.index') }}" class="inline-flex items-center gap-2 text-white/50 hover:text-white transition text-sm mb-4">
                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7"/>
                </svg>
                Back to Concepts
            </a>

            <div class="flex items-start justify-between">
                <div>
                    <h1 class="text-2xl font-bold text-white mb-3">{{ $concept->title }}</h1>
                    <div class="flex items-center gap-2 py-5">
                        <span class="px-2.5 py-1 text-xs rounded-full bg-{{ $concept->difficulty->color() }}-500/20 text-{{ $concept->difficulty->color() }}-400 font-medium">
                            {{ $concept->difficulty->label() }}
                        </span>
                        <span class="px-2.5 py-1 text-xs rounded-full bg-{{ $concept->status->color() }}-500/20 text-{{ $concept->status->color() }}-400 font-medium">
                            {{ $concept->status->label() }}
                        </span>
                        <a href="{{ route('domains.show', $concept->domain) }}" class="text-white/40 hover:text-white transition text-xs flex items-center gap-1.5">
                            <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 11H5m14 0a2 2 0 012 2v6a2 2 0 01-2 2H5a2 2 0 01-2-2v-6a2 2 0 012-2m14 0V9a2 2 0 00-2-2M5 11V9a2 2 0 012-2m0 0V5a2 2 0 012-2h6a2 2 0 012 2v2M7 7h10"/>
                            </svg>
                            {{ $concept->domain->name }}
                        </a>
                    </div>
                </div>
                <div class="flex gap-2">
                    <a href="{{ route('concepts.edit', $concept) }}" class="p-2.5 bg-white/5 hover:bg-white/10 border border-white/10 text-white/60 hover:text-white rounded-xl transition">
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15.232 5.232l3.536 3.536m-2.036-5.036a2.5 2.5 0 113.536 3.536L6.5 21.036H3v-3.572L16.732 3.732z"/>
                        </svg>
                    </a>
                    <form action="{{ route('concepts.destroy', $concept) }}" method="POST" onsubmit="return confirm('Are you sure?');">
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

        <div class="pb-8">
            <div class="bg-white/5 backdrop-blur-sm border border-white/10 rounded-2xl p-6 mb-5">
                <h3 class="text-sm font-semibold text-white/50 uppercase tracking-wide mb-3">Explanation</h3>
                <p class="text-white/80 whitespace-pre-wrap leading-relaxed">{{ $concept->explanation }}</p>
            </div>

            <div class="bg-gradient-to-br from-indigo-500/10 to-purple-500/10 backdrop-blur-sm border border-indigo-500/20 rounded-2xl p-5">
                <div class="flex items-center justify-between mb-4">
                    <div class="flex items-center gap-3">
                        <div class="w-10 h-10 bg-indigo-500/20 rounded-xl flex items-center justify-center">
                            <svg class="w-5 h-5 text-indigo-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9.663 17h4.673M12 3v1m6.364 1.636l-.707.707m16.364 12.364l.707.707M21 12h-1M4 12H3m3.343-5.657l-.707-.707m2.828 9.9a5 5 0 117.072 0l-.548.547A3.374 3.374 0 0014 18.469V19a2 2 0 11-4 0v-.531c0-.895-.356-1.754-.988-2.386l-.548-.547z"/>
                            </svg>
                        </div>
                        <div>
                            <h3 class="text-base font-semibold text-white">AI Question Generation</h3>
                            <p class="text-white/40 text-xs">Generate 5 interview questions using Groq AI</p>
                        </div>
                    </div>
                    <a href="{{ route('generations.index', $concept) }}" class="flex items-center gap-2 px-4 py-2.5 bg-indigo-600 hover:bg-indigo-700 text-white rounded-xl font-medium transition text-sm shadow-lg shadow-indigo-500/20">
                        View Generations
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"/>
                        </svg>
                    </a>
                </div>
            </div>
        </div>
    </div>
</x-app-layout>