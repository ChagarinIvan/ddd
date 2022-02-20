<?php

namespace App\Templates;

class TemplateFactory
{
    public static function makeTemplateRenderer(TemplateType $templateType): TemplateRendererInterface
    {
        return match ($templateType) {
            TemplateType::mobileVerification => new SmsTemplateRenderer('Your verification code is {{ code }}.'),
            TemplateType::emailVerification => new EmailTemplateRenderer('templates.email.confirmation'),
        };
    }
}
