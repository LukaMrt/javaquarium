<?php

declare(strict_types=1);

namespace App\Domain\Fish\ValueObject;

enum Diet: string
{
    case CARNIVOROUS = 'carnivorous';
    case HERBIVOROUS = 'herbivorous';

    public function isCarnivorous(): bool
    {
        return $this === self::CARNIVOROUS;
    }

    public function isHerbivorous(): bool
    {
        return $this === self::HERBIVOROUS;
    }
}