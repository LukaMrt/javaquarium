<?php

declare(strict_types=1);

namespace App\Domain\Fish\ValueObject;

enum SexualBehaviorType: string
{
    case MONOSEXUAL = 'MONOSEXUAL';
    case PROTANDROUS = 'PROTANDROUS';
    case OPPORTUNISTIC = 'OPPORTUNISTIC';
}
