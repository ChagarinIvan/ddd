<?php

namespace App\Providers;

use App\Events\Notifications\NotificationCreatedEvent;
use App\Events\Notifications\NotificationDispatchedEvent;
use App\Events\Verifications\VerificationConfirmationFailedEvent;
use App\Events\Verifications\VerificationConfirmedEvent;
use App\Events\Verifications\VerificationCreatedEvent;
use App\Listeners\Notifications\NotificationCreatedEventListeners;
use App\Listeners\Notifications\NotificationDispatchedEventListeners;
use App\Listeners\Verifications\VerificationConfirmationFailedEventListener;
use App\Listeners\Verifications\VerificationConfirmedEventListener;
use App\Listeners\Verifications\VerificationCreatedEventListener;
use Laravel\Lumen\Providers\EventServiceProvider as ServiceProvider;

class EventServiceProvider extends ServiceProvider
{
    /**
     * The event listener mappings for the application.
     *
     * @var array
     */
    protected $listen = [
        VerificationCreatedEvent::class => [
            VerificationCreatedEventListener::class,
        ],
        VerificationConfirmedEvent::class => [
            VerificationConfirmedEventListener::class,
        ],
        VerificationConfirmationFailedEvent::class => [
            VerificationConfirmationFailedEventListener::class,
        ],
        NotificationCreatedEvent::class => [
            NotificationCreatedEventListeners::class,
        ],
        NotificationDispatchedEvent::class => [
            NotificationDispatchedEventListeners::class,
        ],
    ];

    /**
     * Determine if events and listeners should be automatically discovered.
     *
     * @return bool
     */
    public function shouldDiscoverEvents()
    {
        return false;
    }
}
