<?php

declare(strict_types=1);

namespace App\Application\UseCase\Aquarium\AdvanceTurn;

use App\Domain\Aquarium\Entity\Aquarium;
use App\Lib\Mapper\ToIntTransformer;
use App\Lib\Mapper\ToStringTransformer;
use Symfony\Component\ObjectMapper\Attribute\Map;

#[Map(source: Aquarium::class)]
final readonly class AdvanceTurnResponse
{
    public function __construct(
        #[Map(source: 'id', transform: ToStringTransformer::class)]
        public string $aquariumId,
        #[Map(source: 'turnNumber', transform: ToIntTransformer::class)]
        public int $turnNumber,
    ) {
    }
}
