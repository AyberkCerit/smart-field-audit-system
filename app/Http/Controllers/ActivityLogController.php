<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Spatie\Activitylog\Models\Activity;
use Illuminate\Support\Facades\Auth;

class ActivityLogController extends Controller
{
    /**
     * Display a listing of the activity logs.
     */
    public function index()
    {
        $user = Auth::user();
        
        $query = Activity::with(['causer', 'subject'])->latest();

        // Admin and Manager can see all logs
        if (!$user->hasAnyRole(['admin', 'manager'])) {
            // Other users (like field personnel) can only see their own logs
            $query->where('causer_id', $user->id);
        }

        $logs = $query->paginate(20);

        return view('activity-logs.index', compact('logs'));
    }
}
