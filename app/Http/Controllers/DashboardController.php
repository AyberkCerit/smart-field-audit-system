<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Task;
use App\Models\User;
use Spatie\Activitylog\Models\Activity;

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

        // Calculate statistics for managers (and admin)
        if ($user->hasAnyRole(['admin', 'manager'])) {
            $cacheKey = 'dashboard_stats_user_' . $user->id;
            
            // Cache statistics in Redis for 5 minutes (300 seconds) to reduce database load
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

        // Get all audit points (for map pins)
        // Cache map data in Redis for 60 minutes (3600 seconds) since it doesn't change instantly
        $auditPoints = \Illuminate\Support\Facades\Cache::remember('active_audit_points', 3600, function () {
            return \App\Models\AuditPoint::where('is_active', true)->get();
        });

        $latestUsers = User::orderBy('created_at', 'desc')->take(5)->get();
        $recentActivities = Activity::with('causer')->orderBy('created_at', 'desc')->take(5)->get();

        return view('dashboard', compact('recentTasks', 'totalSolved', 'avgResolutionTime', 'auditPoints', 'latestUsers', 'recentActivities'));
    }
}
