<button {{ $attributes->merge(['type' => 'submit', 'class' => 'inline-flex justify-center items-center px-6 py-2.5 rounded-xl font-bold bg-red-600 text-white hover:bg-red-500 hover:shadow-[0_0_15px_rgba(220,38,38,0.4)] transition-all transform hover:-translate-y-0.5 focus:outline-none focus:ring-2 focus:ring-red-500 focus:ring-offset-2 focus:ring-offset-background']) }}>
    {{ $slot }}
</button>
