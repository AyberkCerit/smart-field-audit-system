@props(['disabled' => false])

<input @disabled($disabled) {{ $attributes->merge(['class' => 'bg-background border border-secondary/40 rounded-xl px-4 py-2 text-white focus:border-primary focus:ring-1 focus:ring-primary outline-none transition-all shadow-sm']) }}>
