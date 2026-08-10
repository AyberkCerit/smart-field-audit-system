<?php

namespace App\Listeners;

use App\Events\UserCreated;
use App\Models\User;
use App\Notifications\NewUserNotification;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Support\Facades\Notification;

class SendUserCreatedNotification implements ShouldQueue
{
    public function handle(UserCreated $event)
    {
        $user = $event->user;

        $adminsAndManagers = User::role(['admin', 'manager'])->get();
        Notification::send($adminsAndManagers, new NewUserNotification($user));
    }
}
