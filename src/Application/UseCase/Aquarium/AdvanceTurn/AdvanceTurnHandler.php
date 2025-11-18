<?php

declare(strict_types=1);

namespace App\Application\UseCase\Aquarium\AdvanceTurn;

use App\Domain\Aquarium\Entity\Aquarium;
use App\Application\Exception\AquariumNotFoundException;
use App\Domain\Aquarium\Repository\AquariumRepositoryInterface;
use App\Domain\Aquarium\ValueObject\AquariumId;
use App\Domain\Service\RandomGeneratorInterface;
use Symfony\Component\ObjectMapper\ObjectMapperInterface;

final readonly class AdvanceTurnHandler
{
    public function __construct(
        private AquariumRepositoryInterface $aquariumRepository,
        private ObjectMapperInterface $objectMapper,
        private RandomGeneratorInterface $randomGenerator,
    ) {
    }

    public function handle(AdvanceTurnCommand $command): AdvanceTurnResponse
    {
        $aquarium = $this->aquariumRepository->findById(AquariumId::fromString($command->aquariumId));

        if (!$aquarium instanceof Aquarium) {
            throw new AquariumNotFoundException('Aquarium not found with ID: ' . $command->aquariumId);
        }

        $aquarium->advanceTurn($this->randomGenerator);

        $this->aquariumRepository->save($aquarium);

        return $this->objectMapper->map($aquarium, AdvanceTurnResponse::class);
    }
}
