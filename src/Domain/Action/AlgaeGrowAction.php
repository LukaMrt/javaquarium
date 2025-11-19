<?php

declare(strict_types=1);

namespace App\Domain\Action;

use App\Domain\Algae\Entity\Algae;
use App\Domain\Aquarium\Entity\Aquarium;

/**
 * Action representing an algae growing and potentially splitting.
 */
final readonly class AlgaeGrowAction implements EntityActionInterface
{
    public function __construct(
        private Algae $algae,
        private Aquarium $aquarium
    ) {
    }

    public function execute(): void
    {
        if ($this->algae->isDead()) {
            return;
        }

        $offspring = $this->algae->grow();

        if ($offspring instanceof Algae) {
            $this->aquarium->addAlgae($offspring);
        }
    }
}
