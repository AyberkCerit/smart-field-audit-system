<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Notifications\DatabaseNotification;

class NotificationController extends Controller
{
    public function markAsRead(DatabaseNotification $notification)
    {
        $notification->markAsRead();
        
        $url = $notification->data['url'] ?? url()->previous();
        
        return redirect($url);
    }

    public function markAllAsRead()
    {
        auth()->user()->unreadNotifications->markAsRead();
        
        return back()->with('success', 'Tüm bildirimler okundu olarak işaretlendi.');
    }
}
