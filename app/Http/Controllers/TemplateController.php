<?php

namespace App\Http\Controllers;

use App\Templates\TemplateFactory;
use App\Templates\TemplateService;
use App\Templates\TemplateType;
use Illuminate\Validation\Factory as ValidatorFactory;
use Illuminate\Validation\ValidationException;
use Laravel\Lumen\Http\Request;
use Laravel\Lumen\Routing\Controller;
use Symfony\Component\HttpFoundation\Response;

class TemplateController extends Controller
{
    public function __construct(
        private readonly TemplateService $service,
        private readonly ValidatorFactory $validator,
    ) {}

    public function render(Request $request): Response
    {
        $json = $request->json();
        if ($json === null || ($templateData = $json->all()) === []) {
            return response('', Response::HTTP_BAD_REQUEST);
        }
        try {
            $templateData = $this->validator->make($templateData, [
                'slug' => 'required',
                'variables' => 'required|array',
            ])->validate();
        } catch (ValidationException) {
            response('', Response::HTTP_UNPROCESSABLE_ENTITY);
        }

        try {
            $templateType = TemplateType::from($templateData['slug']);
        } catch (\ValueError) {
            return response('', Response::HTTP_NOT_FOUND);
        }

        $templateRenderer = TemplateFactory::makeTemplateRenderer($templateType);
        $template = $this->service->render($templateType, $templateRenderer, $templateData['variables']);

        return response(
            $template->content,
            Response::HTTP_OK,
            ['Content-Type' => $templateRenderer->getContentType()]
        );
    }
}
