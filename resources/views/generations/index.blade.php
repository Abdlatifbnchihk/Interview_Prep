<x-app-layout>
    <x-slot:header>
        <div class="flex justify-between items-center">
            <h2 class="font-semibold text-xl text-gray-800 leading-tight">
                {{ __('Question Generations') }} — {{ $concept->title }}
            </h2>
            <div class="flex gap-2">
                <a href="{{ route('concepts.show', $concept) }}" class="text-indigo-600 hover:text-indigo-900">← {{ __('Back to Concept') }}</a>
            </div>
        </div>
    </x-slot:header>

    <div class="py-12">
        <div class="max-w-4xl mx-auto sm:px-6 lg:px-8">
            @if (session('success'))
                <div class="mb-4 bg-green-100 border border-green-400 text-green-700 px-4 py-3 rounded">
                    {{ session('success') }}
                </div>
            @endif

            @if (session('error'))
                <div class="mb-4 bg-red-100 border border-red-400 text-red-700 px-4 py-3 rounded">
                    {{ session('error') }}
                </div>
            @endif

            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg p-6 mb-6">
                <form method="POST" action="{{ route('generations.store', $concept) }}">
                    @csrf
                    <div class="flex items-center justify-between">
                        <div>
                            <h3 class="text-lg font-semibold text-gray-800">{{ __('Generate New Questions') }}</h3>
                            <p class="text-sm text-gray-500 mt-1">{{ __('AI will generate 5 interview questions for this concept.') }}</p>
                        </div>
                        <x-primary-button>{{ __('Generate Questions') }}</x-primary-button>
                    </div>
                </form>
            </div>

            <h3 class="text-lg font-semibold text-gray-800 mb-4">{{ __('Generation History') }}</h3>

            @if ($concept->generations->isEmpty())
                <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg p-8 text-center">
                    <p class="text-gray-500">{{ __('No questions generated yet. Click "Generate Questions" to start.') }}</p>
                </div>
            @else
                <div class="space-y-6">
                    @foreach ($concept->generations->sortByDesc('created_at') as $generation)
                        <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg p-6">
                            <div class="flex justify-between items-center mb-4">
                                <div class="text-sm text-gray-500">
                                    {{ __('Generated on') }}: {{ $generation->created_at->format('Y-m-d H:i:s') }}
                                </div>
                                <form action="{{ route('generations.destroy', $generation) }}" method="POST" onsubmit="return confirm('{{ __('Are you sure you want to delete this generation?') }}');">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit" class="text-red-600 hover:text-red-900 text-sm">{{ __('Delete Generation') }}</button>
                                </form>
                            </div>

                            <ul class="list-decimal list-inside space-y-2">
                                @foreach ($generation->questions as $index => $question)
                                    <li class="text-gray-700">{{ $question->question }}</li>
                                @endforeach
                            </ul>
                        </div>
                    @endforeach
                </div>
            @endif
        </div>
    </div>
</x-app-layout>