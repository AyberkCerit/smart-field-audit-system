<x-app-layout>
    @push('styles')
        <link href="{{ asset('css/tasks.css') }}?v={{ time() }}" rel="stylesheet">
    @endpush

    <div class="w-full max-w-4xl mx-auto mb-10 mt-6">
        <!-- Header -->
        <div class="mb-8">
            <a href="{{ route('tasks.show', $task) }}" class="inline-flex items-center gap-2 text-sm text-secondary hover:text-white transition-colors mb-4">
                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18"></path></svg>
                Back to Task
            </a>
            <h1 class="text-3xl font-display font-bold text-white flex items-center gap-3">
                <span class="material-icons text-primary text-3xl">edit</span>
                Edit Task
            </h1>
            <p class="text-secondary mt-1">Update the task details, reassign personnel, or modify the location.</p>
        </div>

        <!-- Form Card -->
        <div class="bg-card-dark rounded-2xl border border-secondary/30 p-6 md:p-8 shadow-[0_0_20px_rgba(0,0,0,0.5)]">
            <form action="{{ route('tasks.update', $task) }}" method="POST" enctype="multipart/form-data" class="space-y-6">
                @csrf
                @method('PUT')

                <!-- Başlık -->
                <div>
                    <label for="title" class="block text-sm font-semibold text-text mb-2">Task Title <span class="text-red-500">*</span></label>
                    <input type="text" id="title" name="title" value="{{ old('title', $task->title) }}" required autofocus
                           class="w-full bg-background border border-secondary/30 rounded-xl px-4 py-3 text-text focus:border-primary focus:ring-1 focus:ring-primary transition-all @error('title') border-red-500 focus:border-red-500 focus:ring-red-500 @enderror"
                           placeholder="Ex: North Park Lighting Audit">
                    @error('title')
                        <p class="text-red-400 text-xs mt-1">{{ $message }}</p>
                    @enderror
                </div>

                <!-- Açıklama -->
                <div>
                    <label for="description" class="block text-sm font-semibold text-text mb-2">Task Description</label>
                    <textarea id="description" name="description" rows="4" 
                              class="w-full bg-background border border-secondary/30 rounded-xl px-4 py-3 text-text focus:border-primary focus:ring-1 focus:ring-primary transition-all @error('description') border-red-500 focus:border-red-500 focus:ring-red-500 @enderror"
                              placeholder="You can write detailed instructions about the task here...">{{ old('description', $task->description) }}</textarea>
                    @error('description')
                        <p class="text-red-400 text-xs mt-1">{{ $message }}</p>
                    @enderror
                </div>

                <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                    
                    <!-- Haritadan Lokasyon Seçimi -->
                    <div class="md:col-span-2">
                        <label class="block text-sm font-semibold text-text mb-2">Select Task Area from Map <span class="text-red-500">*</span></label>
                        <p class="text-xs text-secondary mb-3">Please click on the map to update the location where the task will be performed.</p>
                        
                        <!-- Hidden inputs for coordinates -->
                        <input type="hidden" id="latitude" name="latitude" value="{{ old('latitude', optional($task->auditPoint)->latitude) }}">
                        <input type="hidden" id="longitude" name="longitude" value="{{ old('longitude', optional($task->auditPoint)->longitude) }}">
                        
                        <!-- Map Container -->
                        <div class="w-full h-[300px] rounded-xl overflow-hidden border border-secondary/30 relative z-0 transition-all @error('latitude') border-red-500 shadow-[0_0_10px_rgba(239,68,68,0.3)] @enderror">
                            <div id="taskMap" class="absolute inset-0"></div>
                        </div>
                        
                        @error('latitude')
                            <p class="text-red-400 text-xs mt-2">Please select a valid location (pin) on the map.</p>
                        @enderror
                    </div>

                    <!-- Atanan Personel -->
                    <div>
                        <label for="assigned_to" class="block text-sm font-semibold text-text mb-2">Personnel to Assign</label>
                        <select id="assigned_to" name="assigned_to"
                                class="w-full bg-background border border-secondary/30 rounded-xl px-4 py-3 text-text focus:border-primary focus:ring-1 focus:ring-primary transition-all @error('assigned_to') border-red-500 focus:border-red-500 focus:ring-red-500 @enderror">
                            <option value="">No Assignment (Pool)</option>
                            @foreach($users as $user)
                                <option value="{{ $user->id }}" {{ old('assigned_to', $task->assigned_to) == $user->id ? 'selected' : '' }}>
                                    {{ $user->name }}
                                </option>
                            @endforeach
                        </select>
                        @error('assigned_to')
                            <p class="text-red-400 text-xs mt-1">{{ $message }}</p>
                        @enderror
                    </div>

                    <!-- Öncelik -->
                    <div>
                        <label for="priority" class="block text-sm font-semibold text-text mb-2">Priority Status <span class="text-red-500">*</span></label>
                        <select id="priority" name="priority" required
                                class="w-full bg-background border border-secondary/30 rounded-xl px-4 py-3 text-text focus:border-primary focus:ring-1 focus:ring-primary transition-all @error('priority') border-red-500 focus:border-red-500 focus:ring-red-500 @enderror">
                            <option value="low" {{ old('priority', $task->priority) == 'low' ? 'selected' : '' }}>Low</option>
                            <option value="normal" {{ old('priority', $task->priority) == 'normal' ? 'selected' : '' }}>Normal</option>
                            <option value="high" {{ old('priority', $task->priority) == 'high' ? 'selected' : '' }}>Urgent (High)</option>
                        </select>
                        @error('priority')
                            <p class="text-red-400 text-xs mt-1">{{ $message }}</p>
                        @enderror
                    </div>

                    <!-- Son Tarih -->
                    <div>
                        <label for="due_date" class="block text-sm font-semibold text-text mb-2">Due Date (Optional)</label>
                        <div class="relative">
                            <svg class="w-5 h-5 absolute left-4 top-1/2 -translate-y-1/2 text-secondary pointer-events-none" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"></path></svg>
                            <input type="date" id="due_date" name="due_date" value="{{ old('due_date', optional($task->due_date)->format('Y-m-d')) }}"
                                   class="w-full bg-background border border-secondary/30 rounded-xl pl-12 pr-4 py-3 text-text focus:border-primary focus:ring-1 focus:ring-primary transition-all @error('due_date') border-red-500 focus:border-red-500 focus:ring-red-500 @enderror [color-scheme:dark]">
                        </div>
                        @error('due_date')
                            <p class="text-red-400 text-xs mt-1">{{ $message }}</p>
                        @enderror
                    </div>
                </div>

                <!-- Submit Buttons -->
                <div class="flex items-center justify-end gap-4 pt-6 border-t border-secondary/20 mt-8">
                    <a href="{{ route('tasks.show', $task) }}" class="px-6 py-3 rounded-xl text-sm font-semibold text-text hover:text-white hover:bg-white/5 transition-all">
                        Cancel
                    </a>
                    <button type="button" id="holdToConfirmBtn" class="relative overflow-hidden flex items-center gap-2 px-8 py-3 rounded-xl text-sm font-bold border-2 border-primary/40 bg-background/50 text-white shadow-lg hover:border-primary/80 hover:shadow-[0_0_20px_rgba(27,95,197,0.3)] transition-all duration-300 select-none cursor-pointer group">
                        <!-- Fill Effect (Progress) -->
                        <div id="holdProgress" class="absolute left-0 top-0 bottom-0 w-0 bg-primary/90"></div>
                        <!-- Content -->
                        <span class="relative z-10 flex items-center gap-2 transition-transform duration-200" id="holdContent">
                            <svg class="w-5 h-5" fill="none" stroke="currentColor" stroke-width="2.5" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M5 13l4 4L19 7"></path></svg>
                            <span id="holdText">Hold to Update</span>
                        </span>
                    </button>
                </div>
            </form>
        </div>
    </div>

    @push('scripts')
        <script defer src="{{ asset('js/hold-to-confirm.js') }}?v={{ time() }}"></script>
        <script defer src="{{ asset('js/map-helper.js') }}?v={{ time() }}"></script>
        <script defer src="{{ asset('js/task-map.js') }}?v={{ time() }}"></script>
    @endpush
</x-app-layout>
