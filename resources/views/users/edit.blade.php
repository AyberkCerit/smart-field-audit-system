<x-app-layout>
    <div class="py-8">
        <div class="max-w-3xl mx-auto sm:px-6 lg:px-8">
            <div class="mb-6">
                <a href="{{ route('users.index') }}" class="text-secondary hover:text-white flex items-center gap-2 transition-colors">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18"></path></svg>
                    Kullanıcılara Dön
                </a>
            </div>
            
            <div class="bg-card-dark rounded-2xl border border-secondary/30 p-8 shadow-[0_0_20px_rgba(0,0,0,0.5)]">
                <h2 class="text-2xl font-bold text-white mb-6">Kullanıcı Düzenle: {{ $user->name }}</h2>
                
                <form action="{{ route('users.update', $user) }}" method="POST" class="space-y-6">
                    @csrf
                    @method('PUT')
                    
                    <div>
                        <label for="name" class="block text-sm font-medium text-gray-300 mb-1">İsim</label>
                        <input type="text" name="name" id="name" value="{{ old('name', $user->name) }}" required
                            class="w-full bg-background border border-secondary/50 rounded-lg px-4 py-2.5 text-white focus:outline-none focus:border-primary focus:ring-1 focus:ring-primary transition-all">
                        @error('name')
                            <p class="text-red-400 text-xs mt-1">{{ $message }}</p>
                        @enderror
                    </div>
                    
                    <div>
                        <label for="email" class="block text-sm font-medium text-gray-300 mb-1">E-posta</label>
                        <input type="email" name="email" id="email" value="{{ old('email', $user->email) }}" required
                            class="w-full bg-background border border-secondary/50 rounded-lg px-4 py-2.5 text-white focus:outline-none focus:border-primary focus:ring-1 focus:ring-primary transition-all">
                        @error('email')
                            <p class="text-red-400 text-xs mt-1">{{ $message }}</p>
                        @enderror
                    </div>
                    
                    <div>
                        <label for="password" class="block text-sm font-medium text-gray-300 mb-1">Şifre (Değiştirmek istemiyorsanız boş bırakın)</label>
                        <input type="password" name="password" id="password"
                            class="w-full bg-background border border-secondary/50 rounded-lg px-4 py-2.5 text-white focus:outline-none focus:border-primary focus:ring-1 focus:ring-primary transition-all">
                        @error('password')
                            <p class="text-red-400 text-xs mt-1">{{ $message }}</p>
                        @enderror
                    </div>
                    
                    @php $currentRole = $user->roles->first()?->name; @endphp
                    <div>
                        <label for="role" class="block text-sm font-medium text-gray-300 mb-1">Kullanıcı Rolü</label>
                        <select id="role" name="role" required
                                class="w-full bg-background border border-secondary/50 rounded-lg px-4 py-2.5 text-white focus:outline-none focus:border-primary focus:ring-1 focus:ring-primary transition-all">
                            <option value="field_personnel" {{ old('role', $currentRole) == 'field_personnel' ? 'selected' : '' }}>Saha Personeli</option>
                            <option value="manager" {{ old('role', $currentRole) == 'manager' ? 'selected' : '' }}>Yönetici</option>
                            @role('admin')
                            <option value="admin" {{ old('role', $currentRole) == 'admin' ? 'selected' : '' }}>Admin</option>
                            @endrole
                        </select>
                        @error('role')
                            <p class="text-red-400 text-xs mt-1">{{ $message }}</p>
                        @enderror
                    </div>
                    
                    <div class="flex justify-end pt-4 border-t border-secondary/30">
                        <button type="submit" class="px-6 py-2.5 bg-primary/20 text-primary border border-primary/50 hover:bg-primary hover:text-white rounded-xl font-semibold transition-all shadow-[0_0_15px_rgba(27,95,197,0.3)]">
                            Değişiklikleri Kaydet
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</x-app-layout>
