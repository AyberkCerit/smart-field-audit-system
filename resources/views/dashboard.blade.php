<x-app-layout>
    @push('styles')
        <link href="{{ asset('css/dashboard.css') }}" rel="stylesheet">
    @endpush

    <div class="grid grid-cols-1 lg:grid-cols-2 gap-6">
        
        <!-- Live Map Card -->
        <div class="bg-card-dark rounded-2xl border border-secondary/30 p-6 flex flex-col shadow-[0_0_20px_rgba(0,0,0,0.5)] transition-all hover:border-accent/40">
            <div class="flex items-center justify-between mb-4">
                <h3 class="text-xl font-bold text-white flex items-center gap-2">
                    <span class="material-icons text-accent">map</span>
                    Live Field Map
                </h3>
                <span class="px-3 py-1 bg-green-500/10 text-green-400 border border-green-500/20 rounded-full text-xs font-semibold animate-pulse flex items-center gap-1.5">
                    <span class="w-1.5 h-1.5 bg-green-400 rounded-full"></span>
                    Live
                </span>
            </div>
            <div class="w-full flex-grow rounded-xl overflow-hidden border border-secondary/20 relative min-h-[300px] lg:min-h-[400px]">
                <!-- Placeholder for actual map integration (e.g. Leaflet, Google Maps) -->
                <!-- İleride kendi canlı haritanızı buradaki iframe veya div içerisine entegre edebilirsiniz -->
                <iframe 
                    width="100%" 
                    height="100%" 
                    frameborder="0" 
                    scrolling="no" 
                    marginheight="0" 
                    marginwidth="0" 
                    src="https://maps.google.com/maps?width=100%25&amp;height=100%25&amp;hl=en&amp;q=Istanbul+(Saha%20Denetim)&amp;t=p&amp;z=12&amp;ie=UTF8&amp;iwloc=B&amp;output=embed"
                    class="absolute inset-0 grayscale contrast-125 opacity-70 mix-blend-screen dark-map-filter">
                </iframe>
                <!-- Koyu tema hissini artırmak için üstten inen gölge efekti -->
                <div class="absolute inset-0 bg-gradient-to-t from-card-dark via-transparent to-transparent pointer-events-none"></div>
            </div>
        </div>

        <!-- Task Completed Chart Card -->
        <div class="bg-card-dark rounded-2xl border border-secondary/30 p-6 flex flex-col shadow-[0_0_20px_rgba(0,0,0,0.5)] transition-all hover:border-accent/40">
            <div class="flex items-center justify-between mb-4">
                <h3 class="text-xl font-bold text-white flex items-center gap-2">
                    <span class="material-icons text-accent">analytics</span>
                    Task Completion
                </h3>
                <select class="bg-background border border-secondary/30 text-text text-xs rounded-lg px-3 py-1.5 focus:ring-accent focus:border-accent outline-none cursor-pointer">
                    <option>This Week</option>
                    <option>This Month</option>
                    <option>This Year</option>
                </select>
            </div>
            <div class="w-full flex-grow flex items-center justify-center relative min-h-[300px] lg:min-h-[400px] p-2">
                <canvas id="taskChart"></canvas>
            </div>
        </div>

    </div>

    @push('scripts')
        <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
        <script src="{{ asset('js/dashboard.js') }}"></script>
    @endpush
</x-app-layout>
