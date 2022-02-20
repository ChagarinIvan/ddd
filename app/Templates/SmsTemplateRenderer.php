<?php

namespace App\Templates;

class SmsTemplateRenderer implements TemplateRendererInterface
{
    public function __construct(
        private string $smsFormat,
    ) {}

    public function renderContent(array $variables): string
    {
        foreach ($variables as $key => $variable) {
            $this->smsFormat = str_replace("{{ $key }}", $variable, $this->smsFormat);
        }

        return $this->smsFormat;
    }

    public function getContentType(): string
    {
        return 'text/plain';
    }
}
