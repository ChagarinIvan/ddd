<?php

namespace App\Services;

use App\Events\Verifications\VerificationConfirmedEvent;
use App\Events\Verifications\VerificationCreatedEvent;
use App\Exceptions\DublicatedVerificationException;
use App\Models\Verification;
use App\Verifications\Subject;
use App\Verifications\UserInfo;
use Illuminate\Contracts\Events\Dispatcher;

class VerificationService
{
    public function __construct(private readonly Dispatcher $eventDispatcher)
    {}

    private function generateCode(): int
    {
        return random_int(10000000, 99999999);
    }

    /**
     * @throws DublicatedVerificationException
     */
    public function storeVerification(Subject $subject, UserInfo $userInfo): Verification
    {
        $existVerification = Verification::whereIdentity($subject->identity)
            ->whereType($subject->type)
            ->get()
            ->filter(fn(Verification $verification) => !$verification->isExpire);

        if ($existVerification->count() > 0) {
            throw new DublicatedVerificationException();
        }

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

    public function confirmVerification(Verification $verification, int $code): bool
    {
        $verification->attempt++;
        $verification->save();

        if ($verification->code !== $code) {
            return false;
        }

        $verification->confirmed = true;
        $verification->save();

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
