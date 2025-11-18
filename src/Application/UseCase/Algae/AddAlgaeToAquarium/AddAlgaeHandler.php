<?php

declare(strict_types=1);

namespace App\Application\UseCase\Algae\AddAlgaeToAquarium;

use App\Domain\Aquarium\Entity\Aquarium;
use App\Application\Exception\AquariumNotFoundException;
use App\Domain\Algae\Entity\Algae;
use App\Domain\Algae\ValueObject\AlgaeId;
use App\Domain\Aquarium\Repository\AquariumRepositoryInterface;
use App\Domain\Aquarium\ValueObject\AquariumId;
use App\Domain\Shared\ValueObject\Age;
use App\Domain\Shared\ValueObject\EntityName;
use App\Domain\Shared\ValueObject\HealthPoints;
use Symfony\Component\ObjectMapper\ObjectMapperInterface;

final readonly class AddAlgaeHandler
{
    public function __construct(
        private AquariumRepositoryInterface $aquariumRepository,
        private ObjectMapperInterface $objectMapper,
    ) {
    }

    public function handle(AddAlgaeCommand $command): AddAlgaeResponse
    {
        $aquarium = $this->aquariumRepository->findById(AquariumId::fromString($command->aquariumId));

        if (!$aquarium instanceof Aquarium) {
            throw new AquariumNotFoundException('Aquarium not found with ID: ' . $command->aquariumId);
        }

        $algae = new Algae(
            AlgaeId::fromString($command->algaeId),
            new EntityName($command->name),
            Age::initial(),
            HealthPoints::initial(),
        );

        $aquarium->addAlgae($algae);
        $this->aquariumRepository->save($aquarium);

        return $this->objectMapper->map($algae, AddAlgaeResponse::class);
    }
}
