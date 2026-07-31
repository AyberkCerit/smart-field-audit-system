<!-- Navbar Container -->
<div class="fixed top-6 left-0 w-full flex justify-center px-6 z-[9999] pointer-events-none">
    
    <!-- Navbar Content -->
    <header class="w-full max-w-6xl h-16 bg-primary rounded-2xl flex items-center justify-between px-6 shadow-md pointer-events-auto transition-all duration-300 text-white animate-expand">
        
        <a href="{{ route('dashboard') }}" class="flex items-center gap-3 cursor-pointer group">
            <!-- Logo Icon -->
            <div class="w-9 h-9 rounded-xl bg-white/10 border border-white/30 flex items-center justify-center font-bold text-white shadow-[0_0_15px_rgba(0,0,0,0.1)] text-sm tracking-widest transition-all group-hover:scale-105">
                SD
            </div>
            <!-- Brand Name -->
            <span class="text-xl font-bold text-white tracking-wide group-hover:opacity-80 transition-opacity">FieldAudit</span>
        </a>

        <nav class="hidden md:flex items-center gap-2">
            <!-- Tasks Button (All Roles) -->
            <a href="{{ route('tasks.index') }}" class="flex items-center gap-2 px-4 py-2 rounded-lg text-sm font-semibold text-white hover:bg-white/10 hover:shadow-[0_0_15px_rgba(0,0,0,0.1)] transition-all">
                <svg class="w-5 h-5 opacity-90" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2m-6 9l2 2 4-4"></path></svg>
                Tasks
            </a>
            
            <!-- Map Button (All Roles) -->
            <a href="{{ route('audit-points.index') }}" class="flex items-center gap-2 px-4 py-2 rounded-lg text-sm font-semibold transition-all duration-300 {{ request()->routeIs('audit-points.index') ? 'text-white bg-white/10 shadow-[0_0_15px_rgba(255,255,255,0.1)]' : 'text-white hover:bg-white/10 hover:shadow-[0_0_15px_rgba(0,0,0,0.1)]' }}">
                <svg class="w-5 h-5 {{ request()->routeIs('audit-points.index') ? '' : 'opacity-90' }}" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 20l-5.447-2.724A1 1 0 013 16.382V5.618a1 1 0 011.447-.894L9 7m0 13l6-3m-6 3V7m6 10l4.553 2.276A1 1 0 0021 18.382V7.618a1 1 0 00-.553-.894L15 4m0 13V4m0 0L9 7"></path></svg>
                Map
            </a>
            
            <!-- Activity Log Button (All Roles) -->
            <a href="{{ route('activity-logs.index') }}" class="flex items-center gap-2 px-4 py-2 rounded-lg text-sm font-semibold transition-all duration-300 {{ request()->routeIs('activity-logs.index') ? 'text-white bg-white/10 shadow-[0_0_15px_rgba(255,255,255,0.1)]' : 'text-white hover:bg-white/10 hover:shadow-[0_0_15px_rgba(0,0,0,0.1)]' }}">
                <svg class="w-5 h-5 {{ request()->routeIs('activity-logs.index') ? '' : 'opacity-90' }}" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"></path></svg>
                Activity Log
            </a>

            @hasanyrole('admin|manager')
            <!-- Create Task Button (Admin & Manager) -->
            <a href="{{ route('tasks.create') }}" class="flex items-center gap-2 px-4 py-2 rounded-lg text-sm font-bold bg-white text-primary hover:bg-gray-100 hover:scale-105 shadow-md hover:shadow-lg transition-all duration-300">
                <svg class="w-5 h-5" fill="none" stroke="currentColor" stroke-width="2.5" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M12 4v16m8-8H4"></path></svg>
                Create Task
            </a>
            @endhasanyrole
            
            @role('admin')
            <!-- Users Button (Only Admin) -->
            <a href="{{ route('users.index') }}" class="flex items-center gap-2 px-4 py-2 rounded-lg text-sm font-semibold transition-all duration-300 {{ request()->routeIs('users.*') ? 'text-white bg-white/10 shadow-[0_0_15px_rgba(255,255,255,0.1)]' : 'text-white hover:bg-white/10 hover:shadow-[0_0_15px_rgba(0,0,0,0.1)]' }}">
                <svg class="w-5 h-5 {{ request()->routeIs('users.*') ? '' : 'opacity-90' }}" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4.354a4 4 0 110 5.292M15 21H3v-1a6 6 0 0112 0v1zm0 0h6v-1a6 6 0 00-9-5.197M13 7a4 4 0 11-8 0 4 4 0 018 0z"></path></svg>
                Users
            </a>

            <!-- Settings Button (Only Admin) -->
            <a href="{{ route('settings.index') }}" class="flex items-center gap-2 px-4 py-2 rounded-lg text-sm font-semibold transition-all duration-300 {{ request()->routeIs('settings.*') ? 'text-white bg-white/10 shadow-[0_0_15px_rgba(255,255,255,0.1)]' : 'text-white hover:bg-white/10 hover:shadow-[0_0_15px_rgba(0,0,0,0.1)]' }}">
                <svg class="w-5 h-5 {{ request()->routeIs('settings.*') ? '' : 'opacity-90' }}" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10.325 4.317c.426-1.756 2.924-1.756 3.35 0a1.724 1.724 0 002.573 1.066c1.543-.94 3.31.826 2.37 2.37a1.724 1.724 0 001.065 2.572c1.756.426 1.756 2.924 0 3.35a1.724 1.724 0 00-1.066 2.573c.94 1.543-.826 3.31-2.37 2.37a1.724 1.724 0 00-2.572 1.065c-.426 1.756-2.924 1.756-3.35 0a1.724 1.724 0 00-2.573-1.066c-1.543.94-3.31-.826-2.37-2.37a1.724 1.724 0 00-1.065-2.572c-1.756-.426-1.756-2.924 0-3.35a1.724 1.724 0 001.066-2.573c-.94-1.543.826-3.31 2.37-2.37.996.608 2.296.07 2.572-1.065z"></path><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"></path></svg>
                Settings
            </a>
            @endrole
        </nav>

        <div class="flex items-center gap-3">
            <!-- Notification Button -->
            <button class="relative p-2.5 text-white hover:bg-white/10 transition-all rounded-xl">
                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 17h5l-1.405-1.405A2.032 2.032 0 0118 14.158V11a6.002 6.002 0 00-4-5.659V5a2 2 0 10-4 0v.341C7.67 6.165 6 8.388 6 11v3.159c0 .538-.214 1.055-.595 1.436L4 17h5m6 0v1a3 3 0 11-6 0v-1m6 0H9"></path></svg>
                <!-- Notification Badge -->
                <span class="absolute top-2 right-2 w-2.5 h-2.5 bg-red-500 rounded-full border border-background shadow-[0_0_5px_rgba(239,68,68,0.8)]"></span>
            </button>
            
            <!-- Vertical Divider -->
            <div class="w-px h-6 bg-white/20 hidden md:block"></div>

            <!-- User Profile & Logout Menu -->
            <div class="relative group">
                <button class="flex items-center gap-2 hover:opacity-80 transition-opacity">
                    <div class="w-9 h-9 rounded-full bg-white/10 border border-white/20 overflow-hidden shadow-[0_0_10px_rgba(0,0,0,0.1)] flex items-center justify-center font-bold text-white text-sm">
                        @auth
                            {{ strtoupper(substr(Auth::user()->name, 0, 2)) }}
                        @else
                            AD
                        @endauth
                    </div>
                </button>
                
                <!-- Dropdown Wrapper -->
                <div class="absolute right-0 top-full pt-2 w-48 opacity-0 invisible group-hover:opacity-100 group-hover:visible transition-all duration-300 z-[9999]">
                    <div class="bg-background border border-primary/40 rounded-xl shadow-[0_0_15px_rgba(27,95,197,0.3)] overflow-hidden">
                        <div class="p-2 border-b border-secondary/30">
                            <p class="text-sm font-bold text-text px-2">@auth {{ Auth::user()->name }} @endauth</p>
                        </div>
                        <div class="p-2">
                            <a href="{{ route('profile.edit') }}" class="block px-2 py-2 text-sm text-text/80 hover:text-primary hover:bg-primary/10 rounded-lg transition-colors">Profile</a>
                            <form method="POST" action="{{ route('logout') }}">
                                @csrf
                                <button type="submit" class="w-full text-left block px-2 py-2 text-sm text-red-400 hover:text-red-300 hover:bg-red-400/10 rounded-lg transition-colors cursor-pointer relative z-[9999]">
                                    Log Out
                                </button>
                            </form>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        
    </header>
</div>
