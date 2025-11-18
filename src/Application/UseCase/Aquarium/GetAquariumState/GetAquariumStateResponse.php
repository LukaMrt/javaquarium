<?php

declare(strict_types=1);

namespace App\Application\UseCase\Aquarium\GetAquariumState;

use App\Application\DTO\AlgaeDTO;
use App\Application\DTO\FishDTO;
use App\Domain\Aquarium\Entity\Aquarium;
use App\Lib\Mapper\ToIntTransformer;
use App\Lib\Mapper\ToStringTransformer;
use Symfony\Component\ObjectMapper\Attribute\Map;

#[Map(source: Aquarium::class)]
final readonly class GetAquariumStateResponse
{
    /**
     * @param FishDTO[] $fishes
     * @param AlgaeDTO[] $algae
     */
    public function __construct(
        #[Map(source: 'id', transform: ToStringTransformer::class)]
        public string $aquariumId,
        #[Map(source: 'name', transform: ToStringTransformer::class)]
        public string $name,
        #[Map(source: 'turnNumber', transform: ToIntTransformer::class)]
        public int $turnNumber,
        #[Map(target: FishDTO::class . '[]', source: 'fishes')]
        public array $fishes,
        #[Map(target: AlgaeDTO::class . '[]', source: 'algae')]
        public array $algae,
    ) {
    }
}
