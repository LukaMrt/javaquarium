<?php

declare(strict_types=1);

namespace App\Domain\Service;

use App\Domain\Fish\Entity\Fish;
use App\Domain\Shared\GameRules;

final readonly class HungerService
{
    public function isHungry(Fish $fish): bool
    {
        return $fish->isHungry();
    }

    public function applyHunger(Fish $fish): void
    {
        $fish->loseHealth(GameRules::HP_LOSS_PER_TURN);
    }
}
