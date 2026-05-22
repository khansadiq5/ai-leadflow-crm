<?php

namespace App\Services;

use App\Models\Activity;

class ActivityService
{
    public static function log(array $data): void
    {
        Activity::create([
            'user_id' => auth()->id(),
            'lead_id' => $data['lead_id'] ?? null,
            'customer_id' => $data['customer_id'] ?? null,
            'deal_id' => $data['deal_id'] ?? null,
            'task_id' => $data['task_id'] ?? null,
            'type' => $data['type'],
            'description' => $data['description'],
        ]);
    }
}