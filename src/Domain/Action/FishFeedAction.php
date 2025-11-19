<?php

declare(strict_types=1);

namespace App\Domain\Action;

use App\Domain\Aquarium\Entity\Aquarium;
use App\Domain\Fish\Entity\Fish;
use App\Domain\Service\FeedingService;
use App\Domain\Service\RandomGeneratorInterface;

/**
 * Action representing a fish attempting to feed.
 */
final readonly class FishFeedAction implements EntityActionInterface
{
    public function __construct(
        private Fish $fish,
        private Aquarium $aquarium,
        private FeedingService $feedingService,
        private RandomGeneratorInterface $randomGenerator
    ) {
    }

    public function execute(): void
    {
        if ($this->fish->isDead() || !$this->fish->isHungry()) {
            return;
        }

        $this->feedingService->attemptFeeding($this->fish, $this->aquarium, $this->randomGenerator);
    }
}
