<?php

declare(strict_types=1);

namespace App\Domain\Service;

use App\Domain\Aquarium\Entity\Aquarium;
use App\Domain\Fish\Entity\Fish;
use App\Domain\Fish\ValueObject\Diet;
use Symfony\Component\DependencyInjection\Attribute\AutoconfigureTag;

#[AutoconfigureTag('app.feeding_strategy')]
interface FeedingStrategyInterface
{
    /**
     * Finds all valid feeding targets for the given predator in the aquarium.
     *
     * @return array<int, object> Array of valid targets (Fish or Algae)
     */
    public function findValidTargets(Aquarium $aquarium, Fish $predator): array;

    /**
     * Applies feeding effects: predator gains health, target loses health.
     */
    public function feed(Fish $predator, object $target): void;

    /**
     * Checks if this strategy supports the given diet type.
     */
    public function supports(Diet $diet): bool;
}
