<?php

namespace App\Listeners\Verifications;

use App\Events\Verifications\VerificationConfirmedEvent;
use Illuminate\Contracts\Queue\ShouldQueue;
use Psr\Log\LoggerInterface;

class VerificationConfirmedEventListener implements ShouldQueue
{
    public function __construct(private readonly LoggerInterface $logger)
    {}

    public function handle(VerificationConfirmedEvent $event): void
    {
        $this->logger->info("Confirm verification:", $event->toArray());
    }
}
