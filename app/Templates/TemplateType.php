<?php

namespace App\Templates;

enum TemplateType: string
{
    case emailVerification = 'email-verification';
    case mobileVerification  = 'mobile-verification';
}
