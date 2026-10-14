<?php

namespace App\Notifications;

use App\Models\Task;
use Illuminate\Bus\Queueable;
use Illuminate\Notifications\Notification;
use Illuminate\Notifications\Messages\MailMessage;

class TaskAssignedNotification extends Notification
{
    use Queueable;

    public $task;

    public function __construct(Task $task)
    {
        $this->task = $task;
    }

    // কোন channel এ পাঠাবে
    public function via(object $notifiable): array
    {
        return ['database'];
    }

    // Database এ যা save হবে
    public function toArray(object $notifiable): array
    {
        return [
            'task_id' => $this->task->id,
            'task_title' => $this->task->title,
            'project_name' => $this->task->project->name,
            'message' => 'You were assigned a new task: ' . $this->task->title,
            'url' => route('tasks.show', $this->task->id),
        ];
    }

    // Email এ যা যাবে (পরে use করবো)
    public function toMail(object $notifiable): MailMessage
    {
        return (new MailMessage)
            ->subject('New Task Assigned: ' . $this->task->title)
            ->line('You have been assigned a new task.')
            ->line('Task: ' . $this->task->title)
            ->line('Project: ' . $this->task->project->name)
            ->action('View Task', route('tasks.show', $this->task->id));
    }
}