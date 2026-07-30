<x-app-layout>
    @push('styles')
        <link href="{{ asset('css/tasks.css') }}?v={{ time() }}" rel="stylesheet">
    @endpush

    <div class="w-full mb-6">
        <!-- Header -->
        <div class="flex flex-col md:flex-row justify-between items-center mb-8 gap-4">
            <div>
                <h1 class="text-3xl font-display font-bold text-white flex items-center gap-3">
                    <span class="material-icons text-accent text-3xl">task</span>
                    Task Management
                </h1>
                <p class="text-secondary mt-1">You can manage and track all field tasks here.</p>
            </div>
            @hasanyrole('admin|manager')
            <a href="{{ route('tasks.create') }}" class="flex items-center gap-2 px-5 py-2.5 rounded-xl text-sm font-bold bg-[#a4d756] hover:bg-[#91c342] text-gray-900 shadow-[0_0_15px_rgba(164,215,86,0.4)] hover:shadow-[0_0_20px_rgba(164,215,86,0.6)] hover:-translate-y-1 transition-all duration-300">
                <svg class="w-5 h-5 text-gray-900" fill="none" stroke="currentColor" stroke-width="2.5" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M12 4v16m8-8H4"></path></svg>
                Create New Task
            </a>
            @endhasanyrole
        </div>

        <!-- Filters -->
        <form method="GET" action="{{ route('tasks.index') }}" class="bg-card-dark rounded-2xl border border-secondary/30 p-4 mb-8 shadow-md flex flex-col md:flex-row items-center gap-4">
            <div class="flex-1 relative w-full">
                <svg class="w-5 h-5 absolute left-3 top-1/2 -translate-y-1/2 text-secondary" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"></path></svg>
                <input type="text" name="search" value="{{ request('search') }}" placeholder="Search task title or description..." class="w-full bg-background border border-secondary/30 rounded-xl pl-10 pr-4 py-2.5 text-text focus:border-primary/50 focus:ring-1 focus:ring-primary/50 transition-colors" onkeydown="if(event.key === 'Enter'){ this.form.submit(); }">
            </div>
            <select name="status" class="bg-background border border-secondary/30 rounded-xl px-4 py-2.5 text-text focus:border-primary/50 focus:ring-1 focus:ring-primary/50 w-full md:w-auto" onchange="this.form.submit()">
                <option value="">All Statuses</option>
                <option value="pending" @selected(request('status') === 'pending')>Pending</option>
                <option value="completed" @selected(request('status') === 'completed')>Completed</option>
            </select>
        </form>

        <!-- Tasks Grid -->
        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
            @forelse($tasks as $task)
                <a href="{{ route('tasks.show', $task) }}" class="block bg-card-dark rounded-2xl border border-secondary/30 p-5 flex flex-col shadow-[0_0_20px_rgba(0,0,0,0.5)] transition-all hover:border-primary/40 hover:-translate-y-1 duration-300 group cursor-pointer">
                    <div class="flex justify-between items-start mb-4">
                        <div class="flex flex-wrap gap-2">
                            <!-- Priority Badge -->
                            @if($task->priority === 'high')
                                <span class="bg-red-500/20 text-red-400 border border-red-500/20 rounded-full px-2.5 py-0.5 text-xs font-bold uppercase tracking-wider">Urgent</span>
                            @elseif($task->priority === 'normal')
                                <span class="bg-orange-500/20 text-orange-400 border border-orange-500/20 rounded-full px-2.5 py-0.5 text-xs font-bold uppercase tracking-wider">Normal</span>
                            @else
                                <span class="bg-green-500/20 text-green-400 border border-green-500/20 rounded-full px-2.5 py-0.5 text-xs font-bold uppercase tracking-wider">Low</span>
                            @endif

                            <!-- Status Badge -->
                            @if($task->status === 'completed')
                                <span class="bg-green-500/20 text-green-400 border border-green-500/20 rounded-full px-2.5 py-0.5 text-xs font-bold uppercase tracking-wider shadow-[0_0_5px_rgba(74,222,128,0.3)]">Completed</span>
                            @else
                                <span class="bg-[#e4ba00]/20 text-[#e4ba00] border border-[#e4ba00]/30 rounded-full px-2.5 py-0.5 text-xs font-bold uppercase tracking-wider shadow-[0_0_5px_rgba(228,186,0,0.3)]">Pending</span>
                            @endif
                        </div>

                    </div>

                    <h3 class="text-lg font-bold text-white mb-2 group-hover:text-transparent group-hover:bg-clip-text group-hover:bg-gradient-to-r group-hover:from-accent group-hover:to-primary transition-colors line-clamp-1" title="{{ $task->title }}">
                        {{ $task->title }}
                    </h3>
                    
                    <p class="text-sm text-secondary line-clamp-2 mb-4 flex-grow" title="{{ $task->description }}">
                        {{ $task->description }}
                    </p>

                    <div class="flex flex-col gap-2 mt-auto">
                        <!-- Location -->
                        @if($task->auditPoint)
                        <div class="flex items-center gap-2 text-xs text-text/80 bg-background/50 rounded-lg p-2 border border-secondary/20">
                            <svg class="w-4 h-4 text-accent" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314 0z"></path><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 11a3 3 0 11-6 0 3 3 0 016 0z"></path></svg>
                            <span class="truncate">{{ $task->auditPoint->name }}</span>
                        </div>
                        @endif
                        
                        <div class="flex justify-between items-center mt-2 border-t border-secondary/20 pt-3">
                            <!-- Manager -> Assignee -->
                            <div class="flex items-center gap-1.5" title="Manager: {{ $task->manager->name ?? 'System' }} -> Personnel: {{ $task->assignedUser->name ?? 'Not Specified' }}">
                                <span class="text-xs text-text truncate max-w-[70px] font-medium" title="{{ $task->manager->name ?? 'System' }}">
                                    {{ $task->manager ? explode(' ', trim($task->manager->name))[0] : 'System' }}
                                </span>
                                <svg class="w-3 h-3 text-secondary/70" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M14 5l7 7m0 0l-7 7m7-7H3"></path></svg>
                                <span class="text-xs text-accent truncate max-w-[70px] font-bold" title="{{ $task->assignedUser->name ?? 'Not Specified' }}">
                                    {{ $task->assignedUser ? explode(' ', trim($task->assignedUser->name))[0] : 'Not Specified' }}
                                </span>
                            </div>
                            
                            <!-- Due Date -->
                            <div class="flex items-center gap-1.5 text-xs font-medium {{ $task->due_date && $task->due_date < now() && $task->status !== 'completed' ? 'text-red-400' : 'text-secondary' }}">
                                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"></path></svg>
                                {{ $task->due_date ? $task->due_date->format('d M Y') : 'No Deadline' }}
                            </div>
                        </div>
                    </div>
                </a>
            @empty
                <div class="col-span-full flex flex-col items-center justify-center bg-card-dark rounded-2xl border border-secondary/30 p-12 shadow-[0_0_20px_rgba(0,0,0,0.5)]">
                    <div class="w-16 h-16 rounded-full bg-primary/10 flex items-center justify-center text-primary mb-4">
                        <svg class="w-8 h-8" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M20 13V6a2 2 0 00-2-2H6a2 2 0 00-2 2v7m16 0v5a2 2 0 01-2 2H6a2 2 0 01-2-2v-5m16 0h-2.586a1 1 0 00-.707.293l-2.414 2.414a1 1 0 01-.707.293h-3.172a1 1 0 01-.707-.293l-2.414-2.414A1 1 0 006.586 13H4"></path></svg>
                    </div>
                    <h3 class="text-xl font-bold text-white mb-2">No Tasks Found</h3>
                    <p class="text-secondary text-center max-w-md">No tasks found matching your filter or registered in the system. You can start by creating a new task.</p>
                </div>
            @endforelse
        </div>

        <!-- Pagination -->
        <div class="mt-8">
            {{ $tasks->links() }}
        </div>
    </div>
</x-app-layout>
