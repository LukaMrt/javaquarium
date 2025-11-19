<?php

declare(strict_types=1);

namespace App\Domain\Service;

use App\Domain\Algae\Entity\Algae;
use App\Domain\Aquarium\Entity\Aquarium;
use App\Domain\Fish\Entity\Fish;
use App\Domain\Fish\ValueObject\Diet;
use App\Domain\Shared\GameRules;

final readonly class HerbivorousFeedingStrategy implements FeedingStrategyInterface
{
    public function supports(Diet $diet): bool
    {
        return $diet === Diet::HERBIVOROUS;
    }

    public function findValidTargets(Aquarium $aquarium, Fish $predator): array
    {
        return array_values(array_filter(
            $aquarium->getAlgae(),
            fn(Algae $algae): bool => !$algae->isDead()
        ));
    }

    public function feed(Fish $predator, object $target): void
    {
        assert($target instanceof Algae, 'Herbivorous fish can only eat algae');

        $predator->gainHealth(GameRules::HERBIVOROUS_HP_GAIN);
        $target->loseHealth(GameRules::ALGAE_HP_LOSS_WHEN_EATEN);
    }
}
