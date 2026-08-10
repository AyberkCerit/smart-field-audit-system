<?php

namespace App\Listeners;

use App\Events\TaskCompleted;
use App\Models\User;
use App\Notifications\TaskCompletedNotification;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Support\Facades\Notification;

class SendTaskCompletedNotification implements ShouldQueue
{
    public function handle(TaskCompleted $event)
    {
        $task = $event->task;

        $adminsAndManagers = User::role(['admin', 'manager'])->get();
        Notification::send($adminsAndManagers, new TaskCompletedNotification($task));
    }
}
