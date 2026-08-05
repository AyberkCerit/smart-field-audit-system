<?php

namespace App\Notifications;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;
use App\Models\Task;

class NewTaskNotification extends Notification implements ShouldQueue
{
    use Queueable;

    public $task;
    public $message;

    /**
     * Create a new notification instance.
     */
    public function __construct(Task $task, $message)
    {
        $this->task = $task;
        $this->message = $message;
    }

    /**
     * Get the notification's delivery channels.
     *
     * @return array<int, string>
     */
    public function via(object $notifiable): array
    {
        return ['database'];
    }

    /**
     * Get the array representation of the notification.
     *
     * @return array<string, mixed>
     */
    public function toArray(object $notifiable): array
    {
        return [
            'message' => $this->message,
            'task_id' => $this->task->id,
            'url' => route('tasks.show', $this->task->id),
        ];
    }
}
