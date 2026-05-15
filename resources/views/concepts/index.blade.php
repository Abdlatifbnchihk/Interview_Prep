<x-app-layout>
    <x-slot:header>
        <div class="flex items-center justify-between">
            <h1 class="text-2xl font-bold text-white">My Concepts</h1>
            <a href="{{ route('concepts.create') }}" class="flex items-center gap-2 px-4 py-2 bg-indigo-600 hover:bg-indigo-700 text-white rounded-lg font-medium transition">
                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"/>
                </svg>
                Create Concept
            </a>
        </div>
    </x-slot:header>

    <div class="py-8">
        @if (session('success'))
            <div class="mb-6 p-4 bg-emerald-500/20 border border-emerald-500/50 rounded-xl text-emerald-400">
                {{ session('success') }}
            </div>
        @endif

        <div class="bg-white/5 backdrop-blur-sm border border-white/10 rounded-2xl p-4 mb-6">
            <form method="GET" action="{{ route('concepts.index') }}" class="flex flex-wrap gap-4 items-end">
                <div class="flex-1 min-w-[150px]">
                    <label class="block text-sm text-white/70 mb-2">Domain</label>
                    <select name="domain_id" class="w-full px-3 py-2 bg-white/5 border border-white/10 rounded-lg text-white">
                        <option value="" class="text-gray-800">All Domains</option>
                        @foreach ($domains as $domain)
                            <option value="{{ $domain->id }}" {{ request('domain_id') == $domain->id ? 'selected' : '' }} class="text-gray-800">
                                {{ $domain->name }}
                            </option>
                        @endforeach
                    </select>
                </div>
                <div class="flex-1 min-w-[150px]">
                    <label class="block text-sm text-white/70 mb-2">Status</label>
                    <select name="status" class="w-full px-3 py-2 bg-white/5 border border-white/10 rounded-lg text-white">
                        <option value="" class="text-gray-800">All Statuses</option>
                        @foreach (\App\Enums\ConceptStatus::cases() as $status)
                            <option value="{{ $status->value }}" {{ request('status') == $status->value ? 'selected' : '' }} class="text-gray-800">
                                {{ $status->label() }}
                            </option>
                        @endforeach
                    </select>
                </div>
                <div class="flex-1 min-w-[150px]">
                    <label class="block text-sm text-white/70 mb-2">Difficulty</label>
                    <select name="difficulty" class="w-full px-3 py-2 bg-white/5 border border-white/10 rounded-lg text-white">
                        <option value="" class="text-gray-800">All Difficulties</option>
                        @foreach (\App\Enums\ConceptDifficulty::cases() as $diff)
                            <option value="{{ $diff->value }}" {{ request('difficulty') == $diff->value ? 'selected' : '' }} class="text-gray-800">
                                {{ $diff->label() }}
                            </option>
                        @endforeach
                    </select>
                </div>
                <button type="submit" class="px-6 py-2 bg-indigo-600 hover:bg-indigo-700 text-white rounded-lg font-medium transition">
                    Filter
                </button>
            </form>
        </div>

        @if ($concepts->isEmpty())
            <div class="bg-white/5 backdrop-blur-sm border border-white/10 rounded-2xl p-12 text-center">
                <div class="w-16 h-16 mx-auto mb-4 bg-indigo-500/20 rounded-full flex items-center justify-center">
                    <svg class="w-8 h-8 text-indigo-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
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
                <p class="text-white/50 mb-6">Create your first concept to start preparing for interviews</p>
                <a href="{{ route('concepts.create') }}" class="inline-flex items-center gap-2 px-6 py-3 bg-indigo-600 hover:bg-indigo-700 text-white rounded-lg font-medium transition">
                    Create Your First Concept
                </a>
            </div>
        @else
            <div class="space-y-3">
                @foreach ($concepts as $concept)
                    <div class="bg-white/5 backdrop-blur-sm border border-white/10 rounded-xl p-4 hover:bg-white/10 transition">
                        <div class="flex items-center justify-between">
                            <a href="{{ route('concepts.show', $concept) }}" class="flex-1">
                                <div class="flex items-center gap-3 mb-2">
                                    <h3 class="text-lg font-semibold text-white hover:text-indigo-400 transition">{{ $concept->title }}</h3>
                                    <span class="px-2 py-1 text-xs rounded-full bg-{{ $concept->difficulty->color() }}-500/20 text-{{ $concept->difficulty->color() }}-400">
                                        {{ $concept->difficulty->label() }}
                                    </span>
                                    <span class="px-2 py-1 text-xs rounded-full bg-{{ $concept->status->color() }}-500/20 text-{{ $concept->status->color() }}-400">
                                        {{ $concept->status->label() }}
                                    </span>
                                </div>
                                <p class="text-white/50 text-sm flex items-center gap-2">
                                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 11H5m14 0a2 2 0 012 2v6a2 2 0 01-2 2H5a2 2 0 01-2-2v-6a2 2 0 012-2m14 0V9a2 2 0 00-2-2M5 11V9a2 2 0 012-2m0 0V5a2 2 0 012-2h6a2 2 0 012 2v2M7 7h10"/>
                                    </svg>
                                    {{ $concept->domain->name }}
                                </p>
                            </a>
                            <div class="flex items-center gap-4">
                                <form action="{{ route('concepts.updateStatus', $concept) }}" method="POST" class="flex items-center gap-2">
                                    @csrf
                                    @method('PATCH')
                                    <select name="status" onchange="this.form.submit()" class="px-3 py-1.5 bg-white/5 border border-white/10 rounded-lg text-white text-sm">
                                        @foreach (\App\Enums\ConceptStatus::cases() as $status)
                                            <option value="{{ $status->value }}" {{ $concept->status === $status ? 'selected' : '' }} class="text-gray-800">
                                                {{ $status->label() }}
                                            </option>
                                        @endforeach
                                    </select>
                                </form>
                                <a href="{{ route('concepts.edit', $concept) }}" class="p-2 text-white/50 hover:text-indigo-400 transition">
                                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15.232 5.232l3.536 3.536m-2.036-5.036a2.5 2.5 0 013.536 3.536L6.5 21.036H3v-3.572L16.732 3.732z"/>
                                    </svg>
                                </a>
                                <form action="{{ route('concepts.destroy', $concept) }}" method="POST" onsubmit="return confirm('Are you sure?');">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit" class="p-2 text-white/50 hover:text-red-400 transition">
                                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"/>
                                        </svg>
                                    </button>
                                </form>
                            </div>
                        </div>
                    </div>
                @endforeach
            </div>
            <div class="mt-6">
                {{ $concepts->withQueryString()->links() }}
            </div>
        @endif
    </div>
</x-app-layout>