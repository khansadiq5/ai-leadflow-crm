<?php

namespace App\Jobs;

use App\Models\Task;
use App\Services\NotificationService;
use App\Services\ActivityService;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Queue\Queueable;

class SendTaskReminderJob implements ShouldQueue
{
    use Queueable;

    public function __construct(public int $taskId)
    {
        //
    }

    public function handle(): void
    {
        $task = Task::with(['assignedUser'])->find($this->taskId);

        if (!$task) {
            return;
        }

        if (in_array($task->status, ['completed', 'cancelled'])) {
            return;
        }

        if ($task->reminder_sent_at !== null) {
            return;
        }

        NotificationService::send([
            'user_id' => $task->assigned_to,
            'title' => 'Task Reminder',
            'message' => 'Reminder: "' . $task->title . '" is due at ' . $task->due_date->timezone('Asia/Kolkata')->format('d M Y, h:i A') . '.',
            'type' => 'task_reminder',
            'url' => route('tasks.show', $task),
        ]);

        ActivityService::log([
            'lead_id' => $task->lead_id,
            'customer_id' => $task->customer_id,
            'deal_id' => $task->deal_id,
            'task_id' => $task->id,
            'type' => 'task_reminder_sent',
            'description' => 'Reminder notification sent for this task.',
        ]);

        $task->update([
            'reminder_sent_at' => now(),
        ]);
    }
}