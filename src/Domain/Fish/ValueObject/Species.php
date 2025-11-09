<?php

declare(strict_types=1);

namespace App\Domain\Fish\ValueObject;

enum Species: string
{
    case GROUPER = 'GROUPER';
    case TUNA = 'TUNA';
    case CLOWNFISH = 'CLOWNFISH';
    case SOLE = 'SOLE';
    case BASS = 'BASS';
    case CARP = 'CARP';

    public function getDisplayName(): string
    {
        return match ($this) {
            self::GROUPER => 'Mérou',
            self::TUNA => 'Thon',
            self::CLOWNFISH => 'Poisson-clown',
            self::SOLE => 'Sole',
            self::BASS => 'Bar',
            self::CARP => 'Carpe',
        };
    }
}
