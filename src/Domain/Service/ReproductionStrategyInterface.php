<?php

declare(strict_types=1);

namespace App\Domain\Service;

use App\Domain\Aquarium\Entity\Aquarium;
use App\Domain\Fish\Entity\Fish;
use App\Domain\Fish\ValueObject\SexualBehaviorType;
use Symfony\Component\DependencyInjection\Attribute\AutoconfigureTag;

#[AutoconfigureTag('app.reproduction_strategy')]
interface ReproductionStrategyInterface
{
    /**
     * Updates the fish's sex according to the reproduction strategy.
     * For monosexual: no change
     * For protandrous: changes based on age
     * For opportunistic: adapts based on partner
     */
    public function updateSex(Fish $fish, ?Fish $partner = null): void;

    /**
     * Finds all valid reproduction partners for the given fish in the aquarium.
     *
     * @return Fish[]
     */
    public function findValidPartners(Aquarium $aquarium, Fish $fish): array;

    /**
     * Creates offspring from two parents.
     */
    public function reproduce(Fish $parent1, Fish $parent2, RandomGeneratorInterface $randomGenerator): Fish;

    /**
     * Checks if this strategy supports the given sexual behavior type.
     */
    public function supports(SexualBehaviorType $sexualBehavior): bool;
}
