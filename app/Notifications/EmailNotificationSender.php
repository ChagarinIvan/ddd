<?php

namespace App\Notifications;

class EmailNotificationSender implements NotificationSenderInterface
{
    public function send(string $recipient, string $body): bool
    {
        return true;
    }
}
