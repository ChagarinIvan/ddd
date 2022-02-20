<?php

namespace App\Templates;

interface TemplateRendererInterface
{
    public function renderContent(array $variables): string;
    public function getContentType(): string;
}
