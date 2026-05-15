<x-guest-layout>
    <div class="mb-6">
        <h1 class="text-2xl font-bold text-white mb-2">Create your account</h1>
        <p class="text-white/60 text-sm">Start preparing for your interviews today</p>
    </div>

    <form method="POST" action="{{ route('register') }}">
        @csrf

        <div class="mb-4">
            <x-input-label for="name" :value="__('Name')" class="text-white/70" />
            <x-text-input id="name" class="block mt-1 w-full bg-white/5 border-white/10 text-white" type="text" name="name" :value="old('name')" required autofocus autocomplete="name" />
            <x-input-error :messages="$errors->get('name')" class="mt-2" />
        </div>

        <div class="mb-4">
            <x-input-label for="email" :value="__('Email')" class="text-white/70" />
            <x-text-input id="email" class="block mt-1 w-full bg-white/5 border-white/10 text-white" type="email" name="email" :value="old('email')" required autocomplete="username" />
            <x-input-error :messages="$errors->get('email')" class="mt-2" />
        </div>

        <div class="mb-4">
            <x-input-label for="password" :value="__('Password')" class="text-white/70" />
            <x-text-input id="password" class="block mt-1 w-full bg-white/5 border-white/10 text-white" type="password" name="password" required autocomplete="new-password" />
            <x-input-error :messages="$errors->get('password')" class="mt-2" />
        </div>

        <div class="mb-6">
            <x-input-label for="password_confirmation" :value="__('Confirm Password')" class="text-white/70" />
            <x-text-input id="password_confirmation" class="block mt-1 w-full bg-white/5 border-white/10 text-white" type="password" name="password_confirmation" required autocomplete="new-password" />
            <x-input-error :messages="$errors->get('password_confirmation')" class="mt-2" />
        </div>

        <x-primary-button class="w-full justify-center bg-indigo-600 hover:bg-indigo-700">
            {{ __('Register') }}
        </x-primary-button>
    </form>

    <div class="mt-6 text-center">
        <p class="text-white/50 text-sm">
            Already have an account?
            <a href="{{ route('login') }}" class="text-indigo-400 hover:text-indigo-300">Sign in</a>
        </p>
    </div>
</x-guest-layout>