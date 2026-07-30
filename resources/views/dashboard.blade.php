<x-app-layout>
    @push('styles')
        <link href="{{ asset('css/dashboard.css') }}?v={{ time() }}" rel="stylesheet">
    @endpush

    <div class="w-full mb-6">
        @hasanyrole('admin|manager')
        <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
            <!-- Card 1: Created Task -->
            <div class="bg-card-dark rounded-2xl border border-secondary/30 p-6 flex flex-col shadow-[0_0_20px_rgba(0,0,0,0.5)] transition-all hover:border-primary/40 hover:-translate-y-1 duration-300">
                <div class="flex items-center gap-3 mb-4">
                    <div class="w-10 h-10 rounded-xl bg-primary/20 flex items-center justify-center text-primary shadow-[0_0_10px_rgba(27,95,197,0.3)]">
                        <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2m-6 9l2 2 4-4"></path></svg>
                    </div>
                    <h3 class="text-lg font-bold text-white">Recently Created Tasks</h3>
                </div>
                <div class="flex flex-col gap-3 flex-grow">
                    @forelse($recentTasks as $task)
                        <div class="bg-background/50 rounded-lg p-3 border border-secondary/20 flex justify-between items-center hover:border-primary/30 transition-colors">
                            <div>
                                <p class="text-sm font-semibold text-white">{{ Str::limit($task->title, 20) }}</p>
                                <p class="text-xs text-secondary mt-1">{{ $task->created_at->diffForHumans() }}</p>
                            </div>
                            <span class="px-2 py-1 text-[10px] uppercase font-bold rounded-md {{ $task->status === 'completed' ? 'bg-green-500/20 text-green-400' : 'bg-[#e4ba00]/20 text-[#e4ba00]' }}">
                                {{ $task->status === 'pending' ? 'Pending' : ucfirst($task->status) }}
                            </span>
                        </div>
                    @empty
                        <div class="text-sm text-secondary text-center py-4">You haven't created any tasks yet.</div>
                    @endforelse
                </div>
            </div>

            <!-- Card 2: Total Solved Tasks -->
            <div class="bg-card-dark rounded-2xl border border-secondary/30 p-6 flex flex-col shadow-[0_0_20px_rgba(0,0,0,0.5)] transition-all hover:border-primary/40 hover:-translate-y-1 duration-300">
                <div class="flex items-center gap-3 mb-4">
                    <div class="w-10 h-10 rounded-xl bg-green-500/20 flex items-center justify-center text-green-400 shadow-[0_0_10px_rgba(74,222,128,0.3)]">
                        <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path></svg>
                    </div>
                    <h3 class="text-lg font-bold text-white">Resolved Tasks</h3>
                </div>
                <div class="flex-grow flex items-center justify-center">
                    <div class="text-center">
                        <span class="text-6xl font-extrabold text-transparent bg-clip-text bg-gradient-to-br from-green-400 to-green-600 drop-shadow-lg">{{ $totalSolved }}</span>
                        <p class="text-sm text-secondary mt-2 font-medium">Total number of completed tasks</p
                    </div>
                </div>
            </div>

            <!-- Card 3: Avg Resolution Time -->
            <div class="bg-card-dark rounded-2xl border border-secondary/30 p-6 flex flex-col shadow-[0_0_20px_rgba(0,0,0,0.5)] transition-all hover:border-primary/40 hover:-translate-y-1 duration-300">
                <div class="flex items-center gap-3 mb-4">
                    <div class="w-10 h-10 rounded-xl bg-accent/20 flex items-center justify-center text-accent shadow-[0_0_10px_rgba(31,201,221,0.3)]">
                        <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"></path></svg>
                    </div>
                    <h3 class="text-lg font-bold text-white">Average Resolution Time</h3>
                </div>
                <div class="flex-grow flex items-center justify-center">
                    <div class="text-center">
                        <div class="flex items-end justify-center gap-1 drop-shadow-lg">
                            <span class="text-6xl font-extrabold text-transparent bg-clip-text bg-gradient-to-br from-accent to-primary">{{ $avgResolutionTime }}</span>
                            <span class="text-xl font-bold text-secondary mb-2">Hours</span>
                        </div>
                        <p class="text-sm text-secondary mt-2 font-medium">Average time spent per task</p>
                    </div>
                </div>
            </div>
        </div>
        @endhasanyrole
    </div>

    <div class="grid grid-cols-1 gap-6">
        
        <!-- Live Map Card -->
        <div class="bg-card-dark rounded-2xl border border-secondary/30 p-6 flex flex-col shadow-[0_0_20px_rgba(0,0,0,0.5)] transition-all hover:border-accent/40">
            <div class="flex items-center justify-between mb-4">
                <h3 class="text-xl font-bold text-white flex items-center gap-2">
                    <span class="material-icons text-accent">Map</span>
                    Live Field Map
                </h3>
                <span class="px-3 py-1 bg-green-500/10 text-green-400 border border-green-500/20 rounded-full text-xs font-semibold animate-pulse flex items-center gap-1.5">
                    <span class="w-1.5 h-1.5 bg-green-400 rounded-full"></span>
                    Live
                </span>
            </div>
            <div class="w-full flex-grow rounded-xl overflow-hidden border border-secondary/20 relative min-h-[400px] lg:min-h-[500px]">
                <!-- Leaflet Map Container -->
                <div id="map" class="absolute inset-0 z-0"></div>
            </div>
        </div>

    </div>

    @push('scripts')
        <script defer src="{{ asset('js/map-helper.js') }}?v={{ time() }}"></script>
        <script>
            // Using window object to pass backend data to map JS file
            window.dashboardMapPoints = @json($auditPoints ?? []);
        </script>
        <script defer src="{{ asset('js/dashboard.js') }}?v={{ time() }}"></script>
    @endpush
</x-app-layout>
