<?php

namespace App\Notifications;

enum ChannelType: string
{
    case email = 'email';
    case mobile  = 'mobile';
}
