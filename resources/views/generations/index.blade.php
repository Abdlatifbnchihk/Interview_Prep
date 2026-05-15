<x-app-layout>
    <x-slot:header>
        <div class="flex justify-between items-center">
            <a href="{{ route('concepts.show', $concept) }}" class="flex items-center gap-2 text-white/70 hover:text-white transition">
                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7"/>
                </svg>
                Back to {{ $concept->title }}
            </a>
        </div>
    </x-slot:header>

    <div class="py-8">
        <div class="max-w-4xl mx-auto">
            @if (session('success'))
                <div class="mb-6 p-4 bg-emerald-500/20 border border-emerald-500/50 rounded-xl text-emerald-400">
                    {{ session('success') }}
                </div>
            @endif

            @if (session('error'))
                <div class="mb-6 p-4 bg-red-500/20 border border-red-500/50 rounded-xl text-red-400">
                    {{ session('error') }}
                </div>
            @endif

            <div class="bg-gradient-to-br from-indigo-500/10 to-purple-500/10 backdrop-blur-sm border border-indigo-500/20 rounded-2xl p-6 mb-8">
                <form method="POST" action="{{ route('generations.store', $concept) }}">
                    @csrf
                    <div class="flex items-center justify-between">
                        <div class="flex items-center gap-3">
                            <div class="w-12 h-12 bg-indigo-500/20 rounded-xl flex items-center justify-center">
                                <svg class="w-6 h-6 text-indigo-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9.663 17h4.673M12 3v1m6.364 1.636l-.707.707m16.364 12.364l.707.707M21 12h-1M4 12H3m3.343-5.657l-.707-.707m2.828 9.9a5 5 0 117.072 0l-.548.547A3.374 3.374 0 0014 18.469V19a2 2 0 11-4 0v-.531c0-.895-.356-1.754-.988-2.386l-.548-.547z"/>
                                </svg>
                            </div>
                            <div>
                                <h3 class="text-lg font-semibold text-white">Generate New Questions</h3>
                                <p class="text-white/50 text-sm">AI will generate 5 interview questions</p>
                            </div>
                        </div>
                        <button type="submit" class="px-6 py-3 bg-indigo-600 hover:bg-indigo-700 text-white rounded-xl font-medium transition flex items-center gap-2">
                            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 10V3L4 14h7v7l9-11h-7z"/>
                            </svg>
                            Generate Questions
                        </button>
                    </div>
                </form>
            </div>

            <h2 class="text-xl font-semibold text-white/90 mb-6">Generation History</h2>

            @if ($concept->generations->isEmpty())
                <div class="bg-white/5 backdrop-blur-sm border border-white/10 rounded-2xl p-12 text-center">
                    <div class="w-16 h-16 mx-auto mb-4 bg-indigo-500/20 rounded-full flex items-center justify-center">
                        <svg class="w-8 h-8 text-indigo-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9.663 17h4.673M12 3v1m6.364 1.636l-.707.707m16.364 12.364l.707.707M21 12h-1M4 12H3m3.343-5.657l-.707-.707m2.828 9.9a5 5 0 117.072 0l-.548.547A3.374 3.374 0 0014 18.469V19a2 2 0 11-4 0v-.531c0-.895-.356-1.754-.988-2.386l-.548-.547z"/>
                        </svg>
                    </div>
                    <h3 class="text-xl font-semibold text-white mb-2">No questions generated yet</h3>
                    <p class="text-white/50">Click "Generate Questions" to start</p>
                </div>
            @else
                <div class="space-y-6">
                    @foreach ($concept->generations->sortByDesc('created_at') as $generation)
                        <div class="bg-white/5 backdrop-blur-sm border border-white/10 rounded-2xl p-6">
                            <div class="flex justify-between items-center mb-4">
                                <div class="flex items-center gap-2 text-white/50 text-sm">
                                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"/>
                                    </svg>
                                    Generated on {{ $generation->created_at->format('Y-m-d H:i:s') }}
                                </div>
                                <form action="{{ route('generations.destroy', $generation) }}" method="POST" onsubmit="return confirm('Are you sure you want to delete this generation?');">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit" class="px-4 py-2 bg-red-500/20 hover:bg-red-500/30 text-red-400 rounded-lg transition text-sm">
                                        Delete
                                    </button>
                                </form>
                            </div>

                            <ul class="space-y-3">
                                @foreach ($generation->questions as $index => $question)
                                    <li class="flex items-start gap-3">
                                        <span class="flex-shrink-0 w-6 h-6 bg-indigo-500/20 text-indigo-400 rounded-full flex items-center justify-center text-sm font-medium">
                                            {{ $index + 1 }}
                                        </span>
                                        <span class="text-white/80">{{ $question->question }}</span>
                                    </li>
                                @endforeach
                            </ul>
                        </div>
                    @endforeach
                </div>
            @endif
        </div>
    </div>
</x-app-layout>