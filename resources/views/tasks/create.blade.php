<x-app-layout>
    @push('styles')
        <link href="{{ asset('css/tasks.css') }}?v={{ time() }}" rel="stylesheet">
    @endpush

    <div class="w-full max-w-4xl mx-auto mb-10 mt-6">
        <!-- Header -->
        <div class="mb-8">
            <a href="{{ route('tasks.index') }}" class="inline-flex items-center gap-2 text-sm text-secondary hover:text-white transition-colors mb-4">
                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18"></path></svg>
                Görevlere Dön
            </a>
            <h1 class="text-3xl font-display font-bold text-white flex items-center gap-3">
                <span class="material-icons text-primary text-3xl">add_circle</span>
                Yeni Görev Oluştur
            </h1>
            <p class="text-secondary mt-1">Saha personeli için yeni bir görev atayın ve haritadan görev bölgesini seçin.</p>
        </div>

        <!-- Form Card -->
        <div class="bg-card-dark rounded-2xl border border-secondary/30 p-6 md:p-8 shadow-[0_0_20px_rgba(0,0,0,0.5)]">
            <form action="{{ route('tasks.store') }}" method="POST" enctype="multipart/form-data" class="space-y-6">
                @csrf

                <!-- Başlık -->
                <div>
                    <label for="title" class="block text-sm font-semibold text-text mb-2">Görev Başlığı <span class="text-red-500">*</span></label>
                    <input type="text" id="title" name="title" value="{{ old('title') }}" required autofocus
                           class="w-full bg-background border border-secondary/30 rounded-xl px-4 py-3 text-text focus:border-primary focus:ring-1 focus:ring-primary transition-all @error('title') border-red-500 focus:border-red-500 focus:ring-red-500 @enderror"
                           placeholder="Örn: Kuzey Parkı Aydınlatma Denetimi">
                    @error('title')
                        <p class="text-red-400 text-xs mt-1">{{ $message }}</p>
                    @enderror
                </div>

                <!-- Açıklama -->
                <div>
                    <label for="description" class="block text-sm font-semibold text-text mb-2">Görev Açıklaması</label>
                    <textarea id="description" name="description" rows="4" 
                              class="w-full bg-background border border-secondary/30 rounded-xl px-4 py-3 text-text focus:border-primary focus:ring-1 focus:ring-primary transition-all @error('description') border-red-500 focus:border-red-500 focus:ring-red-500 @enderror"
                              placeholder="Görevle ilgili detaylı talimatları buraya yazabilirsiniz...">{{ old('description') }}</textarea>
                    @error('description')
                        <p class="text-red-400 text-xs mt-1">{{ $message }}</p>
                    @enderror
                </div>

                <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                    
                    <!-- Haritadan Lokasyon Seçimi -->
                    <div class="md:col-span-2">
                        <label class="block text-sm font-semibold text-text mb-2">Görev Alanını Haritadan Seçin <span class="text-red-500">*</span></label>
                        <p class="text-xs text-secondary mb-3">Lütfen harita üzerinde tıklayarak görevin yapılacağı konumu işaretleyin.</p>
                        
                        <!-- Hidden inputs for coordinates -->
                        <input type="hidden" id="latitude" name="latitude" value="{{ old('latitude') }}">
                        <input type="hidden" id="longitude" name="longitude" value="{{ old('longitude') }}">
                        
                        <!-- Map Container -->
                        <div class="w-full h-[300px] rounded-xl overflow-hidden border border-secondary/30 relative z-0 transition-all @error('latitude') border-red-500 shadow-[0_0_10px_rgba(239,68,68,0.3)] @enderror">
                            <div id="taskMap" class="absolute inset-0"></div>
                        </div>
                        
                        @error('latitude')
                            <p class="text-red-400 text-xs mt-2">Lütfen harita üzerinden geçerli bir lokasyon (pin) seçin.</p>
                        @enderror
                    </div>

                    <!-- Atanan Personel -->
                    <div>
                        <label for="assigned_to" class="block text-sm font-semibold text-text mb-2">Atanacak Personel</label>
                        <select id="assigned_to" name="assigned_to"
                                class="w-full bg-background border border-secondary/30 rounded-xl px-4 py-3 text-text focus:border-primary focus:ring-1 focus:ring-primary transition-all @error('assigned_to') border-red-500 focus:border-red-500 focus:ring-red-500 @enderror">
                            <option value="">Atama Yapılmayacak (Havuz)</option>
                            @foreach($users as $user)
                                <option value="{{ $user->id }}" {{ old('assigned_to') == $user->id ? 'selected' : '' }}>
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
                        <label for="priority" class="block text-sm font-semibold text-text mb-2">Öncelik Durumu <span class="text-red-500">*</span></label>
                        <select id="priority" name="priority" required
                                class="w-full bg-background border border-secondary/30 rounded-xl px-4 py-3 text-text focus:border-primary focus:ring-1 focus:ring-primary transition-all @error('priority') border-red-500 focus:border-red-500 focus:ring-red-500 @enderror">
                            <option value="low" {{ old('priority') == 'low' ? 'selected' : '' }}>Düşük</option>
                            <option value="normal" {{ old('priority', 'normal') == 'normal' ? 'selected' : '' }}>Normal</option>
                            <option value="high" {{ old('priority') == 'high' ? 'selected' : '' }}>Acil (Yüksek)</option>
                        </select>
                        @error('priority')
                            <p class="text-red-400 text-xs mt-1">{{ $message }}</p>
                        @enderror
                    </div>

                    <!-- Dosya Yükleme -->
                    <div>
                        <label for="attachment" class="block text-sm font-semibold text-text mb-2">Görev Belgesi / Görseli (Opsiyonel)</label>
                        <div class="relative w-full">
                            <input type="file" id="attachment" name="attachment" accept=".jpg,.jpeg,.png,.pdf"
                                   class="block w-full text-sm text-secondary file:mr-4 file:py-3 file:px-4 file:rounded-l-xl file:border-0 file:text-sm file:font-bold file:bg-primary/10 file:text-primary hover:file:bg-primary/20 bg-background border border-secondary/30 rounded-xl cursor-pointer focus:outline-none focus:border-primary focus:ring-1 focus:ring-primary transition-all @error('attachment') border-red-500 @enderror" />
                        </div>
                        <p class="text-xs text-secondary mt-2">İzin verilen formatlar: JPG, PNG, PDF. Maks: 10MB</p>
                        @error('attachment')
                            <p class="text-red-400 text-xs mt-1">{{ $message }}</p>
                        @enderror
                    </div>

                    <!-- Son Tarih -->
                    <div>
                        <label for="due_date" class="block text-sm font-semibold text-text mb-2">Bitiş Tarihi (Opsiyonel)</label>
                        <div class="relative">
                            <svg class="w-5 h-5 absolute left-4 top-1/2 -translate-y-1/2 text-secondary pointer-events-none" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"></path></svg>
                            <input type="date" id="due_date" name="due_date" value="{{ old('due_date') }}"
                                   class="w-full bg-background border border-secondary/30 rounded-xl pl-12 pr-4 py-3 text-text focus:border-primary focus:ring-1 focus:ring-primary transition-all @error('due_date') border-red-500 focus:border-red-500 focus:ring-red-500 @enderror [color-scheme:dark]">
                        </div>
                        @error('due_date')
                            <p class="text-red-400 text-xs mt-1">{{ $message }}</p>
                        @enderror
                    </div>
                </div>

                <!-- Submit Buttons -->
                <div class="flex items-center justify-end gap-4 pt-6 border-t border-secondary/20 mt-8">
                    <a href="{{ route('tasks.index') }}" class="px-6 py-3 rounded-xl text-sm font-semibold text-text hover:text-white hover:bg-white/5 transition-all">
                        İptal
                    </a>
                    <button type="submit" class="flex items-center gap-2 px-8 py-3 rounded-xl text-sm font-bold bg-primary hover:bg-primary/80 text-white shadow-[0_0_15px_rgba(27,95,197,0.4)] hover:shadow-[0_0_20px_rgba(27,95,197,0.6)] hover:-translate-y-1 transition-all duration-300">
                        <svg class="w-5 h-5" fill="none" stroke="currentColor" stroke-width="2.5" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M5 13l4 4L19 7"></path></svg>
                        Görevi Oluştur
                    </button>
                </div>
            </form>
        </div>
    </div>

    @push('scripts')
        <script defer src="{{ asset('js/map-helper.js') }}?v={{ time() }}"></script>
        <script defer src="{{ asset('js/task-map.js') }}?v={{ time() }}"></script>
    @endpush
</x-app-layout>
