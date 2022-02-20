<?php

namespace App\Verifications;

enum SubjectType: string
{
    case emailConfirmation = 'email_confirmation';
    case mobileConfirmation  = 'mobile_confirmation';
}
