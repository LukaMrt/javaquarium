<?php

declare(strict_types=1);

namespace App\Domain\Service;

use App\Domain\Aquarium\Entity\Aquarium;
use App\Domain\Fish\Entity\Fish;
use App\Domain\Fish\ValueObject\SexualBehaviorType;
use Symfony\Component\DependencyInjection\Attribute\AutowireIterator;

final readonly class ReproductionService
{
    /**
     * @param iterable<ReproductionStrategyInterface> $strategies
     */
    public function __construct(
        #[AutowireIterator('app.reproduction_strategy')]
        private iterable $strategies
    ) {
    }

    public function attemptReproduction(
        Fish $fish,
        Aquarium $aquarium,
        RandomGeneratorInterface $randomGenerator
    ): void {
        $sexualBehavior = $fish->getSpecies()->getSexualBehavior();
        $strategy = $this->resolveStrategy($sexualBehavior);

        // Update fish sex first (for protandrous)
        $strategy->updateSex($fish);

        // Find valid partners
        $validPartners = $strategy->findValidPartners($aquarium, $fish);

        if ($validPartners === []) {
            return;
        }

        $partner = $randomGenerator->selectRandom($validPartners);

        // Opportunistic adapts to partner
        $strategy->updateSex($fish, $partner);

        // Create offspring
        $offspring = $strategy->reproduce($fish, $partner, $randomGenerator);
        $aquarium->addFish($offspring);
    }

    private function resolveStrategy(SexualBehaviorType $sexualBehavior): ReproductionStrategyInterface
    {
        foreach ($this->strategies as $strategy) {
            if ($strategy->supports($sexualBehavior)) {
                return $strategy;
            }
        }

        throw new \LogicException('No reproduction strategy found for sexual behavior: ' . $sexualBehavior->value);
    }
}
