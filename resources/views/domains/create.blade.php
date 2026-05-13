<x-app-layout>
    <x-slot:header>
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('Create Domain') }}
        </h2>
    </x-slot:header>

    <div class="py-12">
        <div class="max-w-md mx-auto sm:px-6 lg:px-8">
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg p-6">
                <form method="POST" action="{{ route('domains.store') }}">
                    @csrf

                    <div class="mb-4">
                        <x-input-label for="name" :value="__('Domain Name')" />
                        <x-text-input id="name" class="block mt-1 w-full" type="text" name="name" :value="old('name')" required autofocus />
                        <x-input-error :messages="$errors->get('name')" class="mt-2" />
                    </div>

                    <div class="mb-4">
                        <x-input-label for="color" :value="__('Color')" />
                        <div class="flex items-center gap-2 mt-1">
                            <input type="color" name="color" id="colorPicker" value="{{ old('color', '#6366F1') }}" class="w-12 h-10 rounded cursor-pointer border-0">
                            <x-text-input id="colorInput" class="block flex-1" type="text" name="color_text" :value="old('color', '#6366F1')" placeholder="#000000" />
                        </div>
                        <x-input-error :messages="$errors->get('color')" class="mt-2" />
                    </div>

                    <div class="flex justify-end">
                        <a href="{{ route('domains.index') }}" class="text-gray-600 hover:text-gray-900 mr-4 py-2">{{ __('Cancel') }}</a>
                        <x-primary-button>{{ __('Create Domain') }}</x-primary-button>
                    </div>
                </form>
            </div>
        </div>
    </div>

    <script>
        document.getElementById('colorPicker').addEventListener('input', function(e) {
            document.getElementById('colorInput').value = e.target.value;
        });
        document.getElementById('colorInput').addEventListener('input', function(e) {
            if (/^#[0-9A-Fa-f]{6}$/.test(e.target.value)) {
                document.getElementById('colorPicker').value = e.target.value;
            }
        });
    </script>
</x-app-layout>