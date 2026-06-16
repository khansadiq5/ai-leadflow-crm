<?php

namespace App\Jobs;

use App\Models\Task;
use App\Services\NotificationService;
use App\Services\ActivityService;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Queue\Queueable;
use Illuminate\Support\Facades\Log;

class SendOverdueTaskAlertJob implements ShouldQueue
{
    use Queueable;

    public function __construct(public int $taskId)
    {
        //
    }

    public function handle(): void
    {
        Log::channel('single')->info('[SendOverdueTaskAlertJob] handle() entered.', [
            'task_id' => $this->taskId,
        ]);

        $task = Task::with('assignedUser')->find($this->taskId);

        if (!$task) {
            Log::channel('single')->warning('[SendOverdueTaskAlertJob] Task not found.', ['task_id' => $this->taskId]);
            return;
        }

        // Completed/cancelled task ke liye overdue alert nahi bhejna
        if (in_array($task->status, ['completed', 'cancelled'])) {
            Log::channel('single')->info('[SendOverdueTaskAlertJob] Skipped: task already completed/cancelled.', [
                'task_id' => $task->id,
                'status'  => $task->status,
            ]);
            return;
        }

        // Agar task abhi overdue nahi hai to return
        if ($task->due_date >= now()) {
            Log::channel('single')->info('[SendOverdueTaskAlertJob] Skipped: task is not yet overdue.', [
                'task_id'  => $task->id,
                'due_date' => $task->due_date->toDateTimeString(),
                'now'      => now()->toDateTimeString(),
            ]);
            return;
        }

        // Ek task ka overdue alert sirf ek baar bhejna
        if ($task->overdue_notified_at !== null) {
            Log::channel('single')->info('[SendOverdueTaskAlertJob] Skipped: already notified.', [
                'task_id'              => $task->id,
                'overdue_notified_at'  => $task->overdue_notified_at->toDateTimeString(),
            ]);
            return;
        }

        // ── DEBUG LOG [CHECK 9]: About to call NotificationService::send() ──────────
        Log::channel('single')->info('[SendOverdueTaskAlertJob] Calling NotificationService::send().', [
            'task_id'     => $task->id,
            'assigned_to' => $task->assigned_to,
        ]);

        NotificationService::send([
            'user_id' => $task->assigned_to,
            'title'   => 'Overdue Task Alert',
            'message' => 'Overdue: "' . $task->title . '" was due at ' . $task->due_date->timezone('Asia/Kolkata')->format('d M Y, h:i A') . '.',
            'type'    => 'task_overdue',
            'url'     => route('tasks.show', $task),
        ]);

        Log::channel('single')->info('[SendOverdueTaskAlertJob] NotificationService::send() completed successfully.', [
            'task_id'     => $task->id,
            'assigned_to' => $task->assigned_to,
        ]);

        ActivityService::log([
            'lead_id'     => $task->lead_id,
            'customer_id' => $task->customer_id,
            'deal_id'     => $task->deal_id,
            'task_id'     => $task->id,
            'type'        => 'task_overdue_alert_sent',
            'description' => 'Overdue alert notification sent for this task.',
        ]);

        $task->update([
            'overdue_notified_at' => now(),
        ]);

        // ── DEBUG LOG [CHECK 10]: overdue_notified_at was stamped ───────────────
        Log::channel('single')->info('[SendOverdueTaskAlertJob] overdue_notified_at stamped. Alert flow complete.', [
            'task_id'              => $task->id,
            'overdue_notified_at'  => $task->fresh()->overdue_notified_at,
        ]);
    }
}