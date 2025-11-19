<?php

declare(strict_types=1);

namespace App\Domain\Shared;

final class GameRules
{
    public const int INITIAL_HP = 10;

    public const int MAX_HP = 10;

    // Age and lifespan
    public const int MAX_AGE = 20;

    // Hunger and turn mechanics
    public const int HUNGER_THRESHOLD = 5;

    public const int FISH_HP_LOSS_PER_TURN = 1;

    // Feeding mechanics - HP gains
    public const int HERBIVOROUS_HP_GAIN = 3;

    public const int CARNIVOROUS_HP_GAIN = 5;

    // Feeding mechanics - HP losses
    public const int FISH_HP_LOSS_WHEN_ATTACKED = 4;

    public const int ALGAE_HP_LOSS_WHEN_EATEN = 2;

    // Algae growth
    public const int ALGAE_GROWTH_HP = 1;

    public const int ALGAE_SPLIT_THRESHOLD = 10;
}
