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
                        <p class="text-sm text-secondary mt-2 font-medium">Total number of completed tasks</p>
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

    <div class="grid grid-cols-1 lg:grid-cols-2 gap-6">
        
        <!-- Live Map Card -->
        <div class="bg-card-dark rounded-2xl border border-secondary/30 p-6 flex flex-col shadow-[0_0_20px_rgba(0,0,0,0.5)] transition-all hover:border-accent/40">
            <div class="flex items-center justify-between mb-4">
                <h3 class="text-xl font-bold text-white flex items-center gap-2">
                    <span class="material-icons text-accent">location_on</span>
                    Live Locations
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

        <!-- Right Side: Role Specific Content -->
        <div class="flex flex-col gap-6">
            
            @hasanyrole('admin|manager')
            <!-- Latest Users -->
            <div class="bg-card-dark rounded-2xl border border-secondary/30 p-6 flex flex-col shadow-[0_0_20px_rgba(0,0,0,0.5)] transition-all hover:border-accent/40">
                <div class="flex items-center gap-3 mb-4">
                    <div class="w-10 h-10 rounded-xl bg-purple-500/20 flex items-center justify-center text-purple-400 shadow-[0_0_10px_rgba(168,85,247,0.3)]">
                        <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0zm6 3a2 2 0 11-4 0 2 2 0 014 0zM7 10a2 2 0 11-4 0 2 2 0 014 0z"></path></svg>
                    </div>
                    <h3 class="text-lg font-bold text-white">Latest Registered Users</h3>
                </div>
                <div class="flex flex-col gap-3">
                    @forelse($latestUsers as $user)
                        <div class="bg-background/50 rounded-lg p-3 border border-secondary/20 flex justify-between items-center hover:border-primary/30 transition-colors">
                            <div class="flex items-center gap-3">
                                <div class="w-8 h-8 rounded-full bg-primary/20 flex items-center justify-center text-primary font-bold">
                                    {{ substr($user->name, 0, 1) }}
                                </div>
                                <div>
                                    <p class="text-sm font-semibold text-white">{{ $user->name }}</p>
                                    <p class="text-xs text-secondary">{{ $user->email }}</p>
                                </div>
                            </div>
                            <span class="text-xs text-secondary">{{ $user->created_at->diffForHumans() }}</span>
                        </div>
                    @empty
                        <div class="text-sm text-secondary text-center py-4">No users found.</div>
                    @endforelse
                </div>
            </div>

            <!-- Recent Activities -->
            <div class="bg-card-dark rounded-2xl border border-secondary/30 p-6 flex flex-col shadow-[0_0_20px_rgba(0,0,0,0.5)] transition-all hover:border-accent/40 flex-grow">
                <div class="flex items-center gap-3 mb-4">
                    <div class="w-10 h-10 rounded-xl bg-orange-500/20 flex items-center justify-center text-orange-400 shadow-[0_0_10px_rgba(249,115,22,0.3)]">
                        <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 10V3L4 14h7v7l9-11h-7z"></path></svg>
                    </div>
                    <h3 class="text-lg font-bold text-white">Recent Activities</h3>
                </div>
                <div class="flex flex-col gap-3">
                    @forelse($recentActivities as $activity)
                        <div class="bg-background/50 rounded-lg p-3 border border-secondary/20 flex flex-col hover:border-primary/30 transition-colors">
                            <div class="flex justify-between items-center mb-1">
                                <span class="text-sm font-semibold text-white">{{ $activity->causer ? $activity->causer->name : 'System' }}</span>
                                <span class="text-xs text-secondary">{{ $activity->created_at->diffForHumans() }}</span>
                            </div>
                            <p class="text-xs text-secondary/80">
                                <span class="capitalize text-primary/80">{{ $activity->log_name ?? 'default' }}:</span> {{ $activity->description }}
                                @if($activity->subject_type)
                                    <span class="text-secondary/60">on {{ class_basename($activity->subject_type) }}</span>
                                @endif
                            </p>
                        </div>
                    @empty
                        <div class="text-sm text-secondary text-center py-4">No recent activities.</div>
                    @endforelse
                </div>
            </div>
            @else
            <!-- Active Tasks for Personnel -->
            <div class="bg-card-dark rounded-2xl border border-secondary/30 p-6 flex flex-col shadow-[0_0_20px_rgba(0,0,0,0.5)] transition-all hover:border-accent/40 max-h-[300px]">
                <div class="flex items-center gap-3 mb-4">
                    <div class="w-10 h-10 rounded-xl bg-blue-500/20 flex items-center justify-center text-blue-400 shadow-[0_0_10px_rgba(59,130,246,0.3)]">
                        <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2m-6 9l2 2 4-4"></path></svg>
                    </div>
                    <h3 class="text-lg font-bold text-white">Active Tasks</h3>
                </div>
                <div class="flex flex-col gap-3 overflow-y-auto custom-scrollbar pr-2">
                    @forelse($personnelTasks as $task)
                        <a href="{{ route('tasks.show', $task->id) }}" class="bg-background/50 rounded-lg p-3 border border-secondary/20 flex justify-between items-center hover:border-primary/30 hover:bg-white/5 transition-colors group">
                            <div>
                                <p class="text-sm font-semibold text-white group-hover:text-primary transition-colors">{{ Str::limit($task->title, 30) }}</p>
                                <p class="text-xs text-secondary mt-1">{{ $task->auditPoint ? $task->auditPoint->name : 'No Point' }}</p>
                            </div>
                            <span class="px-2 py-1 text-[10px] uppercase font-bold rounded-md bg-[#e4ba00]/20 text-[#e4ba00]">
                                {{ ucfirst($task->status) }}
                            </span>
                        </a>
                    @empty
                        <div class="text-sm text-secondary text-center py-4">You have no active tasks.</div>
                    @endforelse
                </div>
            </div>

            <!-- Feedback Chat -->
            <div class="bg-card-dark rounded-2xl border border-secondary/30 flex flex-col shadow-[0_0_20px_rgba(0,0,0,0.5)] transition-all hover:border-accent/40 flex-grow max-h-[400px]">
                <div class="flex items-center gap-3 p-4 border-b border-secondary/30">
                    <div class="w-10 h-10 rounded-xl bg-green-500/20 flex items-center justify-center text-green-400 shadow-[0_0_10px_rgba(74,222,128,0.3)]">
                        <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 12h.01M12 12h.01M16 12h.01M21 12c0 4.418-4.03 8-9 8a9.863 9.863 0 01-4.255-.949L3 20l1.395-3.72C3.512 15.042 3 13.574 3 12c0-4.418 4.03-8 9-8s9 3.582 9 8z"></path></svg>
                    </div>
                    <h3 class="text-lg font-bold text-white">Feedback to Managers</h3>
                </div>
                
                <div id="feedback-container" class="flex-grow flex flex-col-reverse p-4 gap-3 overflow-y-auto custom-scrollbar">
                    @forelse($feedbacks as $feedback)
                        <div class="bg-background/50 rounded-lg p-3 border border-secondary/20 {{ $feedback->user_id === auth()->id() ? 'ml-auto border-primary/30 bg-primary/10' : 'mr-auto' }} max-w-[85%]">
                            <div class="flex justify-between items-center mb-1 gap-4">
                                <span class="text-xs font-bold {{ $feedback->user_id === auth()->id() ? 'text-primary' : 'text-white' }}">{{ $feedback->user_id === auth()->id() ? 'You' : $feedback->user->name }}</span>
                                <span class="text-[10px] text-secondary">{{ $feedback->created_at->format('H:i') }}</span>
                            </div>
                            <p class="text-sm text-white/90">{{ $feedback->message }}</p>
                        </div>
                    @empty
                        <div class="text-sm text-secondary text-center py-4">No feedback sent yet. Start the conversation!</div>
                    @endforelse
                </div>

                <div class="p-4 border-t border-secondary/30 bg-background/30 rounded-b-2xl">
                    <form id="feedback-form" action="{{ route('feedbacks.store') }}" method="POST" class="flex gap-2">
                        @csrf
                        <input type="text" name="message" placeholder="Type your feedback..." required class="flex-grow bg-background border border-secondary/40 rounded-xl px-4 py-2 text-white text-sm focus:border-primary focus:ring-1 focus:ring-primary outline-none transition-all">
                        <button type="submit" class="px-4 py-2 bg-primary text-white rounded-xl font-bold hover:bg-primary/80 hover:shadow-lg transition-all">Send</button>
                    </form>
                </div>
            </div>
            @endhasanyrole

        </div>

    </div>

    @push('scripts')
        <script defer src="{{ asset('js/map-helper.js') }}?v={{ time() }}"></script>
        <script>
            // Using window object to pass backend data to map JS file
            window.dashboardMapPoints = @json($auditPoints ?? []);
            window.defaultMapView = '{{ $mapDefaultView ?? "hybrid" }}';
        </script>
        <script defer src="{{ asset('js/dashboard.js') }}?v={{ time() }}"></script>
    @endpush
</x-app-layout>
