<?php

declare(strict_types=1);

namespace App\Domain\Action;

use App\Domain\Algae\Entity\Algae;
use App\Domain\Aquarium\Entity\Aquarium;
use App\Domain\Fish\Entity\Fish;
use App\Domain\Service\FeedingService;
use App\Domain\Service\RandomGeneratorInterface;

/**
 * Provides actions for entities, decoupling Aquarium from concrete action classes.
 */
final readonly class EntityActionProvider implements ActionProviderInterface
{
    public function __construct(
        private FeedingService $feedingService,
        private RandomGeneratorInterface $randomGenerator
    ) {
    }

    public function getActionsFor(object $entity, Aquarium $aquarium): array
    {
        return match (true) {
            $entity instanceof Algae => [new AlgaeGrowAction($entity, $aquarium)],
            $entity instanceof Fish => [new FishFeedAction($entity, $aquarium, $this->feedingService, $this->randomGenerator)],
            default => []
        };
    }
}
