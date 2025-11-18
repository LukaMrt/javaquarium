<?php

declare(strict_types=1);

namespace App\Domain\Service;

use App\Domain\Algae\Entity\Algae;
use App\Domain\Fish\Entity\Fish;
use App\Domain\Shared\GameRules;

final readonly class FeedingService
{
    public function canFeed(Fish $predator, Fish|Algae $target): bool
    {
        // Cannot eat self
        if ($target instanceof Fish && $predator->getId()->equals($target->getId())) {
            return false;
        }

        // Cannot eat same species
        if ($target instanceof Fish && $predator->getSpecies() === $target->getSpecies()) {
            return false;
        }

        $diet = $predator->getSpecies()->getDiet();

        // Herbivorous can only eat algae
        if ($diet->isHerbivorous()) {
            return $target instanceof Algae;
        }

        // Carnivorous can only eat fish
        if ($diet->isCarnivorous()) {
            return $target instanceof Fish;
        }

        return false;
    }

    public function feed(Fish $predator, Fish|Algae $target): FeedingResult
    {
        // Check if target is dead
        if ($target->isDead()) {
            return FeedingResult::failure('Target is already dead');
        }

        // Check if feeding is allowed
        if (!$this->canFeed($predator, $target)) {
            return FeedingResult::failure('Invalid feeding attempt');
        }

        $diet = $predator->getSpecies()->getDiet();

        if ($diet->isHerbivorous() && $target instanceof Algae) {
            // Herbivore eats algae
            $predator->gainHealth(GameRules::HERBIVOROUS_HP_GAIN);
            $target->loseHealth(GameRules::ALGAE_HP_LOSS_WHEN_EATEN);
        } elseif ($diet->isCarnivorous() && $target instanceof Fish) {
            // Carnivore eats fish
            $predator->gainHealth(GameRules::CARNIVOROUS_HP_GAIN);
            $target->loseHealth(GameRules::FISH_HP_LOSS_WHEN_ATTACKED);
        }

        return FeedingResult::success();
    }
}
