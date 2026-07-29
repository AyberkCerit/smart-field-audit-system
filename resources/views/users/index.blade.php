<x-app-layout>
    <div class="py-8">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            
            <!-- Başlık ve Aksiyon Alanı -->
            <div class="flex justify-between items-center mb-6">
                <h2 class="text-2xl font-bold text-white flex items-center gap-2">
                    <svg class="w-8 h-8 text-primary" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0zm6 3a2 2 0 11-4 0 2 2 0 014 0zM7 10a2 2 0 11-4 0 2 2 0 014 0z"></path></svg>
                    Kullanıcı Yönetimi
                </h2>
                <a href="{{ route('users.create') }}" class="flex items-center gap-2 px-5 py-2.5 rounded-xl text-sm font-bold bg-[#a4d756] hover:bg-[#91c342] text-gray-900 shadow-[0_0_15px_rgba(164,215,86,0.4)] hover:shadow-[0_0_20px_rgba(164,215,86,0.6)] hover:-translate-y-1 transition-all duration-300">
                    <svg class="w-5 h-5 text-gray-900" fill="none" stroke="currentColor" stroke-width="2.5" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M12 4v16m8-8H4"></path></svg>
                    Yeni Kullanıcı Ekle
                </a>
            </div>

            <!-- Kullanıcılar Tablosu -->
            <div class="bg-card-dark rounded-2xl border border-secondary/30 shadow-[0_0_20px_rgba(0,0,0,0.5)] overflow-hidden">
                <div class="overflow-x-auto">
                    <table class="w-full text-left border-collapse">
                        <thead>
                            <tr class="bg-background/80 border-b border-secondary/30 text-secondary text-sm uppercase tracking-wider">
                                <th class="px-6 py-4 font-semibold">İsim</th>
                                <th class="px-6 py-4 font-semibold">E-posta</th>
                                <th class="px-6 py-4 font-semibold">Roller</th>
                                <th class="px-6 py-4 font-semibold">Kayıt Tarihi</th>
                                <th class="px-6 py-4 font-semibold text-right">İşlemler</th>
                            </tr>
                        </thead>
                        <tbody class="divide-y divide-secondary/20">
                            @forelse($users as $user)
                                <tr class="hover:bg-background/40 transition-colors duration-200">
                                    <td class="px-6 py-4">
                                        <div class="flex items-center gap-3">
                                            <div class="w-10 h-10 rounded-full bg-primary/20 flex items-center justify-center text-primary font-bold">
                                                {{ strtoupper(substr($user->name, 0, 1)) }}
                                            </div>
                                            <div class="text-white font-medium">{{ $user->name }}</div>
                                        </div>
                                    </td>
                                    <td class="px-6 py-4 text-gray-300">{{ $user->email }}</td>
                                    <td class="px-6 py-4">
                                        <div class="flex gap-1 flex-wrap">
                                            @forelse($user->roles as $role)
                                                <span class="px-2 py-1 bg-accent/10 text-accent border border-accent/20 rounded-md text-xs font-semibold">
                                                    {{ ucfirst($role->name) }}
                                                </span>
                                            @empty
                                                <span class="text-secondary text-sm italic">Rol atanmamış</span>
                                            @endforelse
                                        </div>
                                    </td>
                                    <td class="px-6 py-4 text-gray-400 text-sm">
                                        {{ $user->created_at->format('d M Y') }}
                                    </td>
                                    <td class="px-6 py-4 text-right">
                                        <div class="flex justify-end gap-2">
                                            <a href="{{ route('users.edit', $user) }}" class="p-2 bg-yellow-500/10 text-yellow-500 hover:bg-yellow-500 hover:text-white rounded-lg transition-colors border border-yellow-500/20" title="Düzenle">
                                                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"></path></svg>
                                            </a>
                                            <form action="{{ route('users.destroy', $user) }}" method="POST" class="inline-block" onsubmit="return confirm('Bu kullanıcıyı silmek istediğinize emin misiniz?');">
                                                @csrf
                                                @method('DELETE')
                                                <button type="submit" class="p-2 bg-red-500/10 text-red-500 hover:bg-red-500 hover:text-white rounded-lg transition-colors border border-red-500/20" title="Sil">
                                                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"></path></svg>
                                                </button>
                                            </form>
                                        </div>
                                    </td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="5" class="px-6 py-8 text-center text-secondary">
                                        Kayıtlı kullanıcı bulunamadı.
                                    </td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
                
                @if($users->hasPages())
                    <div class="px-6 py-4 border-t border-secondary/30 bg-background/50">
                        {{ $users->links() }}
                    </div>
                @endif
            </div>

        </div>
    </div>
</x-app-layout>
