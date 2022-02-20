<?php

namespace App\Templates;

class EmailTemplateRenderer implements TemplateRendererInterface
{
    public function __construct(
        private readonly string $viewSource,
    ) {}

    public function renderContent(array $variables): string
    {
        return view($this->viewSource, compact('variables'))->toHtml();
    }

    public function getContentType(): string
    {
        return 'text/html';
    }
}
