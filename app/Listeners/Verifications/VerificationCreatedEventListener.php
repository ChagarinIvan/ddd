<?php

namespace App\Listeners\Verifications;

use App\Events\Verifications\VerificationCreatedEvent;
use App\Notifications\NotificationService;
use Illuminate\Contracts\Queue\ShouldQueue;

class VerificationCreatedEventListener implements ShouldQueue
{
    public function __construct(private readonly NotificationService $service)
    {}

    public function handle(VerificationCreatedEvent $event): void
    {
        $this->service->notificate($event->subject, $event->code);
    }
}
