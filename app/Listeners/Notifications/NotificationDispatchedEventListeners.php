<?php

namespace App\Listeners\Notifications;

use App\Events\Notifications\NotificationDispatchedEvent;
use Illuminate\Contracts\Queue\ShouldQueue;
use Psr\Log\LoggerInterface;

class NotificationDispatchedEventListeners implements ShouldQueue
{
    public function __construct(private readonly LoggerInterface $logger)
    {}

    public function handle(NotificationDispatchedEvent $event): void
    {
        $this->logger->info("Notification dispatched:", $event->toArray());
    }
}
