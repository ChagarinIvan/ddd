<?php

namespace App\Notifications;

use JetBrains\PhpStorm\Pure;

class NotificationSenderFactoryType
{
    #[Pure]
    public static function getSender(ChannelType $type): NotificationSenderInterface
    {
        return match ($type) {
            ChannelType::email => new EmailNotificationSender(),
            ChannelType::mobile => new SmsNotificationSender(),
        };
    }
}
