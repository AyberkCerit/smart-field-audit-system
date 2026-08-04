<button {{ $attributes->merge(['type' => 'button', 'class' => 'inline-flex justify-center items-center px-6 py-2.5 rounded-xl font-semibold bg-background/50 border border-secondary/30 text-text hover:bg-white/10 hover:border-secondary transition-colors focus:outline-none focus:ring-2 focus:ring-primary focus:ring-offset-2 focus:ring-offset-background disabled:opacity-25']) }}>
    {{ $slot }}
</button>
