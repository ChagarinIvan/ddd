<?php

namespace App\Listeners\Notifications;

use App\Events\Notifications\NotificationCreatedEvent;
use Illuminate\Contracts\Queue\ShouldQueue;
use Psr\Log\LoggerInterface;

class NotificationCreatedEventListeners implements ShouldQueue
{
    public function __construct(private readonly LoggerInterface $logger)
    {}

    public function handle(NotificationCreatedEvent $event): void
    {
        $this->logger->info("Notification created:", $event->toArray());
    }
}
