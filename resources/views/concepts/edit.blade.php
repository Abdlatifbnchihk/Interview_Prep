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
        <div class="max-w-2xl mx-auto">
            <div class="bg-white/5 backdrop-blur-sm border border-white/10 rounded-2xl p-8">
                <h1 class="text-2xl font-bold text-white mb-8">Edit Concept</h1>

                <form method="POST" action="{{ route('concepts.update', $concept) }}">
                    @csrf
                    @method('PUT')

                    <div class="mb-6">
                        <label for="domain_id" class="block text-sm font-medium text-white/70 mb-2">Domain</label>
                        <select id="domain_id" name="domain_id" required
                            class="w-full px-4 py-3 bg-white/5 border border-white/10 rounded-xl text-white focus:outline-none focus:border-indigo-500 transition">
                            @foreach ($domains as $domain)
                                <option value="{{ $domain->id }}" {{ $concept->domain_id == $domain->id ? 'selected' : '' }} class="text-gray-800">
                                    {{ $domain->name }}
                                </option>
                            @endforeach
                        </select>
                        @error('domain_id')
                            <p class="mt-2 text-sm text-red-400">{{ $message }}</p>
                        @enderror
                    </div>

                    <div class="mb-6">
                        <label for="title" class="block text-sm font-medium text-white/70 mb-2">Title</label>
                        <input type="text" id="title" name="title" value="{{ old('title', $concept->title) }}" required autofocus
                            class="w-full px-4 py-3 bg-white/5 border border-white/10 rounded-xl text-white placeholder-white/30 focus:outline-none focus:border-indigo-500 transition">
                        @error('title')
                            <p class="mt-2 text-sm text-red-400">{{ $message }}</p>
                        @enderror
                    </div>

                    <div class="mb-6">
                        <label for="explanation" class="block text-sm font-medium text-white/70 mb-2">Explanation</label>
                        <textarea id="explanation" name="explanation" rows="5" required
                            class="w-full px-4 py-3 bg-white/5 border border-white/10 rounded-xl text-white placeholder-white/30 focus:outline-none focus:border-indigo-500 transition resize-none">{{ old('explanation', $concept->explanation) }}</textarea>
                        @error('explanation')
                            <p class="mt-2 text-sm text-red-400">{{ $message }}</p>
                        @enderror
                    </div>

                    <div class="grid grid-cols-2 gap-4 mb-8">
                        <div>
                            <label for="difficulty" class="block text-sm font-medium text-white/70 mb-2">Difficulty</label>
                            <select id="difficulty" name="difficulty" required
                                class="w-full px-4 py-3 bg-white/5 border border-white/10 rounded-xl text-white focus:outline-none focus:border-indigo-500 transition">
                                @foreach (\App\Enums\ConceptDifficulty::cases() as $diff)
                                    <option value="{{ $diff->value }}" {{ $concept->difficulty === $diff ? 'selected' : '' }} class="text-gray-800">
                                        {{ $diff->label() }}
                                    </option>
                                @endforeach
                            </select>
                            @error('difficulty')
                                <p class="mt-2 text-sm text-red-400">{{ $message }}</p>
                            @enderror
                        </div>
                        <div>
                            <label for="status" class="block text-sm font-medium text-white/70 mb-2">Status</label>
                            <select id="status" name="status" required
                                class="w-full px-4 py-3 bg-white/5 border border-white/10 rounded-xl text-white focus:outline-none focus:border-indigo-500 transition">
                                @foreach (\App\Enums\ConceptStatus::cases() as $status)
                                    <option value="{{ $status->value }}" {{ $concept->status === $status ? 'selected' : '' }} class="text-gray-800">
                                        {{ $status->label() }}
                                    </option>
                                @endforeach
                            </select>
                            @error('status')
                                <p class="mt-2 text-sm text-red-400">{{ $message }}</p>
                            @enderror
                        </div>
                    </div>

                    <div class="flex gap-4">
                        <a href="{{ route('concepts.show', $concept) }}" class="flex-1 px-6 py-3 text-center text-white/70 hover:text-white bg-white/5 hover:bg-white/10 rounded-xl transition">
                            Cancel
                        </a>
                        <button type="submit" class="flex-1 px-6 py-3 bg-indigo-600 hover:bg-indigo-700 text-white rounded-xl font-medium transition">
                            Update Concept
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</x-app-layout>