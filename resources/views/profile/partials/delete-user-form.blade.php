<section>
    <header class="mb-6">
        <h2 class="text-lg font-medium text-white">Delete Account</h2>
        <p class="mt-1 text-sm text-white/50">Once your account is deleted, all of its resources and data will be permanently deleted. Before deleting your account, please download any data or information that you wish to retain.</p>
    </header>

    <x-danger-button
        x-data=""
        x-on:click.prevent="$dispatch('open-modal', 'confirm-user-deletion')"
        class="bg-red-600/20 hover:bg-red-600/40 text-red-400 border border-red-500/30"
    >Delete Account</x-danger-button>

    <x-modal name="confirm-user-deletion" :show="$errors->userDeletion->isNotEmpty()" focusable>
        <form method="post" action="{{ route('profile.destroy') }}" class="p-6">
            @csrf
            @method('delete')

            <h2 class="text-lg font-medium text-white mb-2">Are you sure you want to delete your account?</h2>

            <p class="text-sm text-white/50 mb-6">
                Once your account is deleted, all of its resources and data will be permanently deleted. Please enter your password to confirm you would like to permanently delete your account.
            </p>

            <div class="mb-6">
                <x-input-label for="password" value="{{ __('Password') }}" class="text-white/70 mb-2" />

                <x-text-input
                    id="password"
                    name="password"
                    type="password"
                    class="block w-full bg-white/5 border-white/10 text-white rounded-xl px-4 py-3 focus:border-indigo-500 focus:ring-1 focus:ring-indigo-500"
                    placeholder="{{ __('Password') }}"
                />

                <x-input-error :messages="$errors->userDeletion->get('password')" class="mt-2" />
            </div>

            <div class="flex justify-end gap-3">
                <x-secondary-button x-on:click="$dispatch('close')" class="bg-white/5 hover:bg-white/10 text-white/70 border-white/10">
                    Cancel
                </x-secondary-button>

                <x-danger-button class="bg-red-600 hover:bg-red-700 text-white border-red-600">
                    Delete Account
                </x-danger-button>
            </div>
        </form>
    </x-modal>
</section>