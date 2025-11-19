<?php

declare(strict_types=1);

namespace App\Domain\Action;

use App\Domain\Aquarium\Entity\Aquarium;
use App\Domain\Fish\Entity\Fish;
use App\Domain\Service\RandomGeneratorInterface;
use App\Domain\Service\ReproductionService;
use App\Domain\Shared\GameRules;

final readonly class FishReproduceAction implements EntityActionInterface
{
    public function __construct(
        private Fish $fish,
        private Aquarium $aquarium,
        private ReproductionService $reproductionService,
        private RandomGeneratorInterface $randomGenerator
    ) {
    }

    public function execute(): void
    {
        if ($this->fish->isDead()
            || $this->fish->isHungry()
            || $this->fish->getAge()->toInt() < GameRules::MIN_REPRODUCTION_AGE
        ) {
            return;
        }

        $this->reproductionService->attemptReproduction(
            $this->fish,
            $this->aquarium,
            $this->randomGenerator
        );
    }
}
