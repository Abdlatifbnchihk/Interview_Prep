<x-app-layout>
    <x-slot:header>
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('Create Concept') }}
        </h2>
    </x-slot:header>

    <div class="py-12">
        <div class="max-w-2xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg p-6">
                <form method="POST" action="{{ route('concepts.store') }}">
                    @csrf

                    <div class="mb-4">
                        <x-input-label for="domain_id" :value="__('Domain')" />
                        <select id="domain_id" name="domain_id" class="block mt-1 w-full border-gray-300 rounded-md" required>
                            <option value="">{{ __('Select a domain') }}</option>
                            @foreach ($domains as $domain)
                                <option value="{{ $domain->id }}" {{ old('domain_id') == $domain->id ? 'selected' : '' }}>
                                    {{ $domain->name }}
                                </option>
                            @endforeach
                        </select>
                        <x-input-error :messages="$errors->get('domain_id')" class="mt-2" />
                    </div>

                    <div class="mb-4">
                        <x-input-label for="title" :value="__('Title')" />
                        <x-text-input id="title" class="block mt-1 w-full" type="text" name="title" :value="old('title')" required autofocus />
                        <x-input-error :messages="$errors->get('title')" class="mt-2" />
                    </div>

                    <div class="mb-4">
                        <x-input-label for="explanation" :value="__('Explanation')" />
                        <textarea id="explanation" name="explanation" rows="6" class="block mt-1 w-full border-gray-300 rounded-md" required>{{ old('explanation') }}</textarea>
                        <x-input-error :messages="$errors->get('explanation')" class="mt-2" />
                    </div>

                    <div class="mb-4">
                        <x-input-label for="difficulty" :value="__('Difficulty')" />
                        <select id="difficulty" name="difficulty" class="block mt-1 w-full border-gray-300 rounded-md" required>
                            @foreach (\App\Enums\ConceptDifficulty::cases() as $diff)
                                <option value="{{ $diff->value }}" {{ old('difficulty') == $diff->value ? 'selected' : '' }}>
                                    {{ $diff->label() }}
                                </option>
                            @endforeach
                        </select>
                        <x-input-error :messages="$errors->get('difficulty')" class="mt-2" />
                    </div>

                    <div class="mb-4">
                        <x-input-label for="status" :value="__('Status')" />
                        <select id="status" name="status" class="block mt-1 w-full border-gray-300 rounded-md" required>
                            @foreach (\App\Enums\ConceptStatus::cases() as $status)
                                <option value="{{ $status->value }}" {{ old('status') == $status->value ? 'selected' : '' }}>
                                    {{ $status->label() }}
                                </option>
                            @endforeach
                        </select>
                        <x-input-error :messages="$errors->get('status')" class="mt-2" />
                    </div>

                    <div class="flex justify-end">
                        <a href="{{ route('concepts.index') }}" class="text-gray-600 hover:text-gray-900 mr-4 py-2">{{ __('Cancel') }}</a>
                        <x-primary-button>{{ __('Create Concept') }}</x-primary-button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</x-app-layout>