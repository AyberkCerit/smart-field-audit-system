<x-guest-layout>
    <div class="mb-8 mt-4">
        <h2 class="text-4xl font-display font-bold text-white mb-2">Register</h2>
        <p class="text-neutral-400">Create a new account to access the system.</p>
    </div>
    
    <form method="POST" action="{{ route('register') }}" class="space-y-4 flex-grow">
        @csrf
        
        <div class="space-y-1">
            <label class="text-xs font-bold uppercase tracking-widest text-neutral-500 ml-1">Name</label>
            <div class="relative">
                <span class="material-icons absolute left-4 top-1/2 -translate-y-1/2 text-neutral-500 text-xl">badge</span>
                <input class="w-full bg-neutral-800 border-none rounded-2xl py-3 pl-12 pr-4 text-white focus:ring-2 focus:ring-accent transition-all placeholder:text-neutral-600" 
                       placeholder="John Doe" 
                       type="text" 
                       name="name" 
                       value="{{ old('name') }}" 
                       required 
                       autofocus>
            </div>
            <x-input-error :messages="$errors->get('name')" class="mt-1 text-red-500 text-xs ml-1" />
        </div>

        <div class="space-y-1">
            <label class="text-xs font-bold uppercase tracking-widest text-neutral-500 ml-1">Email</label>
            <div class="relative">
                <span class="material-icons absolute left-4 top-1/2 -translate-y-1/2 text-neutral-500 text-xl">email</span>
                <input class="w-full bg-neutral-800 border-none rounded-2xl py-3 pl-12 pr-4 text-white focus:ring-2 focus:ring-accent transition-all placeholder:text-neutral-600" 
                       placeholder="admin@sahadenetim.com" 
                       type="email" 
                       name="email" 
                       value="{{ old('email') }}" 
                       required>
            </div>
            <x-input-error :messages="$errors->get('email')" class="mt-1 text-red-500 text-xs ml-1" />
        </div>
        
        <div class="space-y-1">
            <label class="text-xs font-bold uppercase tracking-widest text-neutral-500 ml-1">Password</label>
            <div class="relative">
                <span class="material-icons absolute left-4 top-1/2 -translate-y-1/2 text-neutral-500 text-xl">lock</span>
                <input id="password-input" 
                       class="w-full bg-neutral-800 border-none rounded-2xl py-3 pl-12 pr-12 text-white focus:ring-2 focus:ring-accent transition-all placeholder:text-neutral-600" 
                       placeholder="••••••••" 
                       type="password" 
                       name="password" 
                       required>
                <button id="toggle-password" class="absolute right-4 top-1/2 -translate-y-1/2 text-neutral-500 hover:text-white" type="button">
                    <span class="material-icons text-xl" id="toggle-icon">visibility</span>
                </button>
            </div>
            <x-input-error :messages="$errors->get('password')" class="mt-1 text-red-500 text-xs ml-1" />
        </div>

        <div class="space-y-1">
            <label class="text-xs font-bold uppercase tracking-widest text-neutral-500 ml-1">Confirm Password</label>
            <div class="relative">
                <span class="material-icons absolute left-4 top-1/2 -translate-y-1/2 text-neutral-500 text-xl">lock_outline</span>
                <input id="password-confirm-input" 
                       class="w-full bg-neutral-800 border-none rounded-2xl py-3 pl-12 pr-12 text-white focus:ring-2 focus:ring-accent transition-all placeholder:text-neutral-600" 
                       placeholder="••••••••" 
                       type="password" 
                       name="password_confirmation" 
                       required>
                <button id="toggle-password-confirm" class="absolute right-4 top-1/2 -translate-y-1/2 text-neutral-500 hover:text-white" type="button">
                    <span class="material-icons text-xl" id="toggle-icon-confirm">visibility</span>
                </button>
            </div>
            <x-input-error :messages="$errors->get('password_confirmation')" class="mt-1 text-red-500 text-xs ml-1" />
        </div>
        
        <button class="w-full bg-primary hover:bg-blue-600 text-white font-bold py-4 rounded-2xl glow-button flex items-center justify-center gap-2 mt-6" type="submit">
            SIGN UP
            <span class="material-icons">arrow_forward</span>
        </button>
    </form>
    
    <div class="mt-4 pb-2 text-center">
        <p class="text-sm text-neutral-500">
            Already have an account? 
            <a class="text-accent font-semibold hover:underline" href="{{ route('login') }}">Sign In</a>
        </p>
    </div>
</x-guest-layout>
