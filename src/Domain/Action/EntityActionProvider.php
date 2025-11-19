<?php

declare(strict_types=1);

namespace App\Domain\Action;

use App\Domain\Algae\Entity\Algae;
use App\Domain\Aquarium\Entity\Aquarium;
use App\Domain\Fish\Entity\Fish;
use App\Domain\Service\FeedingService;
use App\Domain\Service\RandomGeneratorInterface;
use App\Domain\Service\ReproductionService;

/**
 * Provides actions for entities, decoupling Aquarium from concrete action classes.
 */
final readonly class EntityActionProvider implements ActionProviderInterface
{
    public function __construct(
        private FeedingService $feedingService,
        private ReproductionService $reproductionService,
        private RandomGeneratorInterface $randomGenerator
    ) {
    }

    public function getActionsFor(object $entity, Aquarium $aquarium): array
    {
        return match (true) {
            $entity instanceof Algae => [new AlgaeGrowAction($entity, $aquarium)],
            $entity instanceof Fish => $this->getFishActions($entity, $aquarium),
            default => []
        };
    }

    /**
     * @return EntityActionInterface[]
     */
    private function getFishActions(Fish $fish, Aquarium $aquarium): array
    {
        // Priority: feeding before reproduction
        if ($fish->isHungry()) {
            return [
                new FishFeedAction(
                    $fish,
                    $aquarium,
                    $this->feedingService,
                    $this->randomGenerator
                )
            ];
        }

        return [
            new FishReproduceAction(
                $fish,
                $aquarium,
                $this->reproductionService,
                $this->randomGenerator
            )
        ];
    }
}
