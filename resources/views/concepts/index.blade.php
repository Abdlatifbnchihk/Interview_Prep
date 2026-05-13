<x-app-layout>
    <x-slot:header>
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('My Concepts') }}
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
                <a href="{{ route('concepts.create') }}" class="bg-indigo-600 hover:bg-indigo-700 text-white font-bold py-2 px-4 rounded">
                    + {{ __('Create Concept') }}
                </a>
            </div>

            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg p-4 mb-6">
                <form method="GET" action="{{ route('concepts.index') }}" class="flex flex-wrap gap-4 items-end">
                    <div>
                        <x-input-label for="domain_id" :value="__('Domain')" />
                        <select name="domain_id" class="border-gray-300 rounded-md mt-1">
                            <option value="">{{ __('All Domains') }}</option>
                            @foreach ($domains as $domain)
                                <option value="{{ $domain->id }}" {{ request('domain_id') == $domain->id ? 'selected' : '' }}>
                                    {{ $domain->name }}
                                </option>
                            @endforeach
                        </select>
                    </div>
                    <div>
                        <x-input-label for="status" :value="__('Status')" />
                        <select name="status" class="border-gray-300 rounded-md mt-1">
                            <option value="">{{ __('All Statuses') }}</option>
                            @foreach (\App\Enums\ConceptStatus::cases() as $status)
                                <option value="{{ $status->value }}" {{ request('status') == $status->value ? 'selected' : '' }}>
                                    {{ $status->label() }}
                                </option>
                            @endforeach
                        </select>
                    </div>
                    <div>
                        <x-input-label for="difficulty" :value="__('Difficulty')" />
                        <select name="difficulty" class="border-gray-300 rounded-md mt-1">
                            <option value="">{{ __('All Difficulties') }}</option>
                            @foreach (\App\Enums\ConceptDifficulty::cases() as $diff)
                                <option value="{{ $diff->value }}" {{ request('difficulty') == $diff->value ? 'selected' : '' }}>
                                    {{ $diff->label() }}
                                </option>
                            @endforeach
                        </select>
                    </div>
                    <x-primary-button>{{ __('Filter') }}</x-primary-button>
                </form>
            </div>

            @if ($concepts->isEmpty())
                <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg p-8 text-center">
                    @if (request()->hasAny(['domain_id', 'status', 'difficulty']))
                        <p class="text-gray-500 mb-4">{{ __('No concepts found for the selected filters.') }}</p>
                    @else
                        <p class="text-gray-500 mb-4">{{ __('You have no concepts yet. Start by creating one.') }}</p>
                    @endif
                    <a href="{{ route('concepts.create') }}" class="inline-block bg-indigo-600 hover:bg-indigo-700 text-white font-bold py-2 px-4 rounded">
                        {{ __('Create Your First Concept') }}
                    </a>
                </div>
            @else
                <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                    <ul class="divide-y divide-gray-200">
                        @foreach ($concepts as $concept)
                            <li class="p-4 flex items-center justify-between">
                                <div class="flex-1">
                                    <div class="flex items-center gap-2 mb-1">
                                        <span class="text-lg font-semibold text-gray-800">{{ $concept->title }}</span>
                                        <span class="px-2 py-0.5 text-xs rounded-full bg-{{ $concept->difficulty->color() }}-100 text-{{ $concept->difficulty->color() }}-800">
                                            {{ $concept->difficulty->label() }}
                                        </span>
                                        <span class="px-2 py-0.5 text-xs rounded-full bg-{{ $concept->status->color() }}-100 text-{{ $concept->status->color() }}-800">
                                            {{ $concept->status->label() }}
                                        </span>
                                    </div>
                                    <p class="text-sm text-gray-500">
                                        {{ __('Domain') }}: <span class="font-medium">{{ $concept->domain->name }}</span>
                                    </p>
                                </div>
                                <div class="flex items-center gap-4">
                                    <form action="{{ route('concepts.updateStatus', $concept) }}" method="POST" class="flex items-center gap-2">
                                        @csrf
                                        @method('PATCH')
                                        <select name="status" onchange="this.form.submit()" class="text-sm border-gray-300 rounded-md">
                                            @foreach (\App\Enums\ConceptStatus::cases() as $status)
                                                <option value="{{ $status->value }}" {{ $concept->status === $status ? 'selected' : '' }}>
                                                    {{ $status->label() }}
                                                </option>
                                            @endforeach
                                        </select>
                                    </form>
                                    <a href="{{ route('concepts.show', $concept) }}" class="text-indigo-600 hover:text-indigo-900 text-sm">{{ __('View') }}</a>
                                    <a href="{{ route('concepts.edit', $concept) }}" class="text-indigo-600 hover:text-indigo-900 text-sm">{{ __('Edit') }}</a>
                                    <form action="{{ route('concepts.destroy', $concept) }}" method="POST" onsubmit="return confirm('{{ __('Are you sure?') }}');">
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit" class="text-red-600 hover:text-red-900 text-sm">{{ __('Delete') }}</button>
                                    </form>
                                </div>
                            </li>
                        @endforeach
                    </ul>
                </div>
                <div class="mt-4">
                    {{ $concepts->withQueryString()->links() }}
                </div>
            @endif
        </div>
    </div>
</x-app-layout>