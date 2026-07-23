<x-guest-layout>
    <div class="mb-10 mt-4">
        <h2 class="text-4xl font-display font-bold text-white mb-2">Login</h2>
        <p class="text-neutral-400">Enter your credentials to access the system.</p>
    </div>
    
    <!-- Session Status -->
    <x-auth-session-status class="mb-4" :status="session('status')" />

    <form method="POST" action="{{ route('login') }}" class="space-y-6 flex-grow">
        @csrf
        
        <div class="space-y-2">
            <label class="text-xs font-bold uppercase tracking-widest text-neutral-500 ml-1">Email</label>
            <div class="relative">
                <span class="material-icons absolute left-4 top-1/2 -translate-y-1/2 text-neutral-500 text-xl">person</span>
                <input class="w-full bg-neutral-800 border-none rounded-2xl py-4 pl-12 pr-4 text-white focus:ring-2 focus:ring-accent transition-all placeholder:text-neutral-600" 
                       placeholder="admin@sahadenetim.com" 
                       type="email" 
                       name="email" 
                       value="{{ old('email') }}" 
                       required 
                       autofocus>
            </div>
            <x-input-error :messages="$errors->get('email')" class="mt-2 text-red-500 text-xs ml-1" />
        </div>
        
        <div class="space-y-2">
            <div class="flex justify-between items-center px-1">
                <label class="text-xs font-bold uppercase tracking-widest text-neutral-500">Password</label>
                @if (Route::has('password.request'))
                    <a class="text-xs text-accent hover:underline" href="{{ route('password.request') }}">Forgot?</a>
                @endif
            </div>
            <div class="relative">
                <span class="material-icons absolute left-4 top-1/2 -translate-y-1/2 text-neutral-500 text-xl">lock</span>
                <input id="password-input" 
                       class="w-full bg-neutral-800 border-none rounded-2xl py-4 pl-12 pr-12 text-white focus:ring-2 focus:ring-accent transition-all placeholder:text-neutral-600" 
                       placeholder="••••••••" 
                       type="password" 
                       name="password" 
                       required>
                <button id="toggle-password" class="absolute right-4 top-1/2 -translate-y-1/2 text-neutral-500 hover:text-white" type="button">
                    <span class="material-icons text-xl" id="toggle-icon">visibility</span>
                </button>
            </div>
            <x-input-error :messages="$errors->get('password')" class="mt-2 text-red-500 text-xs ml-1" />
        </div>
        
        <div class="flex items-center gap-3 px-1">
            <input class="rounded border-neutral-700 bg-neutral-800 text-primary focus:ring-primary focus:ring-offset-neutral-900" 
                   id="remember" 
                   name="remember" 
                   type="checkbox">
            <label class="text-sm text-neutral-400 select-none" for="remember">Remember this device</label>
        </div>
        
        <button class="w-full bg-primary hover:bg-blue-600 text-white font-bold py-4 rounded-2xl glow-button flex items-center justify-center gap-2 mt-4" type="submit">
            SIGN IN
            <span class="material-icons">arrow_forward</span>
        </button>
    </form>
    
    @if (Route::has('register'))
        <div class="mt-8 text-center">
            <p class="text-sm text-neutral-500">
                Don't have an account? 
                <a class="text-accent font-semibold hover:underline" href="{{ route('register') }}">Request Access</a>
            </p>
        </div>
    @endif
</x-guest-layout>
