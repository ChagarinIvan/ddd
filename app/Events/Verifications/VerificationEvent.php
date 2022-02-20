<?php

namespace App\Events\Verifications;

use App\Verifications\Subject;
use Illuminate\Queue\SerializesModels;
use JetBrains\PhpStorm\ArrayShape;

class VerificationEvent
{
    use SerializesModels;

    public function __construct(
        public string $id,
        public int $code,
        public Subject $subject,
        public string $occurredOn,
    ) {}

    #[ArrayShape(['id' => "string", 'identity' => "string", 'type' => "\App\Models\SubjectType", 'occurred_on' => "string"])]
    public function toArray(): array
    {
        return [
            'id' => $this->id,
            'identity' => $this->subject->identity,
            'type' => $this->subject->type,
            'occurred_on' => $this->occurredOn,
        ];
    }
}
