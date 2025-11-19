<?php

declare(strict_types=1);

namespace App\Domain\Service;

use App\Domain\Aquarium\Entity\Aquarium;
use App\Domain\Fish\Entity\Fish;
use App\Domain\Fish\ValueObject\FishId;
use App\Domain\Fish\ValueObject\Sex;
use App\Domain\Fish\ValueObject\SexualBehaviorType;
use App\Domain\Shared\GameRules;
use App\Domain\Shared\ValueObject\Age;
use App\Domain\Shared\ValueObject\EntityName;
use App\Domain\Shared\ValueObject\HealthPoints;

final readonly class MonosexualReproductionStrategy implements ReproductionStrategyInterface
{
    public function updateSex(Fish $fish, ?Fish $partner = null): void
    {
        // Monosexual: sex never changes
    }

    public function findValidPartners(Aquarium $aquarium, Fish $fish): array
    {
        return array_values(array_filter(
            $aquarium->getFishes(),
            fn(Fish $candidate): bool =>
                !$candidate->isDead()
                && !$fish->getId()->equals($candidate->getId())
                && $candidate->getAge()->toInt() >= GameRules::MIN_REPRODUCTION_AGE
                && $fish->getSpecies() === $candidate->getSpecies()
                && $fish->getSex() !== $candidate->getSex()
        ));
    }

    public function reproduce(Fish $parent1, Fish $parent2, RandomGeneratorInterface $randomGenerator): Fish
    {
        $species = $parent1->getSpecies();
        $sex = $randomGenerator->randomBoolean() ? Sex::MALE : Sex::FEMALE;

        return new Fish(
            id: FishId::generate(),
            name: new EntityName($species->getDisplayName()),
            species: $species,
            sex: $sex,
            age: Age::initial(),
            healthPoints: new HealthPoints(GameRules::INITIAL_HP)
        );
    }

    public function supports(SexualBehaviorType $sexualBehavior): bool
    {
        return $sexualBehavior === SexualBehaviorType::MONOSEXUAL;
    }
}
