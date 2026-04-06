<?php

declare(strict_types=1);

namespace Domain\MessengerClient;

enum MessengerProviderEnum: string
{
    case TELEGRAM = 'telegram';
    case WHATSAPP = 'whatsapp';
    case INSTAGRAM = 'instagram';
    case VIBER = 'viber';
    case FACEBOOK = 'facebook';
}
