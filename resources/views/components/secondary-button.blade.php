<button {{ $attributes->merge(['type' => 'button', 'class' => 'inline-flex items-center justify-center px-4 py-2 bg-white/5 border border-white/10 rounded-lg font-semibold text-sm text-white/70 hover:bg-white/10 hover:text-white transition ease-in-out duration-150']) }}>
    {{ $slot }}
</button>