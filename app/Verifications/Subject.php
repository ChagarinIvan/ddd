<?php

namespace App\Verifications;

class Subject
{
    public function __construct(
        public readonly string $identity,
        public readonly SubjectType $type,
    ) {}
}
