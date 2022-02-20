<?php

namespace App\Listeners\Verifications;

use App\Events\Verifications\VerificationConfirmationFailedEvent;
use Illuminate\Contracts\Queue\ShouldQueue;
use Psr\Log\LoggerInterface;

class VerificationConfirmationFailedEventListener implements ShouldQueue
{
    public function __construct(private readonly LoggerInterface $logger)
    {}

    public function handle(VerificationConfirmationFailedEvent $event): void
    {
        $this->logger->info("Verification confirmation failed:", $event->toArray());
    }
}
