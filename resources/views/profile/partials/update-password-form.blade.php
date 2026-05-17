<section>
    <header class="mb-6">
        <h2 class="text-lg font-medium text-white">Update Password</h2>
        <p class="mt-1 text-sm text-white/50">Ensure your account is using a long, random password to stay secure.</p>
    </header>

    <form method="post" action="{{ route('password.update') }}" class="space-y-5">
        @csrf
        @method('put')

        <div>
            <x-input-label for="update_password_current_password" :value="__('Current Password')" class="text-white/70 mb-2" />
            <x-text-input id="update_password_current_password" name="current_password" type="password" class="block w-full bg-white/5 border-white/10 text-white rounded-xl px-4 py-3 focus:border-indigo-500 focus:ring-1 focus:ring-indigo-500" autocomplete="current-password" />
            <x-input-error :messages="$errors->updatePassword->get('current_password')" class="mt-2" />
        </div>

        <div>
            <x-input-label for="update_password_password" :value="__('New Password')" class="text-white/70 mb-2" />
            <x-text-input id="update_password_password" name="password" type="password" class="block w-full bg-white/5 border-white/10 text-white rounded-xl px-4 py-3 focus:border-indigo-500 focus:ring-1 focus:ring-indigo-500" autocomplete="new-password" />
            <x-input-error :messages="$errors->updatePassword->get('password')" class="mt-2" />
        </div>

        <div>
            <x-input-label for="update_password_password_confirmation" :value="__('Confirm Password')" class="text-white/70 mb-2" />
            <x-text-input id="update_password_password_confirmation" name="password_confirmation" type="password" class="block w-full bg-white/5 border-white/10 text-white rounded-xl px-4 py-3 focus:border-indigo-500 focus:ring-1 focus:ring-indigo-500" autocomplete="new-password" />
            <x-input-error :messages="$errors->updatePassword->get('password_confirmation')" class="mt-2" />
        </div>

        <div class="flex items-center gap-4">
            <button type="submit" class="px-6 py-3 bg-indigo-600 hover:bg-indigo-700 text-white font-semibold rounded-xl transition shadow-lg shadow-indigo-500/20">
                Save
            </button>

            @if (session('status') === 'password-updated')
                <p
                    x-data="{ show: true }"
                    x-show="show"
                    x-transition
                    x-init="setTimeout(() => show = false, 2000)"
                    class="text-sm text-emerald-400"
                >Saved.</p>
            @endif
        </div>
    </form>
</section>