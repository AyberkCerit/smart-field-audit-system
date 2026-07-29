<?php
$user = \App\Models\User::find(1);
$__start = microtime(true);
$cacheKey = 'dashboard_stats_user_' . $user->id;
// Clear first to ensure a clean state
\Illuminate\Support\Facades\Cache::forget($cacheKey);

// Put in cache (First time)
\Illuminate\Support\Facades\Cache::remember($cacheKey, 300, function () use ($user, $__start) {
    $recentTasks = \App\Models\Task::where('assigned_manager', $user->id)
        ->orderBy('created_at', 'desc')
        ->take(3)
        ->get();
    $totalSolved = 0;
    $avgResolutionTime = 0;
    return compact('recentTasks', 'totalSolved', 'avgResolutionTime');
});

// Clear in-memory array cache that Laravel's Repository might keep
// Actually, Cache::store('redis') creates a fresh instance.
$redisStore = \Illuminate\Support\Facades\Cache::store('redis');
$redisStore->getStore()->get($cacheKey);

// Fetch from cache (Second time - real redis fetch)
$stats = \Illuminate\Support\Facades\Cache::remember($cacheKey, 300, function() {
    die("Should not be called");
});

$rt = $stats['recentTasks'];
echo "Type of recentTasks: " . gettype($rt) . "\n";
if (is_object($rt)) {
    echo "Class: " . get_class($rt) . "\n";
    $first = $rt->first();
    if ($first) {
        echo "Type of first item: " . gettype($first) . "\n";
        if (is_object($first)) {
            echo "Class of first item: " . get_class($first) . "\n";
        } else {
            echo "Value of first item: " . print_r($first, true) . "\n";
        }
    }
} else {
    echo "Value of recentTasks: " . print_r($rt, true) . "\n";
}
