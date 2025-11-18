<?php

declare(strict_types=1);

namespace App\Application\UseCase\Algae\AddAlgaeToAquarium;

final readonly class AddAlgaeCommand
{
    public function __construct(
        public string $aquariumId,
        public string $algaeId,
        public string $name
    ) {
    }
}
