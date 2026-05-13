<x-app-layout>
    <x-slot:header>
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('My Domains') }}
        </h2>
    </x-slot:header>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            @if (session('success'))
                <div class="mb-4 bg-green-100 border border-green-400 text-green-700 px-4 py-3 rounded">
                    {{ session('success') }}
                </div>
            @endif

            <div class="flex justify-end mb-6">
                <a href="{{ route('domains.create') }}" class="bg-indigo-600 hover:bg-indigo-700 text-white font-bold py-2 px-4 rounded">
                    + {{ __('Create Domain') }}
                </a>
            </div>

            @if ($domains->isEmpty())
                <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg p-8 text-center">
                    <p class="text-gray-500 mb-4">{{ __('You have no domains yet. Create your first one.') }}</p>
                    <a href="{{ route('domains.create') }}" class="inline-block bg-indigo-600 hover:bg-indigo-700 text-white font-bold py-2 px-4 rounded">
                        {{ __('Create Your First Domain') }}
                    </a>
                </div>
            @else
                <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
                    @foreach ($domains as $domain)
                        <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                            <div class="border-l-4" style="border-left-color: {{ $domain->color }};">
                                <div class="p-6">
                                    <div class="flex justify-between items-start">
                                        <h3 class="text-lg font-semibold text-gray-800">{{ $domain->name }}</h3>
                                        <span class="inline-block w-4 h-4 rounded-full" style="background-color: {{ $domain->color }};"></span>
                                    </div>
                                    <p class="text-sm text-gray-500 mt-2">
                                        {{ $domain->concepts_count }} {{ __('concepts') }}
                                    </p>

                                    <div class="mt-4">
                                        <div class="flex justify-between text-xs text-gray-500 mb-1">
                                            <span class="text-green-600">{{ __('Mastered') }} ({{ $domain->masteredCount() }})</span>
                                            <span class="text-yellow-600">{{ __('In Progress') }} ({{ $domain->inProgressCount() }})</span>
                                            <span class="text-blue-600">{{ __('To Review') }} ({{ $domain->toReviewCount() }})</span>
                                        </div>
                                        <div class="w-full bg-gray-200 rounded-full h-2">
                                            @php
                                                $total = $domain->masteredCount() + $domain->inProgressCount() + $domain->toReviewCount();
                                                $masteredPct = $total > 0 ? ($domain->masteredCount() / $total) * 100 : 0;
                                                $inProgressPct = $total > 0 ? ($domain->inProgressCount() / $total) * 100 : 0;
                                            @endphp
                                            <div class="flex h-2 rounded-full overflow-hidden">
                                                <div class="bg-green-500" style="width: {{ $masteredPct }}%;"></div>
                                                <div class="bg-yellow-500" style="width: {{ $inProgressPct }}%;"></div>
                                            </div>
                                        </div>
                                    </div>

                                    <div class="flex justify-end gap-2 mt-4">
                                        <a href="{{ route('domains.edit', $domain) }}" class="text-indigo-600 hover:text-indigo-900 text-sm">{{ __('Edit') }}</a>
                                        <form action="{{ route('domains.destroy', $domain) }}" method="POST" onsubmit="return confirm('{{ __('Are you sure?') }}');">
                                            @csrf
                                            @method('DELETE')
                                            <button type="submit" class="text-red-600 hover:text-red-900 text-sm">{{ __('Delete') }}</button>
                                        </form>
                                    </div>
                                </div>
                            </div>
                        </div>
                    @endforeach
                </div>
            @endif
        </div>
    </div>
</x-app-layout>