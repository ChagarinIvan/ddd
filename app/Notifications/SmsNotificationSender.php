<?php

namespace App\Notifications;

class SmsNotificationSender implements NotificationSenderInterface
{
    public function __construct(private readonly GotifyApiClient $apiClient)
    {}

    public function send(string $recipient, string $body): bool
    {
        return $this->apiClient->postMessage($recipient, $body);
    }
}
