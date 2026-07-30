<x-app-layout>
    <div class="w-full mb-6">
        <!-- Header -->
        <div class="flex flex-col md:flex-row justify-between items-center mb-8 gap-4">
            <div>
                <h1 class="text-3xl font-display font-bold text-white flex items-center gap-3">
                    <svg class="w-8 h-8 text-accent" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"></path></svg>
                    System Logs
                </h1>
                <p class="text-secondary mt-1">
                    @hasanyrole('admin|manager')
                        Track the activities of all users in the system.
                    @else
                        You can track your recent activities here.
                    @endhasanyrole
                </p>
            </div>
        </div>

        <!-- Activity Log List -->
        <div class="bg-card-dark rounded-2xl border border-secondary/30 overflow-hidden shadow-lg">
            <div class="overflow-x-auto">
                <table class="w-full text-left text-sm text-secondary">
                    <thead class="text-xs uppercase bg-background/50 text-white/70 border-b border-secondary/30">
                        <tr>
                            <th scope="col" class="px-6 py-4">Date</th>
                            <th scope="col" class="px-6 py-4">User</th>
                            <th scope="col" class="px-6 py-4">Action</th>
                            <th scope="col" class="px-6 py-4">Description</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-secondary/10">
                        @forelse($logs as $log)
                            <tr class="hover:bg-white/5 transition-colors">
                                <td class="px-6 py-4 whitespace-nowrap">
                                    <div class="text-white">{{ $log->created_at->format('d.m.Y') }}</div>
                                    <div class="text-xs text-secondary/70">{{ $log->created_at->format('H:i') }}</div>
                                </td>
                                <td class="px-6 py-4">
                                    @if($log->causer)
                                        <div class="flex items-center gap-3">
                                            <div class="w-8 h-8 rounded-full bg-primary/20 flex items-center justify-center text-primary font-bold shadow-inner">
                                                {{ strtoupper(substr($log->causer->name, 0, 1)) }}
                                            </div>
                                            <span class="font-medium text-white">{{ $log->causer->name }}</span>
                                        </div>
                                    @else
                                        <span class="text-secondary/50 italic">System</span>
                                    @endif
                                </td>
                                <td class="px-6 py-4">
                                    @php
                                        $badgeClass = 'bg-secondary/20 text-secondary border-secondary/30';
                                        if (str_contains($log->description, 'created')) {
                                            $badgeClass = 'bg-green-500/20 text-green-400 border-green-500/20';
                                        } elseif (str_contains($log->description, 'updated')) {
                                            $badgeClass = 'bg-blue-500/20 text-blue-400 border-blue-500/20';
                                        } elseif (str_contains($log->description, 'deleted')) {
                                            $badgeClass = 'bg-red-500/20 text-red-400 border-red-500/20';
                                        }
                                    @endphp
                                    <span class="border rounded-full px-2.5 py-1 text-xs font-bold uppercase tracking-wider {{ $badgeClass }}">
                                        {{ $log->event ?? $log->description }}
                                    </span>
                                </td>
                                <td class="px-6 py-4 max-w-xs truncate" title="{{ $log->description }}">
                                    {{ $log->description }}
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="4" class="px-6 py-10 text-center">
                                    <div class="flex flex-col items-center justify-center text-secondary/50">
                                        <svg class="w-12 h-12 mb-3" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"></path></svg>
                                        <p>No log records found yet.</p>
                                    </div>
                                </td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
            
            @if($logs->hasPages())
                <div class="px-6 py-4 border-t border-secondary/20">
                    {{ $logs->links() }}
                </div>
            @endif
        </div>
    </div>
</x-app-layout>
