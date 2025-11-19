<?php

declare(strict_types=1);

namespace App\Domain\Action;

/**
 * Represents an action that can be performed by an entity during a turn.
 * Actions are executed in random order to simulate concurrent behavior.
 */
interface EntityActionInterface
{
    /**
     * Executes the action if the entity is still alive.
     */
    public function execute(): void;
}
