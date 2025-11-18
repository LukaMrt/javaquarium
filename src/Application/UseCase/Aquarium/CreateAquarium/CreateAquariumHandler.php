<?php

declare(strict_types=1);

namespace App\Application\UseCase\Aquarium\CreateAquarium;

use App\Domain\Aquarium\Entity\Aquarium;
use App\Domain\Aquarium\Repository\AquariumRepositoryInterface;
use App\Domain\Aquarium\ValueObject\AquariumId;
use App\Domain\Aquarium\ValueObject\TurnNumber;
use App\Domain\Shared\ValueObject\EntityName;
use Symfony\Component\ObjectMapper\ObjectMapperInterface;

final readonly class CreateAquariumHandler
{
    public function __construct(
        private AquariumRepositoryInterface $aquariumRepository,
        private ObjectMapperInterface $objectMapper,
    ) {
    }

    public function handle(CreateAquariumCommand $command): CreateAquariumResponse
    {
        $aquariumId = AquariumId::fromString($command->aquariumId);
        $existingAquarium = $this->aquariumRepository->findById($aquariumId);

        if ($existingAquarium instanceof Aquarium) {
            return new CreateAquariumResponse($existingAquarium->getId()->toString());
        }

        $aquarium = new Aquarium(
            $aquariumId,
            new EntityName($command->name),
            new TurnNumber(0),
        );

        $this->aquariumRepository->save($aquarium);

        return $this->objectMapper->map($aquarium, CreateAquariumResponse::class);
    }
}
