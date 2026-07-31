<x-app-layout>
    <div class="w-full flex flex-col md:flex-row gap-6" x-data="{ activeTab: 'general' }">
        
        <!-- Sidebar Navigation -->
        <div class="w-full md:w-64 flex-shrink-0">
            <div class="bg-card-dark border border-secondary/30 rounded-2xl p-4 shadow-[0_0_20px_rgba(0,0,0,0.5)]">
                <h2 class="text-xl font-bold text-white mb-6 pl-2 border-l-4 border-primary">Settings</h2>
                
                <nav class="space-y-2">
                    <button @click="activeTab = 'general'"
                            :class="activeTab === 'general' ? 'bg-primary/20 text-primary border-primary/50' : 'text-text/80 border-transparent hover:bg-white/5 hover:text-white'"
                            class="w-full flex items-center gap-3 px-4 py-3 rounded-xl border text-left font-semibold transition-all duration-300">
                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10.325 4.317c.426-1.756 2.924-1.756 3.35 0a1.724 1.724 0 002.573 1.066c1.543-.94 3.31.826 2.37 2.37a1.724 1.724 0 001.065 2.572c1.756.426 1.756 2.924 0 3.35a1.724 1.724 0 00-1.066 2.573c.94 1.543-.826 3.31-2.37 2.37a1.724 1.724 0 00-2.572 1.065c-.426 1.756-2.924 1.756-3.35 0a1.724 1.724 0 00-2.573-1.066c-1.543.94-3.31-.826-2.37-2.37a1.724 1.724 0 00-1.065-2.572c-1.756-.426-1.756-2.924 0-3.35a1.724 1.724 0 001.066-2.573c-.94-1.543.826-3.31 2.37-2.37.996.608 2.296.07 2.572-1.065z"></path><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"></path></svg>
                        General
                    </button>
                    
                    <button @click="activeTab = 'map'"
                            :class="activeTab === 'map' ? 'bg-primary/20 text-primary border-primary/50' : 'text-text/80 border-transparent hover:bg-white/5 hover:text-white'"
                            class="w-full flex items-center gap-3 px-4 py-3 rounded-xl border text-left font-semibold transition-all duration-300">
                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 20l-5.447-2.724A1 1 0 013 16.382V5.618a1 1 0 011.447-.894L9 7m0 13l6-3m-6 3V7m6 10l4.553 2.276A1 1 0 0021 18.382V7.618a1 1 0 00-.553-.894L15 4m0 13V4m0 0L9 7"></path></svg>
                        Map Preferences
                    </button>

                    <button @click="activeTab = 'notifications'"
                            :class="activeTab === 'notifications' ? 'bg-primary/20 text-primary border-primary/50' : 'text-text/80 border-transparent hover:bg-white/5 hover:text-white'"
                            class="w-full flex items-center gap-3 px-4 py-3 rounded-xl border text-left font-semibold transition-all duration-300">
                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 17h5l-1.405-1.405A2.032 2.032 0 0118 14.158V11a6.002 6.002 0 00-4-5.659V5a2 2 0 10-4 0v.341C7.67 6.165 6 8.388 6 11v3.159c0 .538-.214 1.055-.595 1.436L4 17h5m6 0v1a3 3 0 11-6 0v-1m6 0H9"></path></svg>
                        Notifications
                    </button>
                </nav>
            </div>
        </div>

        <!-- Content Area -->
        <div class="flex-grow">
            <form action="{{ route('settings.update') }}" method="POST" class="bg-card-dark border border-secondary/30 rounded-2xl shadow-[0_0_20px_rgba(0,0,0,0.5)] overflow-hidden">
                @csrf
                
                <!-- General Settings -->
                <div x-show="activeTab === 'general'" x-transition:enter="transition ease-out duration-300" x-transition:enter-start="opacity-0 transform translate-y-4" x-transition:enter-end="opacity-100 transform translate-y-0" class="p-6 sm:p-8">
                    <h3 class="text-2xl font-bold text-white mb-2">General Settings</h3>
                    <p class="text-secondary mb-8">Update core application settings and contact information.</p>
                    
                    <div class="space-y-6 max-w-2xl">
                        <div>
                            <label class="block text-sm font-semibold text-text mb-2">Application Name</label>
                            <input type="text" name="app_name" value="{{ $settings['app_name'] }}" class="w-full bg-background border border-secondary/40 rounded-xl px-4 py-3 text-white focus:border-primary focus:ring-1 focus:ring-primary outline-none transition-all">
                        </div>
                        
                        <div>
                            <label class="block text-sm font-semibold text-text mb-2">Admin Contact Email</label>
                            <input type="email" name="contact_email" value="{{ $settings['contact_email'] }}" class="w-full bg-background border border-secondary/40 rounded-xl px-4 py-3 text-white focus:border-primary focus:ring-1 focus:ring-primary outline-none transition-all">
                        </div>
                    </div>
                </div>

                <!-- Map Preferences -->
                <div x-show="activeTab === 'map'" x-transition:enter="transition ease-out duration-300" x-transition:enter-start="opacity-0 transform translate-y-4" x-transition:enter-end="opacity-100 transform translate-y-0" class="p-6 sm:p-8" style="display: none;">
                    <h3 class="text-2xl font-bold text-white mb-2">Map Preferences</h3>
                    <p class="text-secondary mb-8">Configure how the live field map behaves across the application.</p>
                    
                    <div class="space-y-6 max-w-2xl">
                        <div>
                            <label class="block text-sm font-semibold text-text mb-2">Default Map View</label>
                            <div class="relative">
                                <select name="map_default_view" class="w-full bg-background border border-secondary/40 rounded-xl px-4 py-3 text-white focus:border-primary focus:ring-1 focus:ring-primary outline-none transition-all appearance-none">
                                    <option value="street" {{ $settings['map_default_view'] === 'street' ? 'selected' : '' }}>Street View</option>
                                    <option value="satellite" {{ $settings['map_default_view'] === 'satellite' ? 'selected' : '' }}>Satellite</option>
                                    <option value="hybrid" {{ $settings['map_default_view'] === 'hybrid' ? 'selected' : '' }}>Hybrid Dark (Default)</option>
                                </select>
                                <div class="pointer-events-none absolute inset-y-0 right-0 flex items-center px-4 text-secondary">
                                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"></path></svg>
                                </div>
                            </div>
                        </div>

                        <div>
                            <label class="block text-sm font-semibold text-text mb-2">Auto-refresh Interval (Seconds)</label>
                            <input type="number" name="map_auto_refresh" value="{{ $settings['map_auto_refresh'] }}" min="10" max="300" class="w-full bg-background border border-secondary/40 rounded-xl px-4 py-3 text-white focus:border-primary focus:ring-1 focus:ring-primary outline-none transition-all">
                            <p class="text-xs text-secondary mt-2">How often the live dashboard map pulls new audit points.</p>
                        </div>
                    </div>
                </div>

                <!-- Notifications -->
                <div x-show="activeTab === 'notifications'" x-transition:enter="transition ease-out duration-300" x-transition:enter-start="opacity-0 transform translate-y-4" x-transition:enter-end="opacity-100 transform translate-y-0" class="p-6 sm:p-8" style="display: none;">
                    <h3 class="text-2xl font-bold text-white mb-2">Notifications</h3>
                    <p class="text-secondary mb-8">Manage system alerts and automated email reports.</p>
                    
                    <div class="space-y-6 max-w-2xl">
                        <!-- Toggle 1 -->
                        <div class="flex items-center justify-between p-4 bg-background/50 border border-secondary/20 rounded-xl hover:border-primary/30 transition-colors">
                            <div>
                                <h4 class="font-bold text-white">System Alerts</h4>
                                <p class="text-sm text-secondary">Receive real-time alerts in the dashboard when a new task is created.</p>
                            </div>
                            <label class="relative inline-flex items-center cursor-pointer">
                                <input type="checkbox" name="notify_system_alerts" value="1" {{ $settings['notify_system_alerts'] ? 'checked' : '' }} class="sr-only peer">
                                <div class="w-11 h-6 bg-secondary/30 peer-focus:outline-none rounded-full peer peer-checked:after:translate-x-full peer-checked:after:border-white after:content-[''] after:absolute after:top-[2px] after:left-[2px] after:bg-white after:border-gray-300 after:border after:rounded-full after:h-5 after:w-5 after:transition-all peer-checked:bg-primary"></div>
                            </label>
                        </div>

                        <!-- Toggle 2 -->
                        <div class="flex items-center justify-between p-4 bg-background/50 border border-secondary/20 rounded-xl hover:border-primary/30 transition-colors">
                            <div>
                                <h4 class="font-bold text-white">Weekly Email Summaries</h4>
                                <p class="text-sm text-secondary">Send a weekly summary of completed vs pending tasks to the admin email.</p>
                            </div>
                            <label class="relative inline-flex items-center cursor-pointer">
                                <input type="checkbox" name="notify_email_summaries" value="1" {{ $settings['notify_email_summaries'] ? 'checked' : '' }} class="sr-only peer">
                                <div class="w-11 h-6 bg-secondary/30 peer-focus:outline-none rounded-full peer peer-checked:after:translate-x-full peer-checked:after:border-white after:content-[''] after:absolute after:top-[2px] after:left-[2px] after:bg-white after:border-gray-300 after:border after:rounded-full after:h-5 after:w-5 after:transition-all peer-checked:bg-primary"></div>
                            </label>
                        </div>
                    </div>
                </div>

                <!-- Footer Actions -->
                <div class="bg-background/80 border-t border-secondary/30 p-6 flex justify-end gap-3">
                    <button type="button" @click="window.location.reload()" class="px-6 py-2.5 rounded-xl font-semibold text-text hover:bg-white/10 transition-colors">
                        Cancel
                    </button>
                    <button type="submit" class="px-6 py-2.5 rounded-xl font-bold bg-primary text-white hover:bg-primary/80 hover:shadow-[0_0_15px_rgba(27,95,197,0.4)] transition-all transform hover:-translate-y-0.5">
                        Save Changes
                    </button>
                </div>
            </form>
        </div>
    </div>
</x-app-layout>
