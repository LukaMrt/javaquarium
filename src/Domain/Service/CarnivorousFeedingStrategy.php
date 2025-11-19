<?php

declare(strict_types=1);

namespace App\Domain\Service;

use App\Domain\Aquarium\Entity\Aquarium;
use App\Domain\Fish\Entity\Fish;
use App\Domain\Fish\ValueObject\Diet;
use App\Domain\Shared\GameRules;

final readonly class CarnivorousFeedingStrategy implements FeedingStrategyInterface
{
    public function supports(Diet $diet): bool
    {
        return $diet === Diet::CARNIVOROUS;
    }

    public function findValidTargets(Aquarium $aquarium, Fish $predator): array
    {
        return array_values(array_filter(
            $aquarium->getFishes(),
            fn(Fish $prey): bool => !$prey->isDead()
                && !$predator->getId()->equals($prey->getId())
                && $predator->getSpecies() !== $prey->getSpecies()
        ));
    }

    public function feed(Fish $predator, object $target): void
    {
        assert($target instanceof Fish, 'Carnivorous fish can only eat other fish');

        $predator->gainHealth(GameRules::CARNIVOROUS_HP_GAIN);
        $target->loseHealth(GameRules::FISH_HP_LOSS_WHEN_ATTACKED);
    }
}
