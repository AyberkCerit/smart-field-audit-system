<button {{ $attributes->merge(['type' => 'submit', 'class' => 'inline-flex justify-center items-center px-6 py-2.5 rounded-xl font-bold bg-primary text-white hover:bg-primary/80 hover:shadow-[0_0_15px_rgba(27,95,197,0.4)] transition-all transform hover:-translate-y-0.5 focus:outline-none focus:ring-2 focus:ring-primary focus:ring-offset-2 focus:ring-offset-background']) }}>
    {{ $slot }}
</button>
