<?php

namespace App\Console\Commands;

use App\Jobs\SendOverdueTaskAlertJob;
use App\Models\Task;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\Log;

class SendOverdueTaskAlerts extends Command
{
    protected $signature = 'tasks:send-overdue-alerts';

    protected $description = 'Send overdue alerts for tasks whose due date has passed';

    public function handle(): int
    {
        // ── DEBUG LOG [CHECK 1]: Command is executing ─────────────────────────────
        $now = now();
        Log::channel('single')->info('[SendOverdueTaskAlerts] Command started.', [
            'current_time' => $now->toDateTimeString(),       // [CHECK 2] Current time
            'timezone'     => $now->timezoneName,             // [CHECK 4] Timezone of now()
        ]);
        $this->info('[DEBUG] Command running at: ' . $now->toDateTimeString() . ' (' . $now->timezoneName . ')');

        Cache::forget('setting_overdue_alert_enabled');

        $settingValue = setting('overdue_alert_enabled', '1');
        Log::channel('single')->info('[SendOverdueTaskAlerts] Setting check.', [
            'overdue_alert_enabled' => $settingValue,
        ]);
        $this->info('[DEBUG] overdue_alert_enabled setting = ' . var_export($settingValue, true));

        if ($settingValue !== '1') {
            $this->info('Overdue alerts are disabled from settings.');
            Log::channel('single')->warning('[SendOverdueTaskAlerts] Aborted: overdue alerts disabled in settings.');
            return Command::SUCCESS;
        }

        // ── DEBUG LOG [CHECK 3]: Run the exact query and log results ──────────────
        $tasks = Task::whereIn('status', ['pending', 'in_progress'])
            ->whereNull('overdue_notified_at')
            ->where('due_date', '<', $now)
            ->get();

        // [CHECK 2] Total matching tasks count
        Log::channel('single')->info('[SendOverdueTaskAlerts] Query result.', [
            'matching_tasks_count' => $tasks->count(),
            'matching_task_ids'    => $tasks->pluck('id')->toArray(), // [CHECK 2] IDs of matching tasks
        ]);
        $this->info('[DEBUG] Matching overdue tasks: ' . $tasks->count() . ' | IDs: [' . $tasks->pluck('id')->implode(', ') . ']');

        foreach ($tasks as $task) {
            // ── DEBUG LOG [CHECK 6]: Log each task before dispatching ─────────────
            Log::channel('single')->info('[SendOverdueTaskAlerts] Dispatching job for task.', [
                'task_id'     => $task->id,
                'title'       => $task->title,
                'status'      => $task->status,
                'due_date'    => $task->due_date->toDateTimeString(),
                'due_date_tz' => $task->due_date->timezoneName,   // [CHECK 4] due_date TZ
                'assigned_to' => $task->assigned_to,
            ]);
            $this->info('[DEBUG] Dispatching job → Task ID=' . $task->id . ' "' . $task->title . '" due=' . $task->due_date);

            SendOverdueTaskAlertJob::dispatchSync($task->id);
        }

        $this->info($tasks->count() . ' overdue task alert jobs dispatched.');
        Log::channel('single')->info('[SendOverdueTaskAlerts] Command finished. Jobs dispatched: ' . $tasks->count());

        return Command::SUCCESS;
    }
}