<?php

namespace App\Console\Commands;

use App\Jobs\SendOverdueTaskAlertJob;
use App\Models\Task;
use Illuminate\Console\Command;

class SendOverdueTaskAlerts extends Command
{
    protected $signature = 'tasks:send-overdue-alerts';

    protected $description = 'Send overdue alerts for tasks whose due date has passed';

    public function handle(): int
    {
        if (setting('overdue_alert_enabled', '1') !== '1') {
            $this->info('Overdue alerts are disabled from settings.');
            return Command::SUCCESS;
        }

        $tasks = Task::whereIn('status', ['pending', 'in_progress'])
            ->whereNull('overdue_notified_at')
            ->where('due_date', '<', now())
            ->get();

        foreach ($tasks as $task) {
            (new \App\Jobs\SendOverdueTaskAlertJob($task->id))->handle();
        }

        $this->info($tasks->count() . ' overdue task alert jobs dispatched.');

        return Command::SUCCESS;
    }
}