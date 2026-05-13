<x-app-layout>
    <x-slot:header>
        <div class="flex justify-between items-center">
            <h2 class="font-semibold text-xl text-gray-800 leading-tight">
                {{ $domain->name }}
            </h2>
            <div class="flex gap-2">
                <a href="{{ route('domains.edit', $domain) }}" class="text-indigo-600 hover:text-indigo-900">{{ __('Edit') }}</a>
                <form action="{{ route('domains.destroy', $domain) }}" method="POST" onsubmit="return confirm('{{ __('Are you sure?') }}');">
                    @csrf
                    @method('DELETE')
                    <button type="submit" class="text-red-600 hover:text-red-900">{{ __('Delete') }}</button>
                </form>
            </div>
        </div>
    </x-slot:header>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg p-6 mb-6">
                <div class="flex items-center gap-3">
                    <span class="inline-block w-6 h-6 rounded-full" style="background-color: {{ $domain->color }};"></span>
                    <div>
                        <h3 class="text-lg font-semibold">{{ $domain->name }}</h3>
                        <p class="text-sm text-gray-500">{{ $domain->concepts->count() }} {{ __('concepts') }}</p>
                    </div>
                </div>
            </div>

            @if ($domain->concepts->isEmpty())
                <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg p-8 text-center">
                    <p class="text-gray-500">{{ __('No concepts in this domain yet.') }}</p>
                </div>
            @else
                <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                    <ul class="divide-y divide-gray-200">
                        @foreach ($domain->concepts as $concept)
                            <li class="p-4 flex justify-between items-center">
                                <span class="text-gray-800">{{ $concept->name ?? 'Concept' }}</span>
                            </li>
                        @endforeach
                    </ul>
                </div>
            @endif
        </div>
    </div>
</x-app-layout>