<?php

declare(strict_types=1);

namespace App\Domain\Service;

use App\Domain\Aquarium\Entity\Aquarium;
use App\Domain\Fish\Entity\Fish;
use App\Domain\Fish\ValueObject\Diet;
use Symfony\Component\DependencyInjection\Attribute\AutowireIterator;

final readonly class FeedingService
{
    /**
     * @param iterable<FeedingStrategyInterface> $strategies
     */
    public function __construct(
        #[AutowireIterator('app.feeding_strategy')]
        private iterable $strategies
    ) {
    }

    public function attemptFeeding(
        Fish $predator,
        Aquarium $aquarium,
        RandomGeneratorInterface $randomGenerator
    ): void {
        $diet = $predator->getSpecies()->getDiet();
        $strategy = $this->resolveStrategy($diet);

        $validTargets = $strategy->findValidTargets($aquarium, $predator);

        if ($validTargets === []) {
            return;
        }

        $target = $randomGenerator->selectRandom($validTargets);
        $strategy->feed($predator, $target);
    }

    private function resolveStrategy(Diet $diet): FeedingStrategyInterface
    {
        foreach ($this->strategies as $strategy) {
            if ($strategy->supports($diet)) {
                return $strategy;
            }
        }

        throw new \LogicException('No feeding strategy found for diet: ' . $diet->value);
    }
}
