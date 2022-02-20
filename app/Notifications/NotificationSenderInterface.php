<?php

namespace App\Notifications;

interface NotificationSenderInterface
{
    public function send(string $recipient, string $body): void;
}
