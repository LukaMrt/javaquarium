<?php

declare(strict_types=1);

namespace App\Domain\Fish\ValueObject;

enum Sex: string
{
    case MALE = 'MALE';
    case FEMALE = 'FEMALE';
}
