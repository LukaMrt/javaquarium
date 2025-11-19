<?php

declare(strict_types=1);

namespace App\Domain\Action;

use App\Domain\Aquarium\Entity\Aquarium;

/**
 * Provides actions for entities during a turn.
 * Decouples the Aquarium from concrete action implementations.
 */
interface ActionProviderInterface
{
    /**
     * Creates the appropriate actions for the given entity.
     *
     * @return EntityActionInterface[]
     */
    public function getActionsFor(object $entity, Aquarium $aquarium): array;
}
