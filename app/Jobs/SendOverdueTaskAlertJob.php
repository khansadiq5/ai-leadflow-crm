<?php

namespace App\Jobs;

use App\Models\Task;
use App\Services\NotificationService;
use App\Services\ActivityService;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Queue\Queueable;

class SendOverdueTaskAlertJob implements ShouldQueue
{
    use Queueable;

    public function __construct(public int $taskId)
    {
        //
    }

    public function handle(): void
    {
        $task = Task::with('assignedUser')->find($this->taskId);

        if (!$task) {
            return;
        }

        // Completed/cancelled task ke liye overdue alert nahi bhejna
        if (in_array($task->status, ['completed', 'cancelled'])) {
            return;
        }

        // Agar task abhi overdue nahi hai to return
        if ($task->due_date >= now()) {
            return;
        }

        // Ek task ka overdue alert sirf ek baar bhejna
        if ($task->overdue_notified_at !== null) {
            return;
        }

        NotificationService::send([
            'user_id' => $task->assigned_to,
            'title' => 'Overdue Task Alert',
            'message' => 'Overdue: "' . $task->title . '" was due at ' . $task->due_date->timezone('Asia/Kolkata')->format('d M Y, h:i A') . '.',
            'type' => 'task_overdue',
            'url' => route('tasks.show', $task),
        ]);

        ActivityService::log([
            'lead_id' => $task->lead_id,
            'customer_id' => $task->customer_id,
            'deal_id' => $task->deal_id,
            'task_id' => $task->id,
            'type' => 'task_overdue_alert_sent',
            'description' => 'Overdue alert notification sent for this task.',
        ]);

        $task->update([
            'overdue_notified_at' => now(),
        ]);
    }
}