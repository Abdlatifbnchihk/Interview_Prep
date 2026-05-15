<x-app-layout>
    <x-slot:header>
        <a href="{{ route('domains.index') }}" class="flex items-center gap-2 text-white/70 hover:text-white transition">
            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7"/>
            </svg>
            Back to Domains
        </a>
    </x-slot:header>

    <div class="py-8">
        <div class="max-w-lg mx-auto">
            <div class="bg-white/5 backdrop-blur-sm border border-white/10 rounded-2xl p-8">
                <h1 class="text-2xl font-bold text-white mb-8">Create New Domain</h1>

                <form method="POST" action="{{ route('domains.store') }}">
                    @csrf

                    <div class="mb-6">
                        <label for="name" class="block text-sm font-medium text-white/70 mb-2">Domain Name</label>
                        <input type="text" id="name" name="name" :value="old('name')" required autofocus
                            class="w-full px-4 py-3 bg-white/5 border border-white/10 rounded-xl text-white placeholder-white/30 focus:outline-none focus:border-indigo-500 focus:ring-1 focus:ring-indigo-500 transition"
                            placeholder="e.g., Laravel, PHP OOP, REST API">
                        @error('name')
                            <p class="mt-2 text-sm text-red-400">{{ $message }}</p>
                        @enderror
                    </div>

                    <div class="mb-8">
                        <label class="block text-sm font-medium text-white/70 mb-2">Domain Color</label>
                        <div class="flex items-center gap-4">
                            <input type="color" name="color" id="colorPicker" value="{{ old('color', '#6366F1') }}"
                                class="w-14 h-14 rounded-xl cursor-pointer border-0 bg-transparent">
                            <input type="text" id="colorInput" name="color_text"
                                value="{{ old('color', '#6366F1') }}"
                                class="flex-1 px-4 py-3 bg-white/5 border border-white/10 rounded-xl text-white placeholder-white/30 focus:outline-none focus:border-indigo-500 transition"
                                placeholder="#000000">
                        </div>
                        @error('color')
                            <p class="mt-2 text-sm text-red-400">{{ $message }}</p>
                        @enderror
                    </div>

                    <div class="flex gap-4">
                        <a href="{{ route('domains.index') }}" class="flex-1 px-6 py-3 text-center text-white/70 hover:text-white bg-white/5 hover:bg-white/10 rounded-xl transition">
                            Cancel
                        </a>
                        <button type="submit" class="flex-1 px-6 py-3 bg-indigo-600 hover:bg-indigo-700 text-white rounded-xl font-medium transition">
                            Create Domain
                        </button>
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