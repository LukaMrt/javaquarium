<?php

declare(strict_types=1);

namespace App\Application\DTO;

use App\Domain\Algae\Entity\Algae;
use Symfony\Component\ObjectMapper\Attribute\Map;

#[Map(source: Algae::class)]
final readonly class AlgaeDTO
{
    public function __construct(
        #[Map(source: 'id.value')]
        public string $id,
        #[Map(source: 'name.value')]
        public string $name,
        #[Map(source: 'age.value')]
        public int $age,
        #[Map(source: 'healthPoints.value')]
        public int $healthPoints,
    ) {
    }
}