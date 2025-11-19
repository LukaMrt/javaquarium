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

    public function getDiet(): Diet
    {
        return match ($this) {
            self::GROUPER, self::TUNA, self::CLOWNFISH => Diet::CARNIVOROUS,
            self::SOLE, self::BASS, self::CARP => Diet::HERBIVOROUS,
        };
    }

    public function getSexualBehavior(): SexualBehaviorType
    {
        return match ($this) {
            self::CARP, self::TUNA => SexualBehaviorType::MONOSEXUAL,
            self::BASS, self::GROUPER => SexualBehaviorType::PROTANDROUS,
            self::SOLE, self::CLOWNFISH => SexualBehaviorType::OPPORTUNISTIC,
        };
    }
}
