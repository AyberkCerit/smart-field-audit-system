<x-app-layout>
    <x-slot name="header">
        <h2 class="font-bold text-2xl text-white tracking-tight flex items-center gap-3">
            <svg class="w-8 h-8 text-primary" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 20l-5.447-2.724A1 1 0 013 16.382V5.618a1 1 0 011.447-.894L9 7m0 13l6-3m-6 3V7m6 10l4.553 2.276A1 1 0 0021 18.382V7.618a1 1 0 00-.553-.894L15 4m0 13V4m0 0L9 7"></path></svg>
            Map & Audit Points
        </h2>
    </x-slot>

    <div class="py-8">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 flex flex-col lg:flex-row gap-6 h-[calc(100vh-200px)] min-h-[600px]">
            
            <!-- Left Column: Audit Points List -->
            <div class="w-full lg:w-1/3 flex flex-col gap-4 h-full">
                <div class="bg-card-dark rounded-2xl border border-secondary/30 p-5 shadow-[0_0_20px_rgba(0,0,0,0.5)] flex flex-col h-full overflow-hidden">
                    <div class="flex justify-between items-center mb-4">
                        <h3 class="text-lg font-bold text-white">Points</h3>
                    </div>
                    
                    <div class="flex-grow overflow-y-auto pr-2 space-y-3 custom-scrollbar">
                        @forelse($auditPoints as $point)
                            <div class="bg-background/50 border border-secondary/20 rounded-xl p-4 hover:border-primary/40 transition-colors cursor-pointer group" onclick="focusMap({{ $point->latitude }}, {{ $point->longitude }})">
                                <div class="flex justify-between items-start mb-2">
                                    <h4 class="text-md font-bold text-white group-hover:text-primary transition-colors line-clamp-1">{{ $point->name }}</h4>
                                    @if($point->is_active)
                                        <span class="bg-green-500/20 text-green-400 border border-green-500/20 rounded-full px-2 py-0.5 text-[10px] font-bold uppercase tracking-wider">Active</span>
                                    @else
                                        <span class="bg-red-500/20 text-red-400 border border-red-500/20 rounded-full px-2 py-0.5 text-[10px] font-bold uppercase tracking-wider">Inactive</span>
                                    @endif
                                </div>
                                <p class="text-xs text-secondary line-clamp-2 mb-3">{{ $point->description }}</p>
                                <div class="flex items-center gap-4 text-xs font-semibold text-secondary/70">
                                    <div class="flex items-center gap-1">
                                        <svg class="w-3.5 h-3.5 text-accent" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314 0z"></path></svg>
                                        {{ number_format($point->latitude, 4) }}, {{ number_format($point->longitude, 4) }}
                                    </div>
                                    @if($point->tasks->isNotEmpty())
                                        <a href="{{ route('tasks.show', $point->tasks->first()) }}" class="ml-auto text-primary hover:text-white transition-colors" onclick="event.stopPropagation();">Details &rarr;</a>
                                    @else
                                        <span class="ml-auto text-secondary/50 text-[10px] uppercase font-bold tracking-wider cursor-not-allowed">No Task</span>
                                    @endif
                                </div>
                            </div>
                        @empty
                            <div class="text-center py-10 bg-background/30 rounded-xl border border-secondary/10">
                                <p class="text-secondary text-sm">No audit points found.</p>
                            </div>
                        @endforelse
                    </div>

                    <div class="mt-4 pt-4 border-t border-secondary/20">
                        {{ $auditPoints->links() }}
                    </div>
                </div>
            </div>

            <!-- Right Column: Map -->
            <div class="w-full lg:w-2/3 h-[400px] lg:h-full">
                <div class="bg-card-dark rounded-2xl border border-secondary/30 p-2 shadow-[0_0_20px_rgba(0,0,0,0.5)] h-full relative overflow-hidden group">
                    <div id="map" class="w-full h-full rounded-xl z-0"></div>
                    <div class="absolute top-6 right-6 z-10 bg-background/80 backdrop-blur-md border border-white/10 rounded-xl px-4 py-2 shadow-lg opacity-0 group-hover:opacity-100 transition-opacity pointer-events-none">
                        <p class="text-xs font-bold text-white">Click on point to zoom in</p>
                    </div>
                </div>
            </div>

        </div>
    </div>

    @push('styles')
        <link href="{{ asset('css/audit-points.css') }}?v={{ time() }}" rel="stylesheet">
    @endpush

    @push('scripts')
        <script defer src="{{ asset('js/map-helper.js') }}?v={{ time() }}"></script>
        <script>
            // Pass the audit points data to the external JS file
            window.auditPointsData = @json($auditPoints->items());
            window.defaultMapView = '{{ $mapDefaultView ?? "hybrid" }}';
        </script>
        <script defer src="{{ asset('js/audit-points.js') }}?v={{ time() }}"></script>
    @endpush
</x-app-layout>
