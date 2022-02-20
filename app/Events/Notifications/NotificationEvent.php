<?php

namespace App\Events\Notifications;

use App\Models\Notification;
use Illuminate\Queue\SerializesModels;

class NotificationEvent
{
    use SerializesModels;

    public function __construct(
        public Notification $notification,
    ) {}

    public function toArray(): array
    {
        return $this->notification->toArray();
    }
}
