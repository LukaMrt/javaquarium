<?php

declare(strict_types=1);

namespace App\Application\DTO;

use App\Domain\Fish\Entity\Fish;
use Symfony\Component\ObjectMapper\Attribute\Map;

#[Map(source: Fish::class)]
final readonly class FishDTO
{
    public function __construct(
        #[Map(source: 'id.value')]
        public string $id,
        #[Map(source: 'name.value')]
        public string $name,
        #[Map(source: 'species.value')]
        public string $species,
        #[Map(source: 'sex.value')]
        public string $sex,
        #[Map(source: 'age.value')]
        public int $age,
        #[Map(source: 'healthPoints.value')]
        public int $healthPoints,
    ) {
    }
}