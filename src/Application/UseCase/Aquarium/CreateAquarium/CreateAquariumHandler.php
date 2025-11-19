<?php

declare(strict_types=1);

namespace App\Application\UseCase\Aquarium\CreateAquarium;

use App\Application\Exception\ValidationException;
use App\Domain\Aquarium\Entity\Aquarium;
use App\Domain\Aquarium\Repository\AquariumRepositoryInterface;
use App\Domain\Aquarium\ValueObject\AquariumId;
use App\Domain\Aquarium\ValueObject\TurnNumber;
use App\Domain\Shared\ValueObject\EntityName;
use Symfony\Component\ObjectMapper\ObjectMapperInterface;
use Symfony\Component\Validator\Validator\ValidatorInterface;

final readonly class CreateAquariumHandler
{
    public function __construct(
        private AquariumRepositoryInterface $aquariumRepository,
        private ObjectMapperInterface $objectMapper,
        private ValidatorInterface $validator,
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

        // Validate the created aquarium entity
        $violations = $this->validator->validate($aquarium);
        if (count($violations) > 0) {
            throw new ValidationException($violations, 'Aquarium validation failed');
        }

        $this->aquariumRepository->save($aquarium);

        return $this->objectMapper->map($aquarium, CreateAquariumResponse::class);
    }
}
