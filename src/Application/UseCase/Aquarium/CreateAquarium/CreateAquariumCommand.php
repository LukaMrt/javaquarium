<?php

declare(strict_types=1);

namespace App\Application\UseCase\Aquarium\CreateAquarium;

final readonly class CreateAquariumCommand
{
    public function __construct(
        public string $aquariumId,
        public string $name
    ) {
    }
}