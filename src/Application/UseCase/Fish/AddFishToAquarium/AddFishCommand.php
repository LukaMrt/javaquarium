<?php

declare(strict_types=1);

namespace App\Application\UseCase\Fish\AddFishToAquarium;

use App\Domain\Fish\ValueObject\Sex;
use App\Domain\Fish\ValueObject\Species;

final readonly class AddFishCommand
{
    public function __construct(
        public string $aquariumId,
        public string $fishId,
        public string $name,
        public Species $species,
        public Sex $sex
    ) {
    }
}