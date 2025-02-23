<?php

declare(strict_types=1);

namespace App\Enums;

enum LeadSource: string
{
    case FACEBOOK = 'facebook';
    case GOOGLE = 'google';
    case LINKEDIN = 'linkedin';
    case MANUAL = 'manual';
}
