<?php

declare(strict_types=1);

namespace App\Application\UseCase\Aquarium\GetAquariumState;

use App\Domain\Aquarium\Entity\Aquarium;
use App\Application\Exception\AquariumNotFoundException;
use App\Domain\Aquarium\Repository\AquariumRepositoryInterface;
use App\Domain\Aquarium\ValueObject\AquariumId;
use Symfony\Component\ObjectMapper\ObjectMapperInterface;

final readonly class GetAquariumStateHandler
{
    public function __construct(
        private AquariumRepositoryInterface $aquariumRepository,
        private ObjectMapperInterface $objectMapper,
    ) {
    }

    public function handle(GetAquariumStateQuery $query): GetAquariumStateResponse
    {
        $aquariumId = AquariumId::fromString($query->aquariumId);
        $aquarium = $this->aquariumRepository->findById($aquariumId);

        if (!$aquarium instanceof Aquarium) {
            throw new AquariumNotFoundException('Aquarium not found with ID: ' . $query->aquariumId);
        }

        return $this->objectMapper->map($aquarium, GetAquariumStateResponse::class);
    }
}
