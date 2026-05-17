<x-app-layout>
    <div class="pt-20">
        <div class="pt-4 pb-6">
            <h1 class="text-2xl font-bold text-white">Profile</h1>
            <p class="text-white/50 text-sm">Manage your account settings</p>
        </div>

        <div class="pb-8 space-y-5">
            <div class="bg-white/5 backdrop-blur-sm border border-white/10 rounded-2xl p-6">
                @include('profile.partials.update-profile-information-form')
            </div>

            <div class="bg-white/5 backdrop-blur-sm border border-white/10 rounded-2xl p-6">
                @include('profile.partials.update-password-form')
            </div>

            <div class="bg-white/5 backdrop-blur-sm border border-white/10 rounded-2xl p-6">
                @include('profile.partials.delete-user-form')
            </div>
        </div>
    </div>
</x-app-layout>