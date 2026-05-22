<?php

namespace App\Services;

use App\Models\Notification;

class NotificationService
{
    public static function send(array $data): void
    {
        Notification::create([
            'user_id' => $data['user_id'],
            'title' => $data['title'],
            'message' => $data['message'],
            'type' => $data['type'] ?? 'general',
            'url' => $data['url'] ?? null,
            'is_read' => false,
        ]);
    }
}