<?php

declare(strict_types=1);

namespace App\Domain\Service;

use App\Domain\Algae\Entity\Algae;
use App\Domain\Algae\ValueObject\AlgaeId;
use App\Domain\Shared\GameRules;
use App\Domain\Shared\ValueObject\Age;
use App\Domain\Shared\ValueObject\HealthPoints;

final readonly class AlgaeGrowthService
{
    /**
     * Makes the algae grow and potentially split.
     * Returns the new offspring algae if split occurred, null otherwise.
     */
    public function grow(Algae $algae): ?Algae
    {
        // 1. Natural growth
        $algae->gainHealth(GameRules::ALGAE_GROWTH_HP);

        // 2. Check for split
        if ($algae->getHealthPoints()->toInt() >= GameRules::ALGAE_SPLIT_THRESHOLD) {
            return $this->split($algae);
        }

        return null;
    }

    private function split(Algae $parent): Algae
    {
        $currentHp = $parent->getHealthPoints()->toInt();
        $halfHp = (int) floor($currentHp / 2);

        // Parent loses half HP
        $parent->loseHealth($halfHp);

        // Create offspring
        return new Algae(
            AlgaeId::generate(),
            $parent->getName(),
            Age::initial(),
            new HealthPoints($halfHp)
        );
    }
}
