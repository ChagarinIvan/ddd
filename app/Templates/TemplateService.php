<?php

namespace App\Templates;

use App\Models\Template;

class TemplateService
{
    public function render(TemplateType $templateType, TemplateRendererInterface $renderer, array $variables): Template
    {
        $template = new Template();
        $template->slug = $templateType;
        $template->content = $renderer->renderContent($variables);
        $template->save();
        return $template;
    }
}
