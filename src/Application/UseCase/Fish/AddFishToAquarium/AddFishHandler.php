<?php

declare(strict_types=1);

namespace App\Application\UseCase\Fish\AddFishToAquarium;

use App\Domain\Aquarium\Entity\Aquarium;
use App\Application\Exception\AquariumNotFoundException;
use App\Domain\Aquarium\Repository\AquariumRepositoryInterface;
use App\Domain\Aquarium\ValueObject\AquariumId;
use App\Domain\Fish\Entity\Fish;
use App\Domain\Fish\ValueObject\FishId;
use App\Domain\Shared\ValueObject\Age;
use App\Domain\Shared\ValueObject\EntityName;
use App\Domain\Shared\ValueObject\HealthPoints;
use Symfony\Component\ObjectMapper\ObjectMapperInterface;

final readonly class AddFishHandler
{
    public function __construct(
        private AquariumRepositoryInterface $aquariumRepository,
        private ObjectMapperInterface $objectMapper,
    ) {
    }

    public function handle(AddFishCommand $command): AddFishResponse
    {
        $aquarium = $this->aquariumRepository->findById(AquariumId::fromString($command->aquariumId));

        if (!$aquarium instanceof Aquarium) {
            throw new AquariumNotFoundException('Aquarium not found with ID: ' . $command->aquariumId);
        }

        $fish = new Fish(
            FishId::fromString($command->fishId),
            new EntityName($command->name),
            $command->species,
            $command->sex,
            Age::initial(),
            HealthPoints::initial(),
        );

        $aquarium->addFish($fish);
        $this->aquariumRepository->save($aquarium);

        return $this->objectMapper->map($fish, AddFishResponse::class);
    }
}
