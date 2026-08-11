@vite(['resources/css/loader.css', 'resources/js/loader.js'])
<div id="global-page-loader" class="fixed inset-0 z-[9999] flex flex-col items-center justify-center bg-[#0a0a0a] transition-opacity duration-500 ease-in-out">
    <div class="relative flex flex-col items-center">
        <!-- SVG Container -->
        <svg viewBox="0 0 304 112" class="w-64 h-24 md:w-80 md:h-32 text-accent glow-accent">
            <g stroke-width="2" stroke="currentColor" stroke-linejoin="round" fill="none" fill-rule="evenodd">
                <polygon id="path-1" points="152,4 170,38 204,56 170,74 152,108 134,74 100,56 134,38"></polygon>
                <polygon style="opacity: 0" id="path-2" points="152,4 170,38 204,56 170,74 152,108 134,74 100,56 134,38"></polygon>
            </g>
        </svg>

        <!-- Loading Text -->
        <div class="mt-6 font-bold text-lg text-white/90 tracking-widest uppercase flex items-center gap-1">
            <span class="inline-block relative">H</span>
            <span class="inline-block relative">A</span>
            <span class="inline-block relative">N</span>
            <span class="inline-block relative">G</span>
            <span class="inline-block relative ml-2">O</span>
            <span class="inline-block relative">N</span>
            <span class="inline-block relative ml-1 animate-pulse">.</span>
            <span class="inline-block relative animate-pulse" style="animation-delay: 200ms">.</span>
            <span class="inline-block relative animate-pulse" style="animation-delay: 400ms">.</span>
        </div>
    </div>
</div>

<script>
    // Set initial loading state as early as possible
    document.body.classList.add('loading-active');
</script>
