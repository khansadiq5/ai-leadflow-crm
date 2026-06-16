<?php

namespace App\Console\Commands;

use App\Jobs\SendTaskReminderJob;
use App\Models\Task;
use Illuminate\Console\Command;

class SendTaskReminders extends Command
{
    protected $signature = 'tasks:send-reminders';

    protected $description = 'Send reminders for tasks that are due soon';

    public function handle(): int
    {
        if (setting('task_reminder_enabled', '1') !== '1') {
            $this->info('Task reminders are disabled from settings.');
            return Command::SUCCESS;
        }

        $minutes = (int) setting('task_reminder_minutes', 15);

        $now = now();
        $reminderWindow = now()->addMinutes($minutes);

        $tasks = Task::whereIn('status', ['pending', 'in_progress'])
            ->whereNull('reminder_sent_at')
            ->whereBetween('due_date', [$now, $reminderWindow])
            ->get();

        foreach ($tasks as $task) {
            // (new \App\Jobs\SendTaskReminderJob($task->id))->handle();
            SendTaskReminderJob::dispatchSync($task->id);
        }

        $this->info($tasks->count() . ' task reminder jobs dispatched.');

        return Command::SUCCESS;
    }
}