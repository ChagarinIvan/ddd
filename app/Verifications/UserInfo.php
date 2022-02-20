<?php

namespace App\Verifications;

class UserInfo
{
    public function __construct(
        public readonly ?string $ip,
        public readonly ?string $agent,
    ) {}

    public function equalTo(UserInfo $userInfo): bool
    {
        return $this->ip === $userInfo->ip && $this->agent === $userInfo->agent;
    }
}
