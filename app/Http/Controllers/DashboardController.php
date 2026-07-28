<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Task;

class DashboardController extends Controller
{
    /**
     * Display the dashboard view with relevant statistics.
     */
    public function index()
    {
        $__start = microtime(true);
        \Illuminate\Support\Facades\Log::info('Dashboard start');
        $user = auth()->user();
        
        $recentTasks = collect();
        $totalSolved = 0;
        $avgResolutionTime = 0;

        // Yöneticiler (ve admin) için istatistikleri hesapla
        if ($user->hasAnyRole(['admin', 'manager'])) {
            $cacheKey = 'dashboard_stats_user_' . $user->id;
            
            // Veritabanını yormamak için istatistikleri 5 dakika (300 saniye) boyunca Redis'te önbellekliyoruz
            $stats = \Illuminate\Support\Facades\Cache::remember($cacheKey, 300, function () use ($user, $__start) {
                $recentTasks = Task::where('assigned_manager', $user->id)
                    ->orderBy('created_at', 'desc')
                    ->take(3)
                    ->get();
                    
                $totalSolved = Task::where('assigned_manager', $user->id)
                    ->where('status', 'completed')
                    ->count();
                
                $avgResolutionTimeRaw = Task::where('assigned_manager', $user->id)
                    ->where('status', 'completed')
                    ->selectRaw('AVG(TIMESTAMPDIFF(HOUR, created_at, updated_at)) as avg_time')
                    ->value('avg_time');
                    
                $avgResolutionTime = $avgResolutionTimeRaw ? round($avgResolutionTimeRaw, 1) : 0;
                \Illuminate\Support\Facades\Log::info('Dashboard end', ['duration' => microtime(true) - $__start]);
                return compact('recentTasks', 'totalSolved', 'avgResolutionTime');
            });
            
            $recentTasks = $stats['recentTasks'];
            $totalSolved = $stats['totalSolved'];
            $avgResolutionTime = $stats['avgResolutionTime'];
        }

        // Tüm denetim noktalarını (harita pinleri için) getir
        // Harita verileri anlık değişmediği için 60 dakika (3600 saniye) boyunca Redis'te önbellekliyoruz
        $auditPoints = \Illuminate\Support\Facades\Cache::remember('active_audit_points', 3600, function () {
            return \App\Models\AuditPoint::where('is_active', true)->get();
        });

        return view('dashboard', compact('recentTasks', 'totalSolved', 'avgResolutionTime', 'auditPoints'));
    }
}
