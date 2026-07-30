<x-app-layout>
    <div class="py-8">
        <div class="max-w-3xl mx-auto sm:px-6 lg:px-8">
            <div class="mb-6">
                <a href="{{ route('users.index') }}" class="text-secondary hover:text-white flex items-center gap-2 transition-colors">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18"></path></svg>
                    Back to Users
                </a>
            </div>
            
            <div class="bg-card-dark rounded-2xl border border-secondary/30 p-8 shadow-[0_0_20px_rgba(0,0,0,0.5)]">
                <h2 class="text-2xl font-bold text-white mb-6">Add New User</h2>
                
                <form action="{{ route('users.store') }}" method="POST" class="space-y-6">
                    @csrf
                    
                    <div>
                        <label for="name" class="block text-sm font-medium text-gray-300 mb-1">Name</label>
                        <input type="text" name="name" id="name" value="{{ old('name') }}" required
                            class="w-full bg-background border border-secondary/50 rounded-lg px-4 py-2.5 text-white focus:outline-none focus:border-primary focus:ring-1 focus:ring-primary transition-all">
                        @error('name')
                            <p class="text-red-400 text-xs mt-1">{{ $message }}</p>
                        @enderror
                    </div>
                    
                    <div>
                        <label for="email" class="block text-sm font-medium text-gray-300 mb-1">Email</label>
                        <input type="email" name="email" id="email" value="{{ old('email') }}" required
                            class="w-full bg-background border border-secondary/50 rounded-lg px-4 py-2.5 text-white focus:outline-none focus:border-primary focus:ring-1 focus:ring-primary transition-all">
                        @error('email')
                            <p class="text-red-400 text-xs mt-1">{{ $message }}</p>
                        @enderror
                    </div>
                    
                    <div>
                        <label for="password" class="block text-sm font-medium text-gray-300 mb-1">Password</label>
                        <input type="password" name="password" id="password" required
                            class="w-full bg-background border border-secondary/50 rounded-lg px-4 py-2.5 text-white focus:outline-none focus:border-primary focus:ring-1 focus:ring-primary transition-all">
                        @error('password')
                            <p class="text-red-400 text-xs mt-1">{{ $message }}</p>
                        @enderror
                    </div>
                    
                    <div>
                        <label for="role" class="block text-sm font-medium text-gray-300 mb-1">User Role</label>
                        <select id="role" name="role" required
                                class="w-full bg-background border border-secondary/50 rounded-lg px-4 py-2.5 text-white focus:outline-none focus:border-lime-500 focus:ring-1 focus:ring-lime-500 transition-all">
                            <option value="field_personnel" {{ old('role') == 'field_personnel' ? 'selected' : '' }}>Field Personnel</option>
                            <option value="manager" {{ old('role') == 'manager' ? 'selected' : '' }}>Manager</option>
                        </select>
                        @error('role')
                            <p class="text-red-400 text-xs mt-1">{{ $message }}</p>
                        @enderror
                    </div>
                    
                    <div class="flex items-center justify-end gap-4 pt-6 border-t border-secondary/30 mt-8">
                        <a href="{{ route('users.index') }}" class="px-6 py-3 rounded-xl text-sm font-semibold text-text hover:text-white hover:bg-white/5 transition-all">
                            Cancel
                        </a>
                        <button type="button" id="holdToConfirmBtn" 
                                data-bg-class="bg-lime-500" 
                                data-shadow-class="shadow-[0_0_20px_rgba(132,204,22,0.8)]"
                                class="relative overflow-hidden flex items-center gap-2 px-8 py-3 rounded-xl text-sm font-bold border-2 border-lime-500/40 bg-background/50 text-white shadow-lg hover:border-lime-500/80 hover:shadow-[0_0_20px_rgba(132,204,22,0.3)] transition-all duration-300 select-none cursor-pointer group">
                            <!-- Fill Effect (Progress) -->
                            <div id="holdProgress" class="absolute left-0 top-0 bottom-0 w-0 bg-lime-500/90"></div>
                            <!-- Content -->
                            <span class="relative z-10 flex items-center gap-2 transition-transform duration-200" id="holdContent">
                                <svg class="w-5 h-5" fill="none" stroke="currentColor" stroke-width="2.5" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M5 13l4 4L19 7"></path></svg>
                                <span id="holdText">Hold to Create</span>
                            </span>
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>

    @push('scripts')
        <script defer src="{{ asset('js/hold-to-confirm.js') }}?v={{ time() }}"></script>
    @endpush
</x-app-layout>
