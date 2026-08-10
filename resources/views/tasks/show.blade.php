<x-app-layout>
    @push('styles')
        <link href="{{ asset('css/tasks.css') }}?v={{ time() }}" rel="stylesheet">
    @endpush

    <div class="w-full max-w-7xl mx-auto mb-10 mt-6">
        <!-- Header -->
        <div class="mb-8 flex flex-col md:flex-row justify-between items-start md:items-center gap-4">
            <div>
                <a href="{{ route('tasks.index') }}" class="inline-flex items-center gap-2 text-sm text-secondary hover:text-white transition-colors mb-4">
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18"></path></svg>
                    Back to Tasks
                </a>
                <h1 class="text-3xl font-display font-bold text-white flex items-center gap-3">
                    <span class="material-icons text-primary text-3xl">task</span>
                    Task Details
                </h1>
            </div>
            
            @hasanyrole('admin|manager')
            <div class="flex gap-3">
                <a href="{{ route('tasks.edit', $task) }}" class="flex items-center gap-2 px-4 py-2 rounded-xl text-sm font-bold bg-background border border-secondary/30 text-text hover:text-white hover:border-primary/50 transition-all duration-300">
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M15.232 5.232l3.536 3.536m-2.036-5.036a2.5 2.5 0 113.536 3.536L6.5 21.036H3v-3.572L16.732 3.732z"></path></svg>
                    Edit
                </a>
                <form action="{{ route('tasks.destroy', $task) }}" method="POST" onsubmit="return confirm('Are you sure you want to delete this task?');">
                    @csrf
                    @method('DELETE')
                    <button type="submit" class="flex items-center gap-2 px-4 py-2 rounded-xl text-sm font-bold bg-red-500/10 border border-red-500/20 text-red-400 hover:bg-red-500/20 transition-all duration-300">
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"></path></svg>
                        Delete
                    </button>
                </form>
            </div>
            @endhasanyrole
        </div>

        <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
            <!-- Left Column: Main Content -->
            <div class="lg:col-span-2 space-y-6">
                <!-- Basic Info Card -->
                <div class="bg-card-dark rounded-2xl border border-secondary/30 p-6 md:p-8 shadow-[0_0_20px_rgba(0,0,0,0.5)]">
                    <div class="flex flex-wrap gap-3 mb-6">
                        <!-- Priority Badge -->
                        @if($task->priority === 'high')
                            <span class="bg-red-500/20 text-red-400 border border-red-500/20 rounded-full px-3 py-1 text-xs font-bold uppercase tracking-wider">Urgent (High)</span>
                        @elseif($task->priority === 'normal')
                            <span class="bg-orange-500/20 text-orange-400 border border-orange-500/20 rounded-full px-3 py-1 text-xs font-bold uppercase tracking-wider">Normal</span>
                        @else
                            <span class="bg-green-500/20 text-green-400 border border-green-500/20 rounded-full px-3 py-1 text-xs font-bold uppercase tracking-wider">Low</span>
                        @endif

                        <!-- Status Badge -->
                        @hasanyrole('admin|manager')
                            <form action="{{ route('tasks.update-status', $task) }}" method="POST" class="inline-block m-0 p-0">
                                @csrf
                                @method('PATCH')
                                @if($task->status === 'completed')
                                    <input type="hidden" name="status" value="pending">
                                    <button type="submit" class="bg-green-500/20 text-green-400 border border-green-500/20 hover:bg-green-500/30 rounded-full px-3 py-1 text-xs font-bold uppercase tracking-wider shadow-[0_0_5px_rgba(74,222,128,0.3)] cursor-pointer transition-colors" title="Click to mark as Pending">
                                        Completed
                                    </button>
                                @else
                                    <input type="hidden" name="status" value="completed">
                                    <button type="submit" class="bg-[#e4ba00]/20 text-[#e4ba00] border border-[#e4ba00]/30 hover:bg-[#e4ba00]/30 rounded-full px-3 py-1 text-xs font-bold uppercase tracking-wider shadow-[0_0_5px_rgba(228,186,0,0.3)] cursor-pointer transition-colors" title="Click to mark as Completed">
                                        Pending
                                    </button>
                                @endif
                            </form>
                        @else
                            @if($task->status === 'completed')
                                <span class="bg-green-500/20 text-green-400 border border-green-500/20 rounded-full px-3 py-1 text-xs font-bold uppercase tracking-wider shadow-[0_0_5px_rgba(74,222,128,0.3)]">Completed</span>
                            @else
                                <span class="bg-[#e4ba00]/20 text-[#e4ba00] border border-[#e4ba00]/30 rounded-full px-3 py-1 text-xs font-bold uppercase tracking-wider shadow-[0_0_5px_rgba(228,186,0,0.3)]">Pending</span>
                            @endif
                        @endhasanyrole
                    </div>

                    <h2 class="text-2xl font-bold text-white mb-4">{{ $task->title }}</h2>
                    
                    <div class="prose prose-invert max-w-none text-secondary">
                        <p class="whitespace-pre-wrap">{{ $task->description ?: 'No description provided for this task.' }}</p>
                    </div>

                    @php
                        $attachments = $task->getMedia('task_attachments');
                    @endphp
                    
                    @if($attachments->count() > 0)
                        <div class="mt-8 border-t border-secondary/20 pt-6">
                            <h3 class="text-lg font-bold text-white mb-4 flex items-center gap-2">
                                <svg class="w-5 h-5 text-accent" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15.172 7l-6.586 6.586a2 2 0 102.828 2.828l6.414-6.586a4 4 0 00-5.656-5.656l-6.415 6.585a6 6 0 108.486 8.486L20.5 13"></path></svg>
                                Attached Files
                            </h3>
                            <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                                @foreach($attachments as $attachment)
                                    <a href="{{ route('tasks.attachment', ['task' => $task->id, 'media' => $attachment->id]) }}" target="_blank" class="flex items-center gap-3 p-3 rounded-xl bg-background/50 border border-secondary/20 hover:border-accent/40 transition-all hover:bg-background/80 group">
                                        <div class="w-10 h-10 rounded-lg bg-primary/10 flex items-center justify-center text-primary group-hover:bg-primary/20 transition-colors">
                                            @if(Str::startsWith($attachment->mime_type, 'image/'))
                                                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z"></path></svg>
                                            @elseif(Str::startsWith($attachment->mime_type, 'application/pdf'))
                                                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M7 21h10a2 2 0 002-2V9.414a1 1 0 00-.293-.707l-5.414-5.414A1 1 0 0012.586 3H7a2 2 0 00-2 2v14a2 2 0 002 2z"></path></svg>
                                            @else
                                                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"></path></svg>
                                            @endif
                                        </div>
                                        <div class="flex-1 min-w-0">
                                            <p class="text-sm font-medium text-white truncate">{{ $attachment->file_name }}</p>
                                            <p class="text-xs text-secondary">{{ $attachment->human_readable_size }}</p>
                                        </div>
                                        <div class="text-secondary group-hover:text-accent transition-colors flex-shrink-0">
                                            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16v1a3 3 0 003 3h10a3 3 0 003-3v-1m-4-4l-4 4m0 0l-4-4m4 4V4"></path></svg>
                                        </div>
                                    </a>
                                @endforeach
                            </div>
                        </div>
                    @endif
                </div>


            
<!-- Map Card -->
                <div class="bg-card-dark rounded-2xl border border-secondary/30 p-6 shadow-[0_0_20px_rgba(0,0,0,0.5)]">
                    <h3 class="text-lg font-bold text-white mb-4 flex items-center gap-2">
                        <svg class="w-5 h-5 text-accent" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314 0z"></path><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 11a3 3 0 11-6 0 3 3 0 016 0z"></path></svg>
                        Task Location
                    </h3>
                    
                    @if($task->auditPoint)
                        <div class="w-full rounded-xl overflow-hidden border border-secondary/30 relative z-0" style="height: 300px;">
                            <div id="taskMap" class="absolute inset-0"></div>
                        </div>
                        <p class="text-sm text-secondary mt-3">
                            <span class="font-medium text-text">Location:</span> {{ $task->auditPoint->name }}
                        </p>
                    @else
                        <div class="flex flex-col items-center justify-center p-8 bg-background/50 rounded-xl border border-secondary/20">
                            <svg class="w-12 h-12 text-secondary mb-3" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z"></path></svg>
                            <p class="text-secondary text-center">No location information for this task.</p>
                        </div>
                    @endif
                </div>
</div>

            <!-- Right Column: Sidebar -->
            <div class="space-y-6">
                <!-- Personnel Info -->
                <div class="bg-card-dark rounded-2xl border border-secondary/30 p-6 shadow-[0_0_20px_rgba(0,0,0,0.5)]">
                    <h3 class="text-lg font-bold text-white mb-4">Personnel Info</h3>
                    
                    <div class="space-y-4">
                        <div>
                            <p class="text-xs text-secondary mb-1">Assigned Personnel</p>
                            <div class="flex items-center gap-3">
                                <div class="w-10 h-10 rounded-full bg-primary/20 flex items-center justify-center text-primary font-bold">
                                    {{ $task->assignedUser ? substr($task->assignedUser->name, 0, 1) : '?' }}
                                </div>
                                <div>
                                    <p class="text-sm font-semibold text-white">{{ $task->assignedUser->name ?? 'Not Assigned' }}</p>
                                    <p class="text-xs text-secondary">{{ $task->assignedUser->email ?? 'Pool Task' }}</p>
                                </div>
                            </div>
                        </div>
                        
                        <div class="border-t border-secondary/20 pt-4">
                            <p class="text-xs text-secondary mb-1">Created By (Manager)</p>
                            <div class="flex items-center gap-3">
                                <div class="w-10 h-10 rounded-full bg-secondary/20 flex items-center justify-center text-text font-bold">
                                    {{ $task->manager ? substr($task->manager->name, 0, 1) : 'S' }}
                                </div>
                                <div>
                                    <p class="text-sm font-semibold text-white">{{ $task->manager->name ?? 'System' }}</p>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Date Info -->
                <div class="bg-card-dark rounded-2xl border border-secondary/30 p-6 shadow-[0_0_20px_rgba(0,0,0,0.5)]">
                    <h3 class="text-lg font-bold text-white mb-4">Timeline</h3>
                    
                    <div class="space-y-4">
                        <div class="flex items-start gap-3">
                            <div class="mt-0.5 text-primary">
                                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"></path></svg>
                            </div>
                            <div>
                                <p class="text-xs text-secondary">Creation Date</p>
                                <p class="text-sm font-medium text-white">{{ $task->created_at->format('d M Y, H:i') }}</p>
                            </div>
                        </div>
                        
                        <div class="flex items-start gap-3">
                            <div class="mt-0.5 {{ $task->due_date && $task->due_date < now() && $task->status !== 'completed' ? 'text-red-500' : 'text-accent' }}">
                                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"></path></svg>
                            </div>
                            <div>
                                <p class="text-xs text-secondary">Due Date</p>
                                <p class="text-sm font-medium {{ $task->due_date && $task->due_date < now() && $task->status !== 'completed' ? 'text-red-400' : 'text-white' }}">
                                    {{ $task->due_date ? $task->due_date->format('d M Y, H:i') : 'Not Specified' }}
                                </p>
                            </div>
                        </div>
                    </div>
                </div>

            @if(auth()->user()->hasRole('field_personnel') && !auth()->user()->hasAnyRole(['admin', 'manager']) && $task->status === 'pending')
                @if($task->assigned_to === null)
                    <!-- Görevi Devral -->
                    <div class="bg-card-dark rounded-2xl border border-secondary/30 p-6 shadow-[0_0_20px_rgba(0,0,0,0.5)]">
                        <h3 class="text-lg font-bold text-white mb-2">Görev İşlemleri</h3>
                        <p class="text-sm text-secondary mb-4">Bu görev şu an havuzda. Üstlenmek için aşağıdaki butona tıklayın.</p>
                        <form action="{{ route('tasks.claim', $task) }}" method="POST">
                            @csrf
                            <button type="submit" class="w-full py-3 rounded-xl text-sm font-bold bg-[#a4d756] hover:bg-[#91c342] text-gray-900 shadow-[0_0_15px_rgba(164,215,86,0.4)] hover:-translate-y-1 transition-all duration-300">
                                Görevi Devral
                            </button>
                        </form>
                    </div>
                @elseif($task->assigned_to === auth()->id())
                    <!-- Görevi Tamamla -->
                    <div class="bg-card-dark rounded-2xl border border-secondary/30 p-6 shadow-[0_0_20px_rgba(0,0,0,0.5)]">
                        <h3 class="text-lg font-bold text-white mb-2">Görevi Tamamla</h3>
                        <p class="text-sm text-secondary mb-4">Görevi tamamlamak için bir kanıt fotoğrafı yükleyin ve bulunduğunuz konumu doğrulayın.</p>
                        
                        <form id="completeTaskForm" action="{{ route('tasks.complete', $task) }}" method="POST" enctype="multipart/form-data" class="space-y-4">
                            @csrf
                            <div>
                                <label for="proof_photo" class="block text-sm font-semibold text-text mb-2">Kanıt Fotoğrafı <span class="text-red-500">*</span></label>
                                <input type="file" id="proof_photo" name="proof_photo" accept="image/*" required
                                       class="block w-full text-sm text-secondary file:mr-4 file:py-2 file:px-4 file:rounded-xl file:border-0 file:text-sm file:font-bold file:bg-primary/10 file:text-primary hover:file:bg-primary/20 bg-background border border-secondary/30 rounded-xl cursor-pointer focus:outline-none transition-all">
                                <p class="text-xs text-secondary mt-2">İzin verilen formatlar: JPG, PNG. Maksimum: 10MB (Sunucu limitlerine bağlıdır)</p>
                                @error('proof_photo')
                                    <p class="text-red-400 text-xs mt-1">{{ $message }}</p>
                                @enderror
                            </div>
                            
                            <input type="hidden" id="latitude" name="latitude">
                            <input type="hidden" id="longitude" name="longitude">
                            
                            <div id="locationStatus" class="text-xs text-orange-400 mb-2 flex items-center gap-1">
                                <svg class="w-4 h-4 animate-spin" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 4v5h.582m15.356 2A8.001 8.001 0 004.582 9m0 0H9m11 11v-5h-.581m0 0a8.003 8.003 0 01-15.357-2m15.357 2H15"></path></svg>
                                Konumunuz alınıyor, lütfen bekleyin...
                            </div>
                            
                            <button type="button" id="holdToConfirmBtn" data-default-text="Basılı Tutarak Tamamla" data-bg-class="bg-green-500" data-shadow-class="shadow-[0_0_20px_rgba(34,197,94,0.8)]" disabled class="relative overflow-hidden w-full flex items-center justify-center gap-2 py-3 rounded-xl text-sm font-bold border-2 border-green-500/40 bg-background/50 text-white shadow-lg opacity-50 cursor-not-allowed transition-all duration-300 group">
                                <div id="holdProgress" class="absolute left-0 top-0 bottom-0 w-0 bg-green-500/90"></div>
                                <span class="relative z-10 flex items-center gap-2" id="holdContent">
                                    <svg class="w-5 h-5" fill="none" stroke="currentColor" stroke-width="2.5" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M5 13l4 4L19 7"></path></svg>
                                    <span id="holdText">Basılı Tutarak Tamamla</span>
                                </span>
                            </button>
                            @if(session('error'))
                                <p class="text-red-400 text-xs mt-2 font-medium">{{ session('error') }}</p>
                            @endif
                        </form>
                    </div>
                @endif
            @endif
            </div>

            </div>
    </div>

    @push('scripts')
        <script defer src="{{ asset('js/map-helper.js') }}?v={{ time() }}"></script>
        <script defer src="{{ asset('js/hold-to-confirm.js') }}?v={{ time() }}"></script>
        @if($task->auditPoint)
        <script>
            document.addEventListener('DOMContentLoaded', function () {
                const mapElement = document.getElementById('taskMap');
                if (mapElement) {
                    const lat = @json($task->auditPoint->latitude);
                    const lng = @json($task->auditPoint->longitude);
                    
                    var map = window.SahaMapHelper.init('taskMap', [lat, lng], 15);
                    const customIcon = window.SahaMapHelper.getCustomPinIcon();
                    L.marker([lat, lng], {icon: customIcon}).addTo(map);
                }

                // Geolocation logic for Field Personnel task completion
                const latInput = document.getElementById('latitude');
                const lngInput = document.getElementById('longitude');
                const statusDiv = document.getElementById('locationStatus');
                const btn = document.getElementById('holdToConfirmBtn');
                
                if (latInput && lngInput && statusDiv && btn) {
                    if ("geolocation" in navigator) {
                        navigator.geolocation.getCurrentPosition(
                            function(position) {
                                latInput.value = position.coords.latitude;
                                lngInput.value = position.coords.longitude;
                                statusDiv.innerHTML = '<svg class="w-4 h-4 text-green-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path></svg> Konum doğrulandı. Tamamlayabilirsiniz.';
                                statusDiv.className = 'text-xs text-green-400 mb-2 flex items-center gap-1';
                                btn.disabled = false;
                                btn.classList.remove('opacity-50', 'cursor-not-allowed');
                            },
                            function(error) {
                                let msg = "Konum alınamadı. (Lütfen konum erişimine izin verin)";
                                if(error.code === error.PERMISSION_DENIED) msg = "Konum izni reddedildi. Görevi tamamlamak için tarayıcı ayarlarından konum erişimine izin vermelisiniz.";
                                statusDiv.innerHTML = '<svg class="w-4 h-4 text-red-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z"></path></svg> ' + msg;
                                statusDiv.className = 'text-xs text-red-400 mb-2 flex items-start gap-1';
                            },
                            { enableHighAccuracy: true, timeout: 10000, maximumAge: 0 }
                        );
                    } else {
                        statusDiv.innerText = "Tarayıcınız konum özelliğini desteklemiyor.";
                        statusDiv.className = 'text-xs text-red-400 mb-2';
                    }
                }
            });
        </script>
        @endif
    @endpush
</x-app-layout>
