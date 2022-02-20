<?php

namespace App\Services;

use App\Events\Verifications\VerificationConfirmedEvent;
use App\Events\Verifications\VerificationCreatedEvent;
use App\Models\Verification;
use App\Verifications\Subject;
use App\Verifications\UserInfo;
use Illuminate\Contracts\Events\Dispatcher;
use Illuminate\Support\Facades\Event;

class VerificationService
{
    public function __construct(private readonly Dispatcher $eventDispatcher)
    {}

    private function generateCode(): int
    {
        return rand(10000000, 99999999);
    }

    public function storeVerification(Subject $subject, UserInfo $userInfo): Verification
    {
        $verification = new Verification();
        $verification->subject = $subject;
        $verification->userInfo = $userInfo;
        $verification->confirmed = false;
        $verification->code = $this->generateCode();
        $verification->save();

        $this->eventDispatcher->dispatch(
            new VerificationCreatedEvent(
                $verification->id,
                $verification->code,
                $subject,
                $verification->created_at,
            )
        );

        return $verification;
    }

    public function confirmVerification(Verification $verification, int $code, UserInfo $userInfo): bool
    {
        if (
            !$verification->userInfo->equalTo($userInfo)
            || $verification->code !== $code
        ) {
            return false;
        }

        $this->eventDispatcher->dispatch(
            new VerificationConfirmedEvent(
                $verification->id,
                $verification->code,
                $verification->subject,
                $verification->created_at,
            )
        );

        return true;
    }
}
