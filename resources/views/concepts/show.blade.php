<x-app-layout>
    <x-slot:header>
        <div class="flex justify-between items-center">
            <h2 class="font-semibold text-xl text-gray-800 leading-tight">
                {{ $concept->title }}
            </h2>
            <div class="flex gap-2">
                <a href="{{ route('concepts.edit', $concept) }}" class="text-indigo-600 hover:text-indigo-900">{{ __('Edit') }}</a>
                <form action="{{ route('concepts.destroy', $concept) }}" method="POST" onsubmit="return confirm('{{ __('Are you sure?') }}');">
                    @csrf
                    @method('DELETE')
                    <button type="submit" class="text-red-600 hover:text-red-900">{{ __('Delete') }}</button>
                </form>
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

            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg p-6 mb-6">
                <div class="flex items-center gap-3 mb-4">
                    <span class="px-2 py-0.5 text-xs rounded-full bg-{{ $concept->difficulty->color() }}-100 text-{{ $concept->difficulty->color() }}-800">
                        {{ $concept->difficulty->label() }}
                    </span>
                    <span class="px-2 py-0.5 text-xs rounded-full bg-{{ $concept->status->color() }}-100 text-{{ $concept->status->color() }}-800">
                        {{ $concept->status->label() }}
                    </span>
                    <span class="text-sm text-gray-500">
                        {{ __('Domain') }}: <a href="{{ route('domains.show', $concept->domain) }}" class="text-indigo-600 hover:text-indigo-900">{{ $concept->domain->name }}</a>
                    </span>
                </div>

                <h3 class="text-lg font-semibold text-gray-800 mb-2">{{ __('Explanation') }}</h3>
                <div class="prose text-gray-700 whitespace-pre-wrap">{{ $concept->explanation }}</div>
            </div>

            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg p-6">
                <div class="flex justify-between items-center mb-4">
                    <h3 class="text-lg font-semibold text-gray-800">{{ __('Question Generations') }}</h3>
                    <a href="{{ route('generations.index', $concept) }}" class="text-indigo-600 hover:text-indigo-900 font-semibold">
                        {{ __('View Generations') }} →
                    </a>
                </div>
                <p class="text-gray-500">{{ __('Generate AI-powered interview questions for this concept.') }}</p>
            </div>
        </div>
    </div>
</x-app-layout>