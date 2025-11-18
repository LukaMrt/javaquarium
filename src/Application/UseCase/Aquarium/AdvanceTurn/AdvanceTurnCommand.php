<?php

declare(strict_types=1);

namespace App\Application\UseCase\Aquarium\AdvanceTurn;

final readonly class AdvanceTurnCommand
{
    public function __construct(
        public string $aquariumId
    ) {
    }
}
