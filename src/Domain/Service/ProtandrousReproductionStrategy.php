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

final readonly class ProtandrousReproductionStrategy implements ReproductionStrategyInterface
{
    private const int AGE_THRESHOLD = 10;

    public function updateSex(Fish $fish, ?Fish $partner = null): void
    {
        $newSex = $fish->getAge()->toInt() <= self::AGE_THRESHOLD
            ? Sex::MALE
            : Sex::FEMALE;

        $fish->setSex($newSex);
    }

    public function findValidPartners(Aquarium $aquarium, Fish $fish): array
    {
        return array_values(array_filter(
            $aquarium->getFishes(),
            function (Fish $candidate) use ($fish): bool {
                if ($candidate->isDead()
                    || $fish->getId()->equals($candidate->getId())
                    || $candidate->getAge()->toInt() < GameRules::MIN_REPRODUCTION_AGE
                    || $fish->getSpecies() !== $candidate->getSpecies()
                ) {
                    return false;
                }

                // Update candidate's sex based on age
                $this->updateSex($candidate);

                // Must have opposite sex (after age-based update)
                return $fish->getSex() !== $candidate->getSex();
            }
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
        return $sexualBehavior === SexualBehaviorType::PROTANDROUS;
    }
}
