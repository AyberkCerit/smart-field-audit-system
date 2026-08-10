<?php

namespace App\Listeners;

use App\Events\TaskCreated;
use App\Models\User;
use App\Notifications\NewTaskNotification;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Support\Facades\Notification;

class SendTaskCreatedNotification implements ShouldQueue
{
    public function handle(TaskCreated $event)
    {
        $task = $event->task;

        $adminsAndManagers = User::role(['admin', 'manager'])->get();
        Notification::send($adminsAndManagers, new NewTaskNotification($task, 'Yeni görev oluşturuldu: ' . $task->title));

        if ($task->assigned_to) {
            $assignedUser = User::find($task->assigned_to);
            if ($assignedUser) {
                $assignedUser->notify(new NewTaskNotification($task, 'Size yeni bir görev atandı: ' . $task->title));
            }
        } else {
            $fieldPersonnel = User::role('field_personnel')->get();
            Notification::send($fieldPersonnel, new NewTaskNotification($task, 'Havuza yeni bir görev eklendi: ' . $task->title));
        }
    }
}
