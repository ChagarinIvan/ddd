<?php

namespace App\Notifications;

use App\Events\Notifications\NotificationCreatedEvent;
use App\Events\Notifications\NotificationDispatchedEvent;
use App\Models\Notification;
use App\Templates\TemplateType;
use App\Verifications\Subject;
use App\Verifications\SubjectType;
use Illuminate\Contracts\Events\Dispatcher;
use Illuminate\Http\Client\Factory;
use Symfony\Component\HttpFoundation\Response;

class NotificationService
{
    public function __construct(
        private readonly Dispatcher $eventDispatcher,
        private readonly Factory $httpFactory
    ) {}

    public function notificate(Subject $subject, int $code): void
    {
        $notification = new Notification();
        $notification->recipient = $subject->identity;
        $notification->dispatched = false;
        $notification->body = $this->renderTemplate($this->templateTypeFromSubjectType($subject->type), $code);
        $notification->channel = $this->channelTypeFromSubjectType($subject->type);
        $notification->save();

        $this->eventDispatcher->dispatch(new NotificationCreatedEvent($notification));

        $sender = NotificationSenderFactoryType::getSender($notification->channel);
        $dispatched = $sender->send($notification->recipient, $notification->body);

        if ($dispatched) {
            $notification->dispatched = true;
            $notification->save();

            $this->eventDispatcher->dispatch(new NotificationDispatchedEvent($notification));
        }
    }

    private function renderTemplate(TemplateType $templateType, int $code): string
    {
        $templateBody = $this->httpFactory->contentType("application/json")
            ->bodyFormat('json')
            ->withHeaders([
                'Authorization' => 'Bearer '.md5(env('APP_KEY'))
            ])
            ->post('nginx:80/templates/render', [
                'slug' => $templateType->value,
                'variables' => [
                    'code' => $code,
                ],
            ]);

        if ($templateBody->status() === Response::HTTP_OK) {
            return $templateBody->body();
        }

        throw new \RuntimeException("Template render error. {$templateBody->status()}");
    }

    private function channelTypeFromSubjectType(SubjectType $type): ChannelType
    {
        return match ($type) {
            SubjectType::emailConfirmation => ChannelType::email,
            SubjectType::mobileConfirmation => ChannelType::mobile,
        };
    }

    private function templateTypeFromSubjectType(SubjectType $type): TemplateType
    {
        return match ($type) {
            SubjectType::emailConfirmation => TemplateType::emailVerification,
            SubjectType::mobileConfirmation => TemplateType::mobileVerification,
        };
    }
}
