<?php

declare(strict_types=1);

namespace App\Application\UseCase\Aquarium\GetAquariumState;

final readonly class GetAquariumStateQuery
{
    public function __construct(
        public string $aquariumId
    ) {
    }
}
